<?php if (!defined('_VALID_BBC')) exit('No direct script access allowed');

$formSearch = _lib('pea', 'interns_tasks');
$formSearch->initSearch();

$formSearch->search->addInput('keyword', 'keyword');
$formSearch->search->input->keyword->addSearchField('title', true);

$add_sql = $formSearch->search->action();
echo $formSearch->search->getForm();

$tabs = array('Tasks' => '', 'Add Task' => '');


// ===============================
// FORM ADD TASK
// ===============================
$formAdd = _lib('pea', 'interns_tasks');
$formAdd->initEdit();

$formAdd->edit->addInput('header', 'header');
$formAdd->edit->input->header->setTitle('Add New Task');

// Intern picker
$formAdd->edit->addInput('interns_id', 'selecttable');
$formAdd->edit->input->interns_id->setTitle('Intern');
$formAdd->edit->input->interns_id->setModal();
$formAdd->edit->input->interns_id->setReferenceTable('interns');
$formAdd->edit->input->interns_id->setReferenceField('id','name');
$formAdd->edit->input->interns_id->setRequire();

// Title
$formAdd->edit->addInput('title','text');
$formAdd->edit->input->title->setTitle('Task Title');
$formAdd->edit->input->title->setRequire();

// Description
$formAdd->edit->addInput('description','textarea');
$formAdd->edit->input->description->setTitle('Description');

$formAdd->edit->addInput('created','hidden');
$formAdd->edit->input->created->setExtra(date('Y-m-d H:i:s'));

$formAdd->edit->addInput('updated','hidden');
$formAdd->edit->input->updated->setExtra(date('Y-m-d H:i:s'));




$formAdd->edit->action();
$tabs['Add Task'] = $formAdd->edit->getForm();


// ===============================
// TABLE LIST
// ===============================
$formList = _lib('pea', 'interns_tasks');
$formList->initRoll($add_sql.' ORDER BY id DESC', 'id');

// Hide ID
$formList->roll->addInput('id','sqlplaintext');
$formList->roll->input->id->setDisplayColumn(false);

// Intern name
$formList->roll->addInput('interns_id','selecttable');
$formList->roll->input->interns_id->setTitle('Intern');
$formList->roll->input->interns_id->setReferenceTable('interns');
$formList->roll->input->interns_id->setReferenceField('id','name');

// Title
$formList->roll->addInput('title','text');
$formList->roll->input->title->setTitle('Task Title');

// Description
$formList->roll->addInput('description','textarea');
$formList->roll->input->description->setTitle('Description');

// Created
$formList->roll->addInput('created','sqlplaintext');
$formList->roll->input->created->setTitle('Created');

// Updated
$formList->roll->addInput('updated','sqlplaintext');
$formList->roll->input->updated->setTitle('Updated');

// Actions
$formList->roll->action();
$formList->roll->onDelete(true);

$tabs['Tasks'] = $formList->roll->getForm();

echo tabs($tabs, 1, 'tabs_tasks');
