<?php if (!defined('_VALID_BBC')) exit('No direct script access allowed');

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$formAdd = _lib('pea', 'interns_tasks_list');
$formAdd->initEdit($id > 0 ? 'WHERE id='.$id : '');

$formAdd->edit->addInput('header','header');
$formAdd->edit->input->header->setTitle('Add / Edit Intern Task');

/* INTERN */
$formAdd->edit->addInput('interns_id','selecttable');
$formAdd->edit->input->interns_id->setTitle('Intern');
$formAdd->edit->input->interns_id->setModal();
$formAdd->edit->input->interns_id->setReferenceTable('interns');
$formAdd->edit->input->interns_id->setReferenceField('name','id');
$formAdd->edit->input->interns_id->setRequire();

/* TASK MASTER */
$formAdd->edit->addInput('interns_tasks_id','selecttable');
$formAdd->edit->input->interns_tasks_id->setTitle('Task');
$formAdd->edit->input->interns_tasks_id->setModal();
$formAdd->edit->input->interns_tasks_id->setReferenceTable('interns_tasks');
$formAdd->edit->input->interns_tasks_id->setReferenceField('title','id');
$formAdd->edit->input->interns_tasks_id->setRequire();

/* NOTES */
$formAdd->edit->addInput('notes','textarea');
$formAdd->edit->input->notes->setTitle('Notes');

/* STATUS */
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
