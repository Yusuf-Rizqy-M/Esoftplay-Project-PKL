<?php if (!defined('_VALID_BBC')) exit('No direct script access allowed');

/* ===========================
   SEARCH
   =========================== */
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
  'Tasks' => '',
  'Add Task' => ''
);

/* ===========================
   FORM ADD / EDIT
   =========================== */
$formAdd = _lib('pea', 'interns_tasks');
$formAdd->initEdit();

$formAdd->edit->addInput('header','header');
$formAdd->edit->input->header->setTitle('Add / Edit Task');

/* INTERN PICKER */
$formAdd->edit->addInput('interns_id','selecttable');
$formAdd->edit->input->interns_id->setTitle('Intern');
$formAdd->edit->input->interns_id->setModal();
$formAdd->edit->input->interns_id->setReferenceTable('interns');
$formAdd->edit->input->interns_id->setReferenceField('name', 'id');
$formAdd->edit->input->interns_id->setRequire();

/* TASK MASTER PICKER */
$formAdd->edit->addInput('interns_tasks_master_id','selecttable');
$formAdd->edit->input->interns_tasks_master_id->setTitle('Task Master');
$formAdd->edit->input->interns_tasks_master_id->setModal();
$formAdd->edit->input->interns_tasks_master_id->setReferenceTable('interns_tasks_master');
$formAdd->edit->input->interns_tasks_master_id->setReferenceField('title','id');
$formAdd->edit->input->interns_tasks_master_id->setRequire();

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

/* HIDDEN ID */
$formList->roll->addInput('id','sqlplaintext');
$formList->roll->input->id->setDisplayColumn(false);

/* INTERN COLUMN */
$formList->roll->addInput('interns_id','selecttable');
$formList->roll->input->interns_id->setTitle('Intern');
$formList->roll->input->interns_id->setReferenceTable('interns');
$formList->roll->input->interns_id->setReferenceField('name','id');

/* TASK MASTER COLUMN (perbaikan kolom) */
$formList->roll->addInput('interns_tasks_master_id','selecttable');
$formList->roll->input->interns_tasks_master_id->setTitle('Master Task');
$formList->roll->input->interns_tasks_master_id->setReferenceTable('interns_tasks_master');
$formList->roll->input->interns_tasks_master_id->setReferenceField('title','id');

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

$formList->roll->action();
$formList->roll->onDelete(true);

$tabs['Tasks'] = $formList->roll->getForm();

/* SHOW TABS */
echo tabs($tabs, 1, 'tabs_interns_tasks');
