<?php if (!defined('_VALID_BBC')) exit('No direct script access allowed');

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



//   $formSearch->search->input->interns_id->setReferenceCondition('`event_id` = '.$event_id);


$formSearch = _lib('pea', 'interns_tasks_list');
$formSearch->initSearch();
  $formSearch->search->addInput('interns_id','selecttable');
  $formSearch->search->input->interns_id->setTitle(lang('Type'));
  $formSearch->search->input->interns_id->addOption(lang('---- Filter by Name  ----'), '');
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
$formList->roll->input->interns_id->setTitle('Intern');
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
    Upload file daftar tugas intern dalam format CSV. Silahkan download "sample file" di
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
        if ($row == 1) continue;
        if (count($data) < 2) continue;
        $email_intern = trim($data[0]);
        $task_title = trim($data[1]);
        $notes = trim($data[2] ?? '');
        if (empty($email_intern) || empty($task_title)) {
            echo "<li>Baris $row: Skip (email intern atau task title kosong)</li>";
            $fail++;
            continue;
        }
        $intern = $db->getRow("SELECT id FROM interns WHERE email='".addslashes($email_intern)."'");
        if (!$intern) {
            echo "<li>Baris $row: Skip - Email intern <b>$email_intern</b> tidak ditemukan</li>";
            $fail++;
            continue;
        }
        $task = $db->getRow("SELECT id FROM interns_tasks WHERE title='".addslashes($task_title)."'");
        if (!$task) {
            echo "<li>Baris $row: Skip - Task title <b>$task_title</b> tidak ditemukan</li>";
            $fail++;
            continue;
        }
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