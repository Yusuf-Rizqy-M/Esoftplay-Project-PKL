<?php
if (!defined('_VALID_BBC')) exit('No direct script access allowed');

$db = $GLOBALS['db'];

$id = isset($_GET['interns_tasks_id']) ? intval($_GET['interns_tasks_id']) : 0;

$form_add = _lib('pea', 'interns_tasks');
$form_add->initEdit($id > 0 ? "WHERE id=$id" : "");

$task = $db->getRow('SELECT title, description FROM interns_tasks WHERE id = ' . $id);
$task_title = !empty($task['title']) ? $task['title'] : 'Task Not Found';

$form_add->edit->addInput('header', 'header');
$form_add->edit->input->header->setTitle($task_title);

$form_add->edit->addInput('title', 'sqlplaintext');
$form_add->edit->input->title->setTitle('Title');

$form_add->edit->addInput('description', 'sqlplaintext');
$form_add->edit->input->description->setTitle('Description');

$form_add->edit->addInput('timeline', 'sqlplaintext');
$form_add->edit->input->timeline->setTitle('Timeline');

$form_add->edit->addInput('task_type_id', 'selecttable');
$form_add->edit->input->task_type_id->setTitle('Task Type');
$form_add->edit->input->task_type_id->setReferenceTable('interns_tasks_type');
$form_add->edit->input->task_type_id->setReferenceField('type_name', 'id');
$form_add->edit->input->task_type_id->setPlainText(true);

$form_add->edit->addInput('interns', 'multiform');
$form_add->edit->input->interns->setTitle(lang('Add Tasks Interns'));
$form_add->edit->input->interns->setReferenceTable('interns_tasks_list');
$form_add->edit->input->interns->setReferenceField('interns_tasks_id', 'id');

$form_add->edit->input->interns->addInput('interns_id', 'selecttable', 'Name');
$form_add->edit->input->interns->addInput('notes', 'text', 'Notes');

$form_add->edit->input->interns->elements->interns_id->setReferenceTable('interns');
$form_add->edit->input->interns->elements->interns_id->setReferenceField('name', 'id');
$form_add->edit->input->interns->elements->interns_id->addOption('-- Select Interns --', '');

$form_add->edit->onSave(function($main_task_id) use ($db) {
    $db->Execute("UPDATE `interns_tasks_list` SET `status` = 1 
                  WHERE `interns_tasks_id` = {$main_task_id} 
                  AND (`status` IS NULL OR `status` = 0)");

    $assignments = $db->getAll("SELECT * FROM `interns_tasks_list` WHERE `interns_tasks_id` = {$main_task_id}");

    if (!empty($assignments)) {
        foreach ($assignments as $row) {
            $notes_safe = addslashes($row['notes']);
            $status     = ($row['status'] > 0) ? $row['status'] : 1;
            $is_exists = $db->getOne("SELECT 1 FROM `interns_tasks_list_history` 
                                      WHERE `interns_tasks_list_id` = {$row['id']} 
                                      AND `status` = {$status} 
                                      AND `notes` = '{$notes_safe}' LIMIT 1");

            if (!$is_exists) {
                $db->Execute("INSERT INTO `interns_tasks_list_history` 
                    (`interns_id`, `interns_tasks_list_id`, `status`, `notes`, `created`) 
                    VALUES 
                    ({$row['interns_id']}, {$row['id']}, {$status}, '{$notes_safe}', NOW())");
            }
        }
    }
});

$form_add->edit->action();
echo '<div class="panel panel-default"><div class="panel-body">' . $form_add->edit->getForm() . '</div></div>';