<?php
if (!defined('_VALID_BBC')) exit('No direct script access allowed');
$db = $GLOBALS['db'];
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id > 0) {
    $old = $db->getRow("SELECT * FROM interns_tasks_list WHERE id={$id}");
    $intern_name = $db->getOne("SELECT name FROM interns WHERE id={$old['interns_id']}");
    $task_title = $db->getOne("SELECT title FROM interns_tasks WHERE id={$old['interns_tasks_id']}");
}
$formAdd = _lib('pea', 'interns_tasks_list');
$formAdd->initEdit($id > 0 ? "WHERE id={$id}" : "");
$formAdd->edit->addInput('header','header');
$formAdd->edit->input->header->setTitle('Add / Edit Intern Task');
if ($id > 0) {
    $formAdd->edit->addInput('intern_name','plaintext');
    $formAdd->edit->input->intern_name->setTitle('Intern');
    $formAdd->edit->input->intern_name->setValue($intern_name);
    $formAdd->edit->addInput('interns_id','hidden');
    $formAdd->edit->addInput('task_title','plaintext');
    $formAdd->edit->input->task_title->setTitle('Task');
    $formAdd->edit->input->task_title->setValue($task_title);
    $formAdd->edit->addInput('interns_tasks_id','hidden');
} else {
    $formAdd->edit->addInput('interns_id','selecttable');
    $formAdd->edit->input->interns_id->setTitle('Intern');
    $formAdd->edit->input->interns_id->setModal();
    $formAdd->edit->input->interns_id->setReferenceTable('interns');
    $formAdd->edit->input->interns_id->setReferenceField('name','id');
    $formAdd->edit->input->interns_id->setRequire();
    $formAdd->edit->addInput('interns_tasks_id','selecttable');
    $formAdd->edit->input->interns_tasks_id->setTitle('Task');
    $formAdd->edit->input->interns_tasks_id->setModal();
    $formAdd->edit->input->interns_tasks_id->setReferenceTable('interns_tasks');
    $formAdd->edit->input->interns_tasks_id->setReferenceField('title','id');
    $formAdd->edit->input->interns_tasks_id->setRequire();
}
$formAdd->edit->addInput('notes','textarea');
$formAdd->edit->input->notes->setTitle('Notes');
$formAdd->edit->addInput('status','select');
$formAdd->edit->input->status->setTitle('Status');
$formAdd->edit->input->status->addOption('To Do', 1);
$formAdd->edit->input->status->addOption('In Progress', 2);
$formAdd->edit->input->status->addOption('Submit', 3);
$formAdd->edit->input->status->addOption('Revised', 4);
$formAdd->edit->input->status->addOption('Done', 5);
$formAdd->edit->input->status->addOption('Cancel', 6);
$formAdd->edit->input->status->setRequire();
$formAdd->edit->action();
if (!empty($_POST)) {
    if ($id == 0) {
        $new_id = $db->Insert_ID();
    } else {
        $new = $db->getRow("SELECT * FROM interns_tasks_list WHERE id={$id}");
        if (!empty($old) && !empty($new)) {
            $changes = [];
            foreach ($new as $key => $val) {
                if (isset($old[$key]) && $old[$key] != $val) {
                    $changes[] = strtoupper($key) . " berubah dari '" . $old[$key] . "' menjadi '" . $val . "'";
                }
            }
            if (!empty($changes)) {
                $report_text = implode("; ", $changes);
                $sql = "
                    INSERT INTO interns_tasks_list_history
                        (interns_id, interns_tasks_id, report, created)
                    VALUES
                        (
                            {$new['interns_id']},
                            {$new['interns_tasks_id']},
                            " . $db->quote($report_text) . ",
                            NOW()
                        )
                ";
                $db->Execute($sql);
            }
        }
        $db->Execute("UPDATE interns_tasks_list SET updated = NOW() WHERE id = {$id}");
    }
    $redirect_url = $_SERVER['PHP_SELF'] . (isset($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : '');
    header("Location: {$redirect_url}");
    exit;
}
if ($id > 0) {
    echo $formAdd->edit->getForm();
}
?>