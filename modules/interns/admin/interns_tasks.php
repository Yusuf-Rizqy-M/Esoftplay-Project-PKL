<?php if (!defined('_VALID_BBC')) exit('No direct script access allowed');

$formSearch = _lib('pea', 'interns_tasks');
$formSearch->initSearch();

$formSearch->search->addInput('keyword','keyword');
$formSearch->search->input->keyword->addSearchField('title', false);
$formSearch->search->input->keyword->addSearchField('description', false);



$add_sql = $formSearch->search->action();
echo $formSearch->search->getForm();

$tabs = array(
  'Tasks'    => '', 
  'Add Task' => ''
);

include 'interns_tasks_edit.php';
$tabs['Add Task'] = $formAdd->edit->getForm();
$formList = _lib('pea', 'interns_tasks');
$formList->initRoll($add_sql.' ORDER BY id DESC', 'id');
$formList->roll->setSaveTool(false);
$formList->roll->setDeleteTool(false);
// id
$formList->roll->addInput('id','sqlplaintext');
$formList->roll->input->id->setDisplayColumn(false);

// title
$formList->roll->addInput('title','sqllinks');
$formList->roll->input->title->setLinks($Bbc->mod['circuit'].'.interns_tasks_edit');
$formList->roll->input->title->setTitle('Title');

// desc
$formList->roll->addInput('description','sqlplaintext');
$formList->roll->input->description->setTitle('Description');

// created
$formList->roll->addInput('created','sqlplaintext');
$formList->roll->input->created->setTitle('Created');

// updated
$formList->roll->addInput('updated','sqlplaintext');
$formList->roll->input->updated->setTitle('Updated');

// action
$formList->roll->action();
$formList->roll->onDelete(true); 

$tabs['Tasks'] = $formList->roll->getForm();

echo tabs($tabs, 1, 'tabs_tasks');