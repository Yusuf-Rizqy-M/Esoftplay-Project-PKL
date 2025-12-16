<?php if (!defined('_VALID_BBC')) exit('No direct script access allowed');

// === DOWNLOAD SAMPLE — DI PALING ATAS, BYPASS SEMUA ===
if (!empty($_GET['act']) && $_GET['act'] == 'sample_tasklist') {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment;filename="sample_import_tasklist.csv"');
    header('Cache-Control: no-cache, no-store, must-revalidate');
    header('Pragma: no-cache');
    header('Expires: 0');
    echo "email_intern,task_title,notes\n";
    echo "yusuf@example.com,Install Linux,kerjakan dalam 1 minggu\n";
    echo "ahmad@example.com,Setup Development Environment,install VSCode dan Git\n";
    die();
}

$formSearch = _lib('pea', 'interns_tasks_list');
$formSearch->initSearch();

$formSearch->search->addInput('keyword', 'keyword');
$formSearch->search->input->keyword->addSearchField('notes', false);

$add_sql = $formSearch->search->action();
echo $formSearch->search->getForm();

$tabs = array(
    'Tasks' => '',
    'Add Task' => ''
);

include 'interns_tasks_list_edit.php';
$tabs['Add Task'] = $formAdd->edit->getForm();

$formList = _lib('pea', 'interns_tasks_list');
$formList->initRoll($add_sql . ' ORDER BY id DESC', 'id');

/* DISABLE DELETE */
$formList->roll->setDeleteTool(false);
$formList->roll->setSaveTool(true);

// id
$formList->roll->addInput('id','sqlplaintext');
$formList->roll->input->id->setDisplayColumn(false);

// INTERN
$formList->roll->addInput('interns_id','selecttable');
$formList->roll->input->interns_id->setTitle('Intern');
$formList->roll->input->interns_id->setPlaintext(true);
$formList->roll->input->interns_id->setReferenceTable('interns');
$formList->roll->input->interns_id->setReferenceField('name','id');

// TASK
$formList->roll->addInput('interns_tasks_id','selecttable');
$formList->roll->input->interns_tasks_id->setTitle('Task');
$formList->roll->input->interns_tasks_id->setPlaintext(true);
$formList->roll->input->interns_tasks_id->setReferenceTable('interns_tasks');
$formList->roll->input->interns_tasks_id->setReferenceField('title','id');

// NOTES
$formList->roll->addInput('notes','textarea');  // ini cukup! jadi editable inline
$formList->roll->input->notes->setTitle('Notes');
// Opsional: biar textarea lebih bagus tampilannya
$formList->roll->input->notes->setParam(array(
    'rows' => 3,
    'class' => 'form-control',
    'style' => 'width:100%; min-width:200px;'
));

// STATUS
$formList->roll->addInput('status','select');
$formList->roll->input->status->setTitle('Status');
$formList->roll->input->status->addOption('To Do', 1);
$formList->roll->input->status->addOption('In Progress', 2);
$formList->roll->input->status->addOption('Submit', 3);
$formList->roll->input->status->addOption('Revised', 4);
$formList->roll->input->status->addOption('Done', 5);
$formList->roll->input->status->addOption('Cancel', 6);

// CREATED & UPDATED
$formList->roll->addInput('created','sqlplaintext');
$formList->roll->input->created->setTitle('Created');
$formList->roll->addInput('updated','sqlplaintext');
$formList->roll->input->updated->setTitle('Updated');

$formList->roll->action();

/* === AUTO INSERT HISTORY SAAT STATUS DIUBAH === */
if (!empty($_POST['roll_submit_update'])) {
    if (!empty($_POST['roll_status']) && is_array($_POST['roll_status']) && !empty($_POST['roll_id']) && is_array($_POST['roll_id'])) {
        foreach ($_POST['roll_id'] as $index => $id) {
            $id = (int)$id;
            $new_status = (int)($_POST['roll_status'][$index] ?? 1);

            // Cek status lama dari history
            $old_status = $db->getOne("SELECT status FROM interns_tasks_list_history WHERE interns_tasks_list_id = {$id} ORDER BY created DESC LIMIT 1");

           // Ambil interns_id dari task utama
$interns_id = $db->getOne("SELECT interns_id FROM interns_tasks_list WHERE id = {$id}");
$interns_id = $interns_id ? $interns_id : 1; // default kalau gak ada

$db->Execute("INSERT INTO interns_tasks_list_history (interns_id, interns_tasks_list_id, status, created) VALUES ({$interns_id}, {$id}, {$new_status}, NOW())");
        }
    }
}

/* === TAMPILKAN FORM DULU === */
$output = $formList->roll->getForm();

/* === MANUAL GANTI STATUS DARI HISTORY TERBARU === */
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

$tabs['Tasks'] = $output;

echo tabs($tabs, 1, 'tabs_interns_tasks_list');
?>

<!-- IMPORT TUGAS INTERN -->
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
                            <input type="file" name="excel_tasklist" class="form-control" placeholder="upload di sini!" accept=".csv" required />
                            <div class="help-block">
                                Upload file daftar tugas intern dalam format CSV. Silahkan download "sample file" untuk menentukan kolom-kolom apa saja yang perlu diisikan di 
                                <a href="?mod=interns.interns_tasks_list&act=sample_tasklist">sini</a> (urutan: email_intern,task_title,notes).
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
// === IMPORT TUGAS INTERN ===
if (!empty($_POST['import_tasklist']) && $_POST['import_tasklist'] == 'upload' && !empty($_FILES['excel_tasklist']['tmp_name'])) {
    global $db;
    $file = $_FILES['excel_tasklist']['tmp_name'];
    $handle = fopen($file, "r");
    if ($handle === false) {
        die('<div class="alert alert-danger">Gagal buka file!</div>');
    }

    $success = $fail = 0;
    $row = 0;
    echo '<div class="alert alert-info"><h4>Hasil Import Tugas Intern:</h4><ul>';

    while (($data = fgetcsv($handle, 1000, ",")) !== false) {
        $row++;
        if ($row == 1) continue; // header
        if (count($data) < 2) continue;

        $email_intern = trim($data[0]);
        $task_title   = trim($data[1]);
        $notes        = trim($data[2] ?? '');

        if (empty($email_intern) || empty($task_title)) {
            echo "<li>Baris $row: Skip (email intern atau task title kosong)</li>";
            $fail++;
            continue;
        }
        
        // Cari intern berdasarkan email
        $intern = $db->getRow("SELECT id FROM interns WHERE email='".addslashes($email_intern)."'");
        if (!$intern) {
            echo "<li>Baris $row: Skip - Email intern <b>$email_intern</b> tidak ditemukan</li>";
            $fail++;
            continue;
        }

        // Cari task berdasarkan title
        $task = $db->getRow("SELECT id FROM interns_tasks WHERE title='".addslashes($task_title)."'");
        if (!$task) {
            echo "<li>Baris $row: Skip - Task title <b>$task_title</b> tidak ditemukan</li>";
            $fail++;
            continue;
        }

        // Cek sudah ada tugas yang sama
        if ($db->getOne("SELECT id FROM interns_tasks_list WHERE interns_id={$intern['id']} AND interns_tasks_id={$task['id']}")) {
            echo "<li>Baris $row: Skip - Tugas <b>$task_title</b> sudah diberikan ke intern ini</li>";
            $fail++;
            continue;
        }

        $q = "INSERT INTO interns_tasks_list 
              (interns_id, interns_tasks_id, notes, status, created, updated) 
              VALUES 
              ({$intern['id']}, {$task['id']}, '".addslashes($notes)."', 1, NOW(), NOW())";

        if ($db->Execute($q)) {
            echo "<li>Baris $row: Tugas <b>$task_title</b> berhasil diberikan ke <b>$email_intern</b></li>";
            $success++;
        } else {
            echo "<li>Baris $row: Gagal insert</li>";
            $fail++;
        }
    }
    fclose($handle);
    echo '</ul><b>Selesai!</b> Berhasil: '.$success.' | Gagal: '.$fail.'</div>';
}
?>