<?php if (!defined('_VALID_BBC')) exit('No direct script access allowed');

/* ===========================
   SEARCH
   =========================== */
$formSearch = _lib('pea', 'interns_tasks_list');
$formSearch->initSearch();

$formSearch->search->addInput('keyword','keyword');
$formSearch->search->input->keyword->addSearchField('notes', true);

$add_sql = $formSearch->search->action();
echo $formSearch->search->getForm();

/* ===========================
   TABS
   =========================== */
$tabs = array(
  'Tasks'     => '',
  'Add Task'  => ''
);

/* ===========================
   FORM ADD / EDIT
   =========================== */
$formAdd = _lib('pea', 'interns_tasks_list');
$formAdd->initEdit();

$formAdd->edit->addInput('header','header');
$formAdd->edit->input->header->setTitle('Add / Edit Intern Task');

/* INTERN PICKER */
$formAdd->edit->addInput('interns_id','selecttable');
$formAdd->edit->input->interns_id->setTitle('Intern');
$formAdd->edit->input->interns_id->setModal();
$formAdd->edit->input->interns_id->setReferenceTable('interns');
$formAdd->edit->input->interns_id->setReferenceField('name', 'id');
$formAdd->edit->input->interns_id->setRequire();

/* TASK MASTER PICKER */
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
$tabs['Add Task'] = $formAdd->edit->getForm();

/* ===========================
   LIST TABLE
   =========================== */
$formList = _lib('pea', 'interns_tasks_list');
$formList->initRoll($add_sql.' ORDER BY id DESC', 'id');

/* HIDDEN ID */
$formList->roll->addInput('id','sqlplaintext');
$formList->roll->input->id->setDisplayColumn(false);

/* INTERN COLUMN */
$formList->roll->addInput('interns_id','selecttable');
$formList->roll->input->interns_id->setTitle('Intern');
$formList->roll->input->interns_id->setReferenceTable('interns');
$formList->roll->input->interns_id->setReferenceField('name','id');

/* TASK MASTER COLUMN */
$formList->roll->addInput('interns_tasks_id','selecttable');
$formList->roll->input->interns_tasks_id->setTitle('Task');
$formList->roll->input->interns_tasks_id->setReferenceTable('interns_tasks');
$formList->roll->input->interns_tasks_id->setReferenceField('title','id');

/* NOTES */
$formList->roll->addInput('notes','sqlplaintext');
$formList->roll->input->notes->setTitle('Notes');

/* STATUS (READABLE) */
$formList->roll->addInput('status','select');
$formList->roll->input->status->setTitle('Status');
$formList->roll->input->status->addOption('To Do', 1);
$formList->roll->input->status->addOption('In Progress', 2);
$formList->roll->input->status->addOption('Submit', 3);
$formList->roll->input->status->addOption('Revised', 4);
$formList->roll->input->status->addOption('Done', 5);
$formList->roll->input->status->addOption('Cancel', 6);

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
echo tabs($tabs, 1, 'tabs_interns_tasks_list');
