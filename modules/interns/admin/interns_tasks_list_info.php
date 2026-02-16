<?php
if (!defined('_VALID_BBC')) exit('No direct script access allowed');

$db = $GLOBALS['db'];
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$form_add = _lib('pea', 'interns_tasks');
$form_add->initEdit($id > 0 ? "WHERE id=$id" : "");

$form_add->edit->addInput('title', 'sqlplaintext');
$form_add->edit->input->title->setTitle('Title');
$form_add->edit->input->title->setRequire();

$form_add->edit->addInput('description', 'sqlplaintext');
$form_add->edit->input->description->setTitle('Description');

$form_add->edit->addInput('timeline', 'sqlplaintext');
$form_add->edit->input->timeline->setTitle('timeline');
$form_add->edit->input->timeline->setNumberFormat(true);
$form_add->edit->input->timeline->setRequire();

$form_add->edit->addInput('task_type_id', 'selecttable');
$form_add->edit->input->task_type_id->setTitle('Task Type');
$form_add->edit->input->task_type_id->setReferenceTable('interns_tasks_type');
$form_add->edit->input->task_type_id->setReferenceField('type_name', 'id');
$form_add->edit->input->task_type_id->setPlainText(true);

$form_add->edit->action();

$form_add->edit->setSaveTool(false);
$form_add->edit->setResetTool(false);

echo $form_add->edit->getForm();


