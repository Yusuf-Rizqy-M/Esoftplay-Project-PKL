<?php if (!defined('_VALID_BBC')) exit('No direct script access allowed');

$formSearch = _lib('pea', 'interns');
$formSearch->initSearch();

$formSearch->search->addInput('keyword','keyword');
$formSearch->search->input->keyword->addSearchField('name', true);

$add_sql = $formSearch->search->action();
echo $formSearch->search->getForm();

$tabs = array(
  'Interns' => '',
  'Add Intern' => ''
);

/* ===========================
   FORM ADD INTERN
   =========================== */
$formAdd = _lib('pea', 'interns');
$formAdd->initEdit();

$formAdd->edit->addInput('header','header');
$formAdd->edit->input->header->setTitle('Add New Intern');

/* USER PICKER */
$formAdd->edit->addInput('bbc_user_id','selecttable');
$formAdd->edit->input->bbc_user_id->setTitle('User');
$formAdd->edit->input->bbc_user_id->setModal();
$formAdd->edit->input->bbc_user_id->setReferenceTable('bbc_user');

/*
  VALUE yg dikirim = id
  TEXT yg tampil = username
*/
$formAdd->edit->input->bbc_user_id->setReferenceField('username','id');
$formAdd->edit->input->bbc_user_id->setRequire();

/* NAME */
$formAdd->edit->addInput('name','text');
$formAdd->edit->input->name->setTitle('Name');
$formAdd->edit->input->name->setRequire();

/* SCHOOL */
$formAdd->edit->addInput('school','text');
$formAdd->edit->input->school->setTitle('School');

/* MAJOR */
$formAdd->edit->addInput('major','text');
$formAdd->edit->input->major->setTitle('Major');

/* DATES */
$formAdd->edit->addInput('start_date','date');
$formAdd->edit->input->start_date->setTitle('Start Date');

$formAdd->edit->addInput('end_date','date');
$formAdd->edit->input->end_date->setTitle('End Date');

echo '<pre>'.print_r($_POST, true).'</pre>';

$formAdd->edit->action();
$tabs['Add Intern'] = $formAdd->edit->getForm();

/* ===========================
   LIST TABLE
   =========================== */
$formList = _lib('pea', 'interns');
$formList->initRoll($add_sql.' ORDER BY id DESC', 'id');

$formList->roll->addInput('id','sqlplaintext');
$formList->roll->input->id->setDisplayColumn(false);

/* USER DISPLAY */
$formList->roll->addInput('bbc_user_id','selecttable');
$formList->roll->input->bbc_user_id->setTitle('User');
$formList->roll->input->bbc_user_id->setReferenceTable('bbc_user');

/*
  TETAP:
  VALUE = id
  DISPLAY = username
*/
$formList->roll->input->bbc_user_id->setReferenceField('id','username');

/* NAME */
$formList->roll->addInput('name','text');
$formList->roll->input->name->setTitle('Name');

/* SCHOOL */
$formList->roll->addInput('school','text');
$formList->roll->input->school->setTitle('School');

/* MAJOR */
$formList->roll->addInput('major','text');
$formList->roll->input->major->setTitle('Major');

/* DATES */
$formList->roll->addInput('start_date','text');
$formList->roll->input->start_date->setTitle('Start Date');

$formList->roll->addInput('end_date','text');
$formList->roll->input->end_date->setTitle('End Date');

$formList->roll->action();
$formList->roll->onDelete(true);

$tabs['Interns'] = $formList->roll->getForm();

echo tabs($tabs, 1, 'tabs_interns');
