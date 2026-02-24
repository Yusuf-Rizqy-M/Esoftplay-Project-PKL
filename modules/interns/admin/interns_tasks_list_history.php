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

// 1. TANGKAP PARAMETER DARI URL (SAMA SEPERTI DI TASK LIST)
$tasks_list_id = isset($_GET['tasks_list_id']) ? intval($_GET['tasks_list_id']) : 0;
$intern_id     = isset($_GET['interns_id']) ? intval($_GET['interns_id']) : 0;

if ($tasks_list_id > 0) {
	$add_sql .= " AND `interns_tasks_list_id` = {$tasks_list_id}";
} elseif ($intern_id > 0) {
	$add_sql .= " AND `interns_id` = {$intern_id}";
}

$form->initRoll($add_sql . ' ORDER BY created DESC, id DESC', 'id');
$form->roll->setDeleteTool(false);
$form->roll->setSaveTool(false);

// INPUT COLUMNS
$form->roll->addInput('id', 'sqlplaintext');
$form->roll->input->id->setDisplayColumn(false);

$form->roll->addInput('interns_id', 'selecttable');
$form->roll->input->interns_id->setTitle('Name');
$form->roll->input->interns_id->setPlaintext(true);
$form->roll->input->interns_id->setReferenceTable('interns');
$form->roll->input->interns_id->setReferenceField('name', 'id');

$form->roll->addInput('email', 'selecttable');
$form->roll->input->email->setTitle('Email');
$form->roll->input->email->setReferenceTable('interns');
$form->roll->input->email->setReferenceField('email', 'id');
$form->roll->input->email->setPlaintext(true);
$form->roll->input->email->setFieldName('interns_id AS email'); 

$form->roll->addInput('interns_tasks_list_id', 'sqlplaintext');
$form->roll->input->interns_tasks_list_id->setTitle('Tasks');
$form->roll->input->interns_tasks_list_id->setDisplayFunction(function($list_id) {
    global $db;
    $sql = "SELECT `title` FROM `interns_tasks` 
            WHERE `id` = (SELECT `interns_tasks_id` FROM `interns_tasks_list` WHERE `id` = ".intval($list_id).")";
    $title = $db->getOne($sql);
    return $title ? $title : '-';
});

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
$form->roll->input->created->setTitle('Changed At');
$form->roll->input->created->setDateFormat('d M Y, H:i');
$form->roll->input->created->setDisplayColumn(false);

$form->roll->action();

if ($tasks_list_id > 0 || $intern_id > 0) {
	echo '<div class="panel panel-default">';
	echo '<div class="panel-heading">';
	
	if ($tasks_list_id > 0) {
		$info = $db->getRow("SELECT i.name, t.title FROM interns_tasks_list l 
							 LEFT JOIN interns i ON l.interns_id=i.id 
							 LEFT JOIN interns_tasks t ON l.interns_tasks_id=t.id 
							 WHERE l.id={$tasks_list_id}");
		echo icon('fa-history').' History: ' . $info['name'] . ' - ' . $info['title'];
	} else {
		$name = $db->getOne("SELECT name FROM interns WHERE id={$intern_id}");
		echo icon('fa-user').' History User: ' . $name;
	}
	
	echo '</div>';
	echo '<div class="panel-body">';
	echo '<div style="margin-bottom: 15px;">';
	echo '<a href="javascript:history.back()" class="btn btn-default btn-sm">'.icon('fa-chevron-left').' Kembali</a>';
	echo '</div>';
	
	echo $form->roll->getForm();
	echo '</div></div>';
} else {
	echo '<div style="margin-bottom: 20px;">';
	echo $form->search->getForm();
	echo '</div>';
	
	echo '<div class="panel panel-default">';
	echo '<div class="panel-heading"><h3 class="panel-title">Daftar Semua History Tugas</h3></div>';
	echo '<div class="panel-body">' . $form->roll->getForm() . '</div>';
	echo '</div>';
	
	echo '<div style="margin-bottom: 15px;">';
	echo '<a href="'.$Bbc->mod['circuit'].'.interns_tasks_list" class="btn btn-default btn-sm">'.icon('fa-chevron-left').' Kembali ke Task List</a>';
	echo '</div>';
}