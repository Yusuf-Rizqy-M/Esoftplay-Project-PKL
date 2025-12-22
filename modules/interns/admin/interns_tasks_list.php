<?php
if (!defined('_VALID_BBC')) exit('No direct script access allowed');

if (!empty($_GET['act']) && $_GET['act'] == 'sample_tasklist') {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment;filename="sample_import_tasklist.csv"');
    header('Cache-Control: no-cache, no-store, must-revalidate');
    header('Pragma: no-cache');
    header('Expires: 0');
    echo "email_intern,task_title,notes\n";
    echo "yusufhabib290@gmail.com,Install Linux,kerjakan dalam 1 minggu\n";
    echo "jojo@gmail.com,Setup Development Environment,install VSCode dan Git\n";
    die();
}

$formSearch = _lib('pea', 'interns_tasks_list');
$formSearch->initSearch();
$formSearch->search->addInput('interns_id','selecttable');
$formSearch->search->input->interns_id->setTitle(lang('Type'));
$formSearch->search->input->interns_id->addOption(lang('---- Filter by Name ----'), '');
$formSearch->search->input->interns_id->setReferenceTable('interns');
$formSearch->search->input->interns_id->setReferenceField('name', 'id');
$formSearch->search->addInput('notes', 'keyword');
$formSearch->search->input->notes->setTitle('Notes');
$formSearch->search->input->notes->addSearchField('notes', false);
$add_sql = $formSearch->search->action();
echo $formSearch->search->getForm();

$formList = _lib('pea', 'interns_tasks_list');
$formList->initRoll($add_sql . ' ORDER BY id DESC', 'id');
$formList->roll->setDeleteTool(false);
$formList->roll->setSaveTool(false);
$formList->roll->addInput('id','sqlplaintext');
$formList->roll->input->id->setDisplayColumn(false);
$formList->roll->addInput('interns_id','selecttable');
$formList->roll->input->interns_id->setTitle('Intern Name');
$formList->roll->input->interns_id->setPlaintext(true);
$formList->roll->input->interns_id->setReferenceTable('interns');
$formList->roll->input->interns_id->setReferenceField('name','id');
$formList->roll->addInput('interns_tasks_id','selecttable');
$formList->roll->input->interns_tasks_id->setTitle('Task');
$formList->roll->input->interns_tasks_id->setPlaintext(true);
$formList->roll->input->interns_tasks_id->setReferenceTable('interns_tasks');
$formList->roll->input->interns_tasks_id->setReferenceField('title','id');
$formList->roll->addInput('notes','sqlplaintext');
$formList->roll->addInput('status','select');
$formList->roll->input->status->setTitle('Status');
$formList->roll->input->status->addOption('To Do', 1);
$formList->roll->input->status->addOption('In Progress', 2);
$formList->roll->input->status->addOption('Submit', 3);
$formList->roll->input->status->addOption('Revised', 4);
$formList->roll->input->status->addOption('Done', 5);
$formList->roll->input->status->addOption('Cancel', 6);
$formList->roll->addInput('created','sqlplaintext');
$formList->roll->input->created->setTitle('Created');
$formList->roll->addInput('updated','sqlplaintext');
$formList->roll->input->updated->setTitle('Updated');
$formList->roll->action();

if (!empty($_POST['roll_submit_update'])) {
    if (!empty($_POST['roll_status']) && is_array($_POST['roll_status']) && !empty($_POST['roll_id']) && is_array($_POST['roll_id'])) {
        foreach ($_POST['roll_id'] as $index => $id) {
            $id = (int)$id;
            $new_status = (int)($_POST['roll_status'][$index] ?? 1);
            $interns_id = $db->getOne("SELECT interns_id FROM interns_tasks_list WHERE id = {$id}");
            $interns_id = $interns_id ? $interns_id : 1;
            $db->Execute("INSERT INTO interns_tasks_list_history (interns_id, interns_tasks_list_id, status, created) VALUES ({$interns_id}, {$id}, {$new_status}, NOW())");
        }
    }
}

$output = $formList->roll->getForm();
global $db;
if (preg_match_all('/<option value="(\d+)"[^>]*>(\d+)<\/option>/', $output, $matches, PREG_SET_ORDER)) {
    foreach ($matches as $match) {
        $option_full = $match[0];
        $id = $match[1];
        $current_status = $match[2];
        $latest = $db->getOne("SELECT status FROM interns_tasks_list_history WHERE interns_tasks_list_id = {$id} ORDER BY created DESC LIMIT 1");
        if ($latest !== null && $latest != $current_status) {
            $new_option = str_replace(">{$current_status}<", ">{$latest}<", $option_full);
            $new_option = str_replace('selected', '', $new_option);
            $new_option = str_replace("value=\"{$latest}\"", "value=\"{$latest}\" selected", $new_option);
            $output = str_replace($option_full, $new_option, $output);
        }
    }
}

echo '<div class="panel panel-default">';
echo '<div class="panel-heading"><h3 class="panel-title">Daftar Tugas Intern</h3></div>';
echo '<div class="panel-body">';
echo $output;
echo '</div>';
echo '</div>';

include 'interns_tasks_list_edit.php';
echo '<div class="panel panel-default">';
echo '<div class="panel-heading"><h3 class="panel-title">Add / Edit Intern Task</h3></div>';
echo '<div class="panel-body">';
echo $formAdd->edit->getForm();
echo '</div>';
echo '</div>';
?>

<div class="col-xs-12 no-both">
    <div class="panel-group" id="accordion_tasklist_import">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title collapsed" data-toggle="collapse" data-parent="#accordion_tasklist_import" href="#import_tasklist_panel" style="cursor: pointer;">
                    Klik Disini Untuk Import Interns Task List From Excel (CSV)
                </h4>
            </div>
            <div id="import_tasklist_panel" class="panel-collapse collapse">
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="panel-body">
                        <div class="form-group">
                            <label>Upload File</label>
                            <input type="file" name="excel_tasklist" class="form-control" accept=".csv" required />
                            <div class="help-block">
                                Upload file daftar tugas intern dalam format CSV. Download sample file 
                                <a href="?mod=interns.interns_tasks_list&act=sample_tasklist">sini</a> 
                                (urutan: email_intern,task_title,notes).
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <button type="submit" name="import_tasklist" value="upload" class="btn btn-default">
                            <?php echo icon('fa-upload') ?> Upload Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
if (!empty($_POST['import_tasklist']) && $_POST['import_tasklist'] == 'upload' && !empty($_FILES['excel_tasklist']['tmp_name'])) {
    global $db;
    $file = $_FILES['excel_tasklist']['tmp_name'];
    $handle = fopen($file, "r");
    if ($handle === false) {
        echo '<div class="alert alert-danger">Gagal membuka file CSV!</div>';
        exit;
    }

    $success = $fail = 0;
    $row = 0;
    $messages = [];
    $success_items = [];

    while (($data = fgetcsv($handle, 1000, ",")) !== false) {
        $row++;
        if ($row == 1) continue;
        if (count($data) < 2) continue;

        $email_intern = trim($data[0]);
        $task_title   = trim($data[1]);
        $notes        = trim($data[2] ?? '');

        if (empty($email_intern) || empty($task_title)) {
            $messages[] = '<li class="text-danger">Baris '.$row.': Skip - email intern atau task title kosong</li>';
            $fail++;
            continue;
        }

        $intern = $db->getRow("SELECT id FROM interns WHERE email='".addslashes($email_intern)."'");
        if (!$intern) {
            $messages[] = '<li class="text-danger">Baris '.$row.': Skip - Email intern <b>'.htmlspecialchars($email_intern).'</b> tidak ditemukan</li>';
            $fail++;
            continue;
        }

        $task = $db->getRow("SELECT id FROM interns_tasks WHERE title='".addslashes($task_title)."'");
        if (!$task) {
            $messages[] = '<li class="text-danger">Baris '.$row.': Skip - Task title <b>'.htmlspecialchars($task_title).'</b> tidak ditemukan</li>';
            $fail++;
            continue;
        }

        if ($db->getOne("SELECT id FROM interns_tasks_list WHERE interns_id={$intern['id']} AND interns_tasks_id={$task['id']}")) {
            $messages[] = '<li class="text-danger">Baris '.$row.': Skip - Tugas <b>'.htmlspecialchars($task_title).'</b> sudah diberikan ke intern ini</li>';
            $fail++;
            continue;
        }

        $q = "INSERT INTO interns_tasks_list
              (interns_id, interns_tasks_id, notes, status, created, updated)
              VALUES
              ({$intern['id']}, {$task['id']}, '".addslashes($notes)."', 1, NOW(), NOW())";

        if ($db->Execute($q)) {
            $messages[] = '<li class="text-success">Baris '.$row.': Tugas <b>'.htmlspecialchars($task_title).'</b> berhasil diberikan ke <b>'.htmlspecialchars($email_intern).'</b></li>';
            $success_items[] = htmlspecialchars($email_intern) . ' → ' . htmlspecialchars($task_title);
            $success++;
        } else {
            $messages[] = '<li class="text-danger">Baris '.$row.': Gagal insert</li>';
            $fail++;
        }
    }

    fclose($handle);

    echo '<div class="alert alert-info" style="margin-top: 15px;"><h4>Hasil Import Tugas Intern:</h4><ul>';
    foreach ($messages as $msg) {
        echo $msg;
    }
    echo '</ul>';
    echo '<strong>Selesai!</strong> Berhasil: <span class="text-success">'.$success.'</span> | Gagal: <span class="text-danger">'.$fail.'</span></div>';

    if ($success > 0) {
        $redirect_url = $_SERVER['PHP_SELF'] . (isset($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : '');
?>

<style>
.loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.95);
    z-index: 9999;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    text-align: center;
}
.loader-spinner {
    border: 8px solid #f3f3f3;
    border-top: 8px solid #3498db;
    border-radius: 50%;
    width: 60px;
    height: 60px;
    animation: spin 1.5s linear infinite;
    margin-bottom: 20px;
}
.loading-text {
    font-family: Arial, sans-serif;
    font-size: 20px;
    color: #333;
    font-weight: bold;
    max-width: 600px;
}
.success-list {
    margin-top: 20px;
    text-align: left;
    max-height: 200px;
    overflow-y: auto;
    padding: 10px;
    background: #f0f8ff;
    border: 1px solid #ccc;
    border-radius: 6px;
    width: 90%;
    max-width: 500px;
}
.success-list li {
    margin-bottom: 5px;
}
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>

<div class="loading-overlay">
    <div class="loader-spinner"></div>
    <div class="loading-text">
        Import Berhasil!<br>
        <small>Sedang memperbarui data...</small>
    </div>
    <div class="success-list">
        <strong>Tugas baru yang berhasil ditambahkan (<?php echo $success; ?>):</strong>
        <?php if (count($success_items) <= 10): ?>
            <ul>
                <?php foreach ($success_items as $item): ?>
                    <li><?php echo $item; ?></li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <ul>
                <?php for ($i = 0; $i < 10; $i++): ?>
                    <li><?php echo $success_items[$i]; ?></li>
                <?php endfor; ?>
            </ul>
            <p style="margin-top: 10px;">+ <?php echo ($success - 10); ?> tugas lainnya</p>
        <?php endif; ?>
    </div>
</div>

<script type="text/javascript">
setTimeout(function() {
    window.location.href = "<?php echo $redirect_url; ?>";
}, 5000);
</script>

<?php
    }
}
?>