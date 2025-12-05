<?php if (!defined('_VALID_BBC')) exit('No direct script access allowed');

$form = _lib('pea', 'interns');
$form->initEdit('WHERE id='.intval($_GET['id']));

$form->edit->addInput('header','header');
$form->edit->input->header->setTitle('Edit Intern');

$form->edit->addInput('bbc_user_id','selecttable');
$form->edit->input->bbc_user_id->setTitle('User');
$form->edit->input->bbc_user_id->setModal();
$form->edit->input->bbc_user_id->setReferenceTable('bbc_user');
$form->edit->input->bbc_user_id->setReferenceField('id','username');
$form->edit->input->bbc_user_id->setRequire();

$form->edit->addInput('name','text');
$form->edit->input->name->setRequire();

$form->edit->addInput('school','text');
$form->edit->input->school->setRequire();

$form->edit->addInput('major','text');

$form->edit->addInput('start_date','date');
$form->edit->input->start_date;

$form->edit->addInput('end_date','date');
$form->edit->input->end_date;

$form->edit->addInput('publish','checkbox');

$form->edit->action();
echo $form->edit->getForm();
