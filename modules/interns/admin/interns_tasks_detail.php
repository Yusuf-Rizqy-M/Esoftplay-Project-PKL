<?php
if (!defined('_VALID_BBC')) exit('No direct script access allowed');
if (!empty($_GET['act']) && $_GET['act'] == 'sample_tasklist') {
  header('Content-Type: text/csv; charset=utf-8');
  header('Content-Disposition: attachment;filename="sample_import_tasklist.csv"');
  header('Cache-Control: no-cache, no-store, must-revalidate');
  header('Pragma: no-cache');
  header('Expires: 0');
  echo "email_intern,task_title,notes\n";
  echo "yusufhabib290@gmail.com,Install Linux,kerjakan dalam 1 minggu\n";
  echo "jojo@gmail.com,Setup Development Environment,install VSCode dan Git\n";
  die();
}
if (!empty($_GET['id'])) {
  $id = $_GET['id'];
} else {
  $id = null;
}

$user = $db->getRow('select * from interns where user_id = ' . $id);
$formList = _lib('pea', 'interns_tasks_list');
$formList->initRoll('WHERE interns_id = ' . $user['id'] . ' ORDER BY id DESC', 'id');
$formList->roll->setDeleteTool(false);
$formList->roll->setSaveTool(false);
$formList->roll->addInput('id', 'sqlplaintext');
$formList->roll->input->id->setDisplayColumn(false);
$formList->roll->addInput('interns_id', 'selecttable');
$formList->roll->input->interns_id->setTitle('Intern Name');
$formList->roll->input->interns_id->setPlaintext(true);
$formList->roll->input->interns_id->setReferenceTable('interns');
$formList->roll->input->interns_id->setReferenceField('name', 'id');
$formList->roll->addInput('interns_tasks_id', 'selecttable');
$formList->roll->input->interns_tasks_id->setTitle('Task');
$formList->roll->input->interns_tasks_id->setPlaintext(true);
$formList->roll->input->interns_tasks_id->setReferenceTable('interns_tasks');
$formList->roll->input->interns_tasks_id->setReferenceField('title', 'id');
$formList->roll->addInput('notes', 'sqllinks');
$formList->roll->input->notes->setLinks($Bbc->mod['circuit'] . '.interns_tasks_list_edit');
$formList->roll->input->notes->setModal(true);
$formList->roll->addInput('status', 'sqlplaintext');
$formList->roll->input->status->setTitle('Status');
$formList->roll->input->status->setDisplayFunction(function ($value) {
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
$formList->roll->addInput('created', 'sqlplaintext');
$formList->roll->input->created->setTitle('Created');
$formList->roll->addInput('updated', 'sqlplaintext');
$formList->roll->input->updated->setTitle('Updated');
$formList->roll->action();
if (!empty($_POST['roll_submit_update'])) {
  if (!empty($_POST['roll_status']) && is_array($_POST['roll_status']) && !empty($_POST['roll_id']) && is_array($_POST['roll_id'])) {
    foreach ($_POST['roll_id'] as $index => $id) {
      $id = (int)$id;
      $new_status = (int)($_POST['roll_status'][$index] ?? 1);
      $interns_id = $db->getOne("SELECT interns_id FROM interns_tasks_list WHERE id = {$id}");
      $interns_id = $interns_id ? $interns_id : 1;
      $db->Execute("INSERT INTO interns_tasks_list_history (interns_id, interns_tasks_list_id, status, created) VALUES ({$interns_id}, {$id}, {$new_status}, NOW())");
    }
  }
}
$output = $formList->roll->getForm();
echo $output;
