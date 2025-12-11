<?php if (!defined('_VALID_BBC')) exit('No direct script access allowed');

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
$formList->roll->addInput('notes','sqlplaintext');
$formList->roll->input->notes->setTitle('Notes');

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