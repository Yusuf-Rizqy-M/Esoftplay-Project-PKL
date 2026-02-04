<?php 
if (!defined('_VALID_BBC')) exit('No direct script access allowed');

$form = _lib('pea', 'interns_tasks_list_history');

$form->initSearch();
$form->search->addInput('interns_id', 'selecttable');
$form->search->input->interns_id->setTitle('Search Name');
$form->search->input->interns_id->setReferenceTable('interns');
$form->search->input->interns_id->setReferenceField('name', 'id');
$form->search->input->interns_id->setAutoComplete(array(
	'minChars'       => 2,
	'matchContains'  => 1,
	'autoFill'       => 'false'
));
$form->search->input->interns_id->addOption('--- Type name to search ---', '');

$add_sql = $form->search->action();

echo '<div style="margin-bottom: 20px;">';
echo $form->search->getForm();
echo '</div>';

$form->initRoll($add_sql . ' ORDER BY created DESC, id DESC', 'id');
$form->roll->setDeleteTool(false);
$form->roll->setSaveTool(false);

$form->roll->addInput('id', 'sqlplaintext');
$form->roll->input->id->setDisplayColumn(false);

$form->roll->addInput('interns_id', 'selecttable');
$form->roll->input->interns_id->setTitle('Name');
$form->roll->input->interns_id->setPlaintext(true);
$form->roll->input->interns_id->setReferenceTable('interns');
$form->roll->input->interns_id->setReferenceField('name', 'id');

$form->roll->addInput('intern_email', 'sqlplaintext');
$form->roll->input->intern_email->setTitle('Email');
$form->roll->input->intern_email->setFieldName('(SELECT email FROM interns WHERE id = interns_id) as intern_email');

$form->roll->addInput('interns_tasks_list_id', 'selecttable');
$form->roll->input->interns_tasks_list_id->setTitle('Tasks');
$form->roll->input->interns_tasks_list_id->setPlaintext(true);
$form->roll->input->interns_tasks_list_id->setReferenceTable('interns_tasks_list AS l LEFT JOIN interns_tasks AS t ON (l.interns_tasks_id=t.id)');
$form->roll->input->interns_tasks_list_id->setReferenceField('t.title', 'l.id');

$form->roll->addInput('task_notes', 'selecttable');
$form->roll->input->task_notes->setTitle('Notes');
$form->roll->input->task_notes->setFieldName('interns_tasks_list_id');
$form->roll->input->task_notes->setPlaintext(true);
$form->roll->input->task_notes->setReferenceTable('interns_tasks_list');
$form->roll->input->task_notes->setReferenceField('notes', 'id');

$form->roll->addInput('status', 'sqlplaintext');
$form->roll->input->status->setTitle('Status');
$form->roll->input->status->setDisplayFunction(function ($value) {
  $colors = [
    1 => ['bg' => '#6c757d', 'text' => 'white', 'label' => 'To Do'],
    2 => ['bg' => '#007bff', 'text' => 'white', 'label' => 'In Progress'],
    3 => ['bg' => '#ffc107', 'text' => 'black', 'label' => 'Submit'],
    4 => ['bg' => '#fd7e14', 'text' => 'white', 'label' => 'Revised'],
    5 => ['bg' => '#28a745', 'text' => 'white', 'label' => 'Done'],
    6 => ['bg' => '#dc3545', 'text' => 'white', 'label' => 'Cancel']
  ];
  $status = $colors[$value] ?? ['bg' => '#6c757d', 'text' => 'white', 'label' => 'Unknown'];
  return '<span class="label" style="background-color: ' . $status['bg'] . '; color: ' . $status['text'] . '; padding: 5px 10px; border-radius: 12px;">' . $status['label'] . '</span>';
});

$form->roll->addInput('created', 'sqlplaintext');
$form->roll->input->created->setTitle('Created');
$form->roll->input->created->setDateFormat('d M Y, H:i');

$output = $form->roll->getForm();

echo '<div class="panel panel-default">';
echo '<div class="panel-heading"><h3 class="panel-title">Daftar Tugas List History</h3></div>';
echo '<div class="panel-body">' . $output . '</div>';
echo '</div>';