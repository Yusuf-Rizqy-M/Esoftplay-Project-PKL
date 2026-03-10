<?php
if (!defined('_VALID_BBC')) exit('No direct script access allowed');

$db = $GLOBALS['db'];

// Mengambil data intern berdasarkan user yang sedang login
$interns = $db->getRow('SELECT id FROM interns WHERE user_id = ' . $user->id);
$intern_id = intval($interns['id']);

$form = _lib('pea', 'interns_tasks_list');

// Inisialisasi list berdasarkan interns_id
$form->initRoll('WHERE interns_id = ' . $intern_id . ' ORDER BY id DESC', 'id');

// Menggunakan header native framework
$form->roll->addInput('header', 'header');
$form->roll->input->header->setTitle('Daftar List Tugas');

$form->roll->addInput('interns_tasks_id', 'selecttable');
$form->roll->input->interns_tasks_id->setTitle('Tasks Title');
$form->roll->input->interns_tasks_id->setReferenceTable('interns_tasks');
$form->roll->input->interns_tasks_id->setReferenceField('title', 'id');
$form->roll->input->interns_tasks_id->setPlaintext(true);

$form->roll->addInput('notes', 'sqlplaintext');
$form->roll->input->notes->setTitle('Notes');


$form->roll->addInput('timeline', 'selecttable');
$form->roll->input->timeline->setTitle('Timeline (Days)');
$form->roll->input->timeline->setReferenceTable('interns_tasks');
$form->roll->input->timeline->setReferenceField('timeline', 'id');
$form->roll->input->timeline->setPlaintext(true);
$form->roll->input->timeline->setFieldName('interns_tasks_id AS timeline');

$form->roll->addInput('status', 'sqllinks');
$form->roll->input->status->setModal(true);
$form->roll->input->status->setLinks($Bbc->mod['circuit'] . '.task_pkl_status');
$form->roll->input->status->setDisplayFunction(function ($v) {
  $colors = [1 => '#6c757d', 2 => '#007bff', 3 => '#ffc107', 4 => '#fd7e14', 5 => '#28a745', 6 => '#dc3545'];
  $labels = [1 => 'To Do', 2 => 'In Progress', 3 => 'Submit', 4 => 'Revised', 5 => 'Done', 6 => 'Cancel'];
  $tcolor = ($v == 3) ? 'black' : 'white';
  return '<span class="label" style="background-color:' . (@$colors[$v] ?: '#eee') . '; color:' . $tcolor . '; padding:5px 10px; border-radius:12px;">' . (@$labels[$v] ?: 'Unknown') . '</span>';
});

$form->roll->addInput('started', 'sqlplaintext');
$form->roll->input->started->setTitle('Started');
$form->roll->input->started->setDisplayFunction(function ($value) {
  return !empty($value) ? date('d M Y H:i', strtotime($value)) : '-';
});

$form->roll->addInput('deadline', 'sqlplaintext');
$form->roll->input->deadline->setTitle('Deadline');
$form->roll->input->deadline->setDisplayFunction(function ($value) {
  return !empty($value) ? date('d M Y H:i', strtotime($value)) : '-';
});

$form->roll->addInput('done_at', 'sqlplaintext');
$form->roll->input->done_at->setTitle('Done At');


$form->roll->addInput('created', 'sqlplaintext');

$form->roll->addInput('updated', 'sqlplaintext');

$form->roll->setDeleteTool(false);
$form->roll->setSaveTool(false);

$form->roll->action();

echo '<div class="panel panel-default">';
echo '  <div class="panel-body">';
echo $form->roll->getForm();
echo '  </div>';
echo '</div>';