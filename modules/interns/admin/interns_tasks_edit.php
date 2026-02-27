<?php
if (!defined('_VALID_BBC')) exit('No direct script access allowed');

$db = $GLOBALS['db'];
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$form_add = _lib('pea', 'interns_tasks');
$form_add->initEdit($id > 0 ? "WHERE id=$id" : "");

$form_add->edit->addInput('header', 'header');
$form_add->edit->input->header->setTitle($id > 0 ? 'Edit Task' : 'Add New Task');

$form_add->edit->addInput('title', 'text');
$form_add->edit->input->title->setTitle('Title');
$form_add->edit->input->title->setRequire();

$form_add->edit->addInput('description', 'textarea');
$form_add->edit->input->description->setTitle('Description');

$form_add->edit->addInput('timeline', 'text');
$form_add->edit->input->timeline->setTitle('Timeline (Days)');
$form_add->edit->input->timeline->setNumberFormat(true);
$form_add->edit->input->timeline->setRequire();

$form_add->edit->addInput('task_type_id', 'selecttable');
$form_add->edit->input->task_type_id->setTitle('Task Type');
$form_add->edit->input->task_type_id->setReferenceTable('interns_tasks_type');
$form_add->edit->input->task_type_id->setReferenceField('type_name', 'id');
$form_add->edit->input->task_type_id->setAllowNew(true);
$form_add->edit->input->task_type_id->setRequire();
$form_add->edit->input->task_type_id->addTip('Ketik tipe tugas baru atau pilih yang sudah ada.');

$form_add->edit->action();

echo '<div class="panel panel-default"><div class="panel-body">' . $form_add->edit->getForm() . '</div></div>';