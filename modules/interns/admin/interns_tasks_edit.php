<?php if (!defined('_VALID_BBC')) exit('No direct script access allowed');

$id = intval($_GET['id']);

$form = _lib('pea', 'interns_tasks');
$form->initEdit('WHERE id='.$id);

$form->edit->addInput('header','header');
$form->edit->input->header->setTitle('Edit Task');

// Intern
$form->edit->addInput('interns_id','selecttable');
$form->edit->input->interns_id->setTitle('Intern');
$form->edit->input->interns_id->setModal();
$form->edit->input->interns_id->setReferenceTable('interns');
$form->edit->input->interns_id->setReferenceField('id','name');
$form->edit->input->interns_id->setRequire();

// Title
$form->edit->addInput('title','text');
$form->edit->input->title->setRequire();

// Description
$form->edit->addInput('description','textarea');

// created (auto set if empty)
$form->edit->addInput('created', 'text');
$form->edit->input->created->setTitle('Created');
$form->edit->input->created->setDefault(date('Y-m-d H:i:s'));
$form->edit->input->created->setReadonly();

// updated (auto update on save)
$form->edit->addInput('updated', 'hidden');
$form->edit->input->updated->setDefault(date('Y-m-d H:i:s'));

$form->edit->action();
echo $form->edit->getForm();
