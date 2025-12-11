<?php if (!defined('_VALID_BBC')) exit('No direct script access allowed');

$form = _lib('pea', 'interns_tasks_list_history');

/* SEARCH */
$form->initSearch();
$form->search->addInput('keyword', 'keyword');
$form->search->input->keyword->addSearchField('status', true);
$add_sql = $form->search->action();
echo $form->search->getForm();

/* LIST HISTORY */
$form->initRoll($add_sql . ' ORDER BY created DESC, id DESC', 'id');

/* No Save/Delete */
$form->roll->setDeleteTool(false);
$form->roll->setSaveTool(false);

/* ID */
$form->roll->addInput('id', 'sqlplaintext');
$form->roll->input->id->setDisplayColumn(false);


// INTERN
$form->roll->addInput('interns_id','selecttable');
$form->roll->input->interns_id->setTitle('Intern');
$form->roll->input->interns_id->setPlaintext(true);
$form->roll->input->interns_id->setReferenceTable('interns');
$form->roll->input->interns_id->setReferenceField('name','id');

/* TASK (notes dari interns_tasks_list) */
$form->roll->addInput('interns_tasks_list_id', 'selecttable');
$form->roll->input->interns_tasks_list_id->setTitle('Task');
$form->roll->input->interns_tasks_list_id->setPlaintext(true);
$form->roll->input->interns_tasks_list_id->setReferenceTable('interns_tasks_list');
$form->roll->input->interns_tasks_list_id->setReferenceField('notes','id');

/* STATUS */
$form->roll->addInput('status', 'sqlplaintext');
$form->roll->input->status->setTitle('Status');

/* CREATED */
$form->roll->addInput('created', 'sqlplaintext');
$form->roll->input->created->setTitle('Changed At');

/* OUTPUT */
echo $form->roll->getForm();