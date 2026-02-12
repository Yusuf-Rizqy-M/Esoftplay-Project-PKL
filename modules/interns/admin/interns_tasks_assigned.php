<?php
if (!defined('_VALID_BBC')) exit('No direct script access allowed');

$db = $GLOBALS['db'];
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$form_add = _lib('pea', 'interns_tasks');
$form_add->initEdit($id > 0 ? "WHERE id=$id" : "");

$task = $db->getRow('select title,description from interns_tasks where id = ' . $id);

$form_add->edit->addInput('header', 'header');
$form_add->edit->input->header->setTitle($task['title']);

$form_add->edit->addInput('title', 'sqlplaintext');
$form_add->edit->input->title->setTitle('Title');

$form_add->edit->addInput('description', 'sqlplaintext');
$form_add->edit->input->description->setTitle('Description');

$form_add->edit->addInput('timeline', 'sqlplaintext');
$form_add->edit->input->timeline->setTitle('timeline');
$form_add->edit->input->timeline->setNumberFormat(true);

$form_add->edit->addInput('type', 'sqlplaintext');
$form_add->edit->input->type->setTitle('type');

$form_add->edit->addInput('interns', 'multiform');
$form_add->edit->input->interns->setTitle(lang('Add Tasks Interns'));
$form_add->edit->input->interns->setReferenceTable('interns_tasks_list');
$form_add->edit->input->interns->setReferenceField('interns_tasks_id', 'id');

$form_add->edit->input->interns->addInput('interns_id', 'selecttable', 'Name');
$form_add->edit->input->interns->addInput('notes', 'text', 'Notes');
$form_add->edit->input->interns_id->setReferenceTable('interns');
$form_add->edit->input->interns_id->setReferenceField('name', 'id');
$form_add->edit->input->interns_id->addOption('-- Select Interns --', '');

$form_add->edit->action();

echo '<div class="panel panel-default"><div class="panel-body">' . $form_add->edit->getForm() . '</div></div>';
