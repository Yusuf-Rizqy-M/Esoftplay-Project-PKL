<?php
if (!defined('_VALID_BBC')) exit('No direct script access allowed');

$db = $GLOBALS['db'];

$interns   = $db->getRow('SELECT id FROM interns WHERE user_id = ' . $user->id);
$intern_id = intval($interns['id']);

$tasks_list_id = isset($_GET['tasks_list_id']) ? intval($_GET['tasks_list_id']) : 0;

$form = _lib('pea', 'interns_tasks_list_history');

$add_sql = "WHERE `interns_id` = {$intern_id}";
if ($tasks_list_id > 0) {
	$add_sql .= " AND `interns_tasks_list_id` = {$tasks_list_id}";
}

$form->initRoll($add_sql . ' ORDER BY created DESC, id DESC', 'id');

$header_title = 'History Aktivitas Tugas';
if ($tasks_list_id > 0) {
	$task_title = $db->getOne("SELECT t.title FROM interns_tasks_list l 
							   LEFT JOIN interns_tasks t ON l.interns_tasks_id=t.id 
							   WHERE l.id={$tasks_list_id}");
	if ($task_title) $header_title = 'History: ' . $task_title;
}

$form->roll->addInput('header', 'header');
$form->roll->input->header->setTitle($header_title);

$form->roll->addInput('interns_tasks_list_id', 'sqlplaintext');
$form->roll->input->interns_tasks_list_id->setTitle('Tasks Title');
$form->roll->input->interns_tasks_list_id->setDisplayFunction(function ($list_id) {
	global $db;
	$sql = "SELECT `title` FROM `interns_tasks` 
			WHERE `id` = (SELECT `interns_tasks_id` FROM `interns_tasks_list` WHERE `id` = " . intval($list_id) . ")";
	$title = $db->getOne($sql);
	return $title ? $title : '-';
});

$form->roll->addInput('notes', 'sqlplaintext');
$form->roll->input->notes->setTitle('Notes');

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
$form->roll->input->created->setTitle('Waktu Perubahan');
$form->roll->input->created->setDateFormat('d M Y, H:i');

$form->roll->setDeleteTool(false);
$form->roll->setSaveTool(false);

$form->roll->action();

echo '<div class="panel panel-default">';
echo '  <div class="panel-body">';
echo $form->roll->getForm();
echo '  </div>';
echo '</div>';