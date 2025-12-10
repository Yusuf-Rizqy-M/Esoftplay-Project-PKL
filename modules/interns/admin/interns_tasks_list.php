<?php if (!defined('_VALID_BBC')) exit('No direct script access allowed');

$formSearch = _lib('pea', 'interns_tasks_list');
$formSearch->initSearch();

$formSearch->search->addInput('keyword', 'keyword');
$formSearch->search->input->keyword->addSearchField('notes', false);

$add_sql = $formSearch->search->action();
echo $formSearch->search->getForm();


$tabs = array(
  'Tasks'    => '',
  'Add Task' => ''
);

include 'interns_tasks_list_edit.php';
$tabs['Add Task'] = $formAdd->edit->getForm();


$formList = _lib('pea', 'interns_tasks_list');
$formList->initRoll($add_sql.' ORDER BY id DESC', 'id');

/* DISABLE DELETE & SAVE */
$formList->roll->setDeleteTool(false);
$formList->roll->setSaveTool(true);

// id
$formList->roll->addInput('id','sqlplaintext');
$formList->roll->input->id->setDisplayColumn(false);

$formList->roll->addInput('interns_id','selecttable');
$formList->roll->input->interns_id->setTitle('Intern');
$formList->roll->input->interns_id->setPlaintext(true);
$formList->roll->input->interns_id->setReferenceTable('interns');
$formList->roll->input->interns_id->setReferenceField('name','id');

$formList->roll->addInput('interns_tasks_id','selecttable','sqlplaintext');
$formList->roll->input->interns_tasks_id->setTitle('Task');
$formList->roll->input->interns_tasks_id->setPlaintext(true);
$formList->roll->input->interns_tasks_id->setReferenceTable('interns_tasks');
$formList->roll->input->interns_tasks_id->setReferenceField('title','id');


// notes
$formList->roll->addInput('notes','sqlplaintext');
$formList->roll->input->notes->setTitle('Notes');

// status
$formList->roll->addInput('status','select');
$formList->roll->input->status->setTitle('Status');
$formList->roll->input->status->addOption('To Do', 1);
$formList->roll->input->status->addOption('In Progress', 2);
$formList->roll->input->status->addOption('Submit', 3);
$formList->roll->input->status->addOption('Revised', 4);
$formList->roll->input->status->addOption('Done', 5);
$formList->roll->input->status->addOption('Cancel', 6);

// created
$formList->roll->addInput('created','sqlplaintext');
$formList->roll->input->created->setTitle('Created');

// updated
$formList->roll->addInput('updated','sqlplaintext');
$formList->roll->input->updated->setTitle('Updated');

$formList->roll->action();

$tabs['Tasks'] = $formList->roll->getForm();

// show tabs
echo tabs($tabs, 1, 'tabs_interns_tasks_list');
