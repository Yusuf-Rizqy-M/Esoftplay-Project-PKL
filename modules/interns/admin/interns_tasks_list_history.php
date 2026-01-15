<?php if (!defined('_VALID_BBC')) exit('No direct script access allowed');

$form = _lib('pea', 'interns_tasks_list_history');

/* SEARCH */
$form->initSearch();

$form->search->addInput('interns_id', 'selecttable');
$form->search->input->interns_id->setTitle(lang('Name')); // Mengganti 'Type' menjadi 'Name' agar lebih relevan
$form->search->input->interns_id->addOption(lang('---- Filter by Name ----'), '');
$form->search->input->interns_id->setReferenceTable('interns');
$form->search->input->interns_id->setReferenceField('name', 'id');

$add_sql = $form->search->action();
echo '<div style="margin-bottom: 20px;">'; // Gap antara Search dan List
echo $form->search->getForm();
echo '</div>';

/* LIST HISTORY */
$form->initRoll($add_sql . ' ORDER BY created DESC, id DESC', 'id');

/* No Save/Delete */
$form->roll->setDeleteTool(false);
$form->roll->setSaveTool(false);

/* ID (Hidden) */
$form->roll->addInput('id', 'sqlplaintext');
$form->roll->input->id->setDisplayColumn(false);

/* KOLOM 1: INTERN NAME */
$form->roll->addInput('interns_id','selecttable');
$form->roll->input->interns_id->setTitle('Intern');
$form->roll->input->interns_id->setPlaintext(true);
$form->roll->input->interns_id->setReferenceTable('interns');
$form->roll->input->interns_id->setReferenceField('name','id');

/* KOLOM 2: TASK NAME */
$form->roll->addInput('interns_tasks_list_id', 'selecttable');
$form->roll->input->interns_tasks_list_id->setTitle('Task');
$form->roll->input->interns_tasks_list_id->setPlaintext(true);
// Kita melakukan JOIN antara interns_tasks_list (l) dan interns_tasks (t)
$form->roll->input->interns_tasks_list_id->setReferenceTable('interns_tasks_list AS l LEFT JOIN interns_tasks AS t ON (l.interns_tasks_id=t.id)');
// Kita ambil field 'title' dari alias tabel 't'
$form->roll->input->interns_tasks_list_id->setReferenceField('t.title', 'l.id');

/* KOLOM 3: NOTES (Perbaikan di sini: gunakan nama input unik agar tidak menimpa Task) */
$form->roll->addInput('task_notes', 'selecttable'); 
$form->roll->input->task_notes->setTitle('Notes');
$form->roll->input->task_notes->setFieldName('interns_tasks_list_id'); // Merujuk ke field ID yang sama di DB
$form->roll->input->task_notes->setPlaintext(true);
$form->roll->input->task_notes->setReferenceTable('interns_tasks_list');
$form->roll->input->task_notes->setReferenceField('notes','id');

/* KOLOM 4: STATUS */
$form->roll->addInput('status', 'sqlplaintext');
$form->roll->input->status->setTitle('Status');
$form->roll->input->status->setDisplayFunction(function ($value) {
    switch ($value) {
        case 1:
            return '<span class="label" style="background-color: #6c757d; color: white; padding: 5px 10px; border-radius: 4px;">To Do</span>';
        case 2:
            return '<span class="label" style="background-color: #007bff; color: white; padding: 5px 10px; border-radius: 4px;">In Progress</span>';
        case 3:
            return '<span class="label" style="background-color: #ffc107; color: black; padding: 5px 10px; border-radius: 4px;">Submit</span>';
        case 4:
            return '<span class="label" style="background-color: #fd7e14; color: white; padding: 5px 10px; border-radius: 4px;">Revised</span>';
        case 5:
            return '<span class="label" style="background-color: #28a745; color: white; padding: 5px 10px; border-radius: 4px;">Done</span>';
        case 6:
            return '<span class="label" style="background-color: #dc3545; color: white; padding: 5px 10px; border-radius: 4px;">Cancel</span>';
        default:
            return '<span class="label label-default">Unknown</span>';
    }
});

/* KOLOM 5: CREATED */
$form->roll->addInput('created', 'sqlplaintext');
$form->roll->input->created->setTitle('Changed At');
$form->roll->input->created->setDateFormat('d M Y, H:i');

/* OUTPUT */
echo $form->roll->getForm();