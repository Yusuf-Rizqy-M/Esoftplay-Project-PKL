<?php if (!defined('_VALID_BBC')) exit('No direct script access allowed');

$form = _lib('pea', 'interns_tasks_list_history');

/* SEARCH */
$form->initSearch();

$form->search->addInput('interns_id', 'selecttable');
$form->search->input->interns_id->setTitle(lang('Type'));
$form->search->input->interns_id->addOption(lang('---- Filter by Name ----'), '');
$form->search->input->interns_id->setReferenceTable('interns');
$form->search->input->interns_id->setReferenceField('name', 'id');

$add_sql = $form->search->action();
echo '<div style="margin-bottom: 20px;">'; // Memberikan jarak bawah 20px
echo $form->search->getForm();
echo '</div>';

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
$form->roll->input->status->setDisplayFunction(function ($value) {
    switch ($value) {
        case 1:
            return '<span class="label label-default" style="background-color: #6c757d; color: white; padding: 5px 10px; border-radius: 4px;">To Do</span>';
        case 2:
            return '<span class="label label-primary" style="background-color: #007bff; color: white; padding: 5px 10px; border-radius: 4px;">In Progress</span>';
        case 3:
            return '<span class="label label-warning" style="background-color: #ffc107; color: black; padding: 5px 10px; border-radius: 4px;">Submit</span>';
        case 4:
            return '<span class="label label-warning" style="background-color: #fd7e14; color: white; padding: 5px 10px; border-radius: 4px;">Revised</span>';
        case 5:
            return '<span class="label label-success" style="background-color: #28a745; color: white; padding: 5px 10px; border-radius: 4px;">Done</span>';
        case 6:
            return '<span class="label label-danger" style="background-color: #dc3545; color: white; padding: 5px 10px; border-radius: 4px;">Cancel</span>';
        default:
            return '<span class="label label-default">Unknown</span>';
    }
});

/* CREATED */
$form->roll->addInput('created', 'sqlplaintext');
$form->roll->input->created->setTitle('Changed At');
$form->roll->input->created->setDateFormat('d M Y, H:i');
/* OUTPUT */
echo $form->roll->getForm();