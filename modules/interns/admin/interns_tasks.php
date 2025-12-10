<?php if (!defined('_VALID_BBC')) exit('No direct script access allowed');

$formSearch = _lib('pea', 'interns_tasks');
$formSearch->initSearch();

$formSearch->search->addInput('keyword','keyword');
$formSearch->search->input->keyword->addSearchField('title', true);
$formSearch->search->input->keyword->addSearchField('description');

$add_sql = $formSearch->search->action();
echo $formSearch->search->getForm();

/* ===========================
   TABS
   =========================== */
$tabs = array(
  'Tasks ' => '',
  'Add Task' => ''
);

/* ===========================
   FORM ADD / EDIT
   =========================== */
$formAdd = _lib('pea', 'interns_tasks');
$formAdd->initEdit();

$formAdd->edit->addInput('header','header');
$formAdd->edit->input->header->setTitle('Add / Edit Task ');

/* TITLE */
$formAdd->edit->addInput('title','text');
$formAdd->edit->input->title->setTitle('Title');
$formAdd->edit->input->title->setRequire();

/* DESCRIPTION */
$formAdd->edit->addInput('description','textarea');
$formAdd->edit->input->description->setTitle('Description');

$formAdd->edit->action();
$tabs['Add Task'] = $formAdd->edit->getForm();

/* ===========================
   LIST TABLE
   =========================== */
$formList = _lib('pea', 'interns_tasks');
$formList->initRoll($add_sql.' ORDER BY id DESC', 'id');

/* ID */
$formList->roll->addInput('id','sqlplaintext');
$formList->roll->input->id->setDisplayColumn(false);

/* TITLE */
$formList->roll->addInput('title','text');
$formList->roll->input->title->setTitle('Title');

/* DESCRIPTION */
$formList->roll->addInput('description','text');
$formList->roll->input->description->setTitle('Description');

/* CREATED */
$formList->roll->addInput('created','sqlplaintext');
$formList->roll->input->created->setTitle('Created');

/* UPDATED */
$formList->roll->addInput('updated','sqlplaintext');
$formList->roll->input->updated->setTitle('Updated');

/* ACTIONS */
$formList->roll->action();
$formList->roll->onDelete(true);

$tabs['Tasks'] = $formList->roll->getForm();

echo tabs($tabs, 1, 'tabs_tasks');
