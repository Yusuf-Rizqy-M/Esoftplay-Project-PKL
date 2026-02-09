<?php
if (!defined('_VALID_BBC')) exit('No direct script access allowed');

$db = $GLOBALS['db'];
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// $old = ($id > 0) ? $db->getRow("SELECT * FROM interns_tasks_list WHERE id=$id") : [];

$form_add = _lib('pea', 'interns_tasks');
$form_add->initEdit($id > 0 ? "WHERE id=$id" : "");

$form_add->edit->addInput('title', 'text');
$form_add->edit->input->title->setTitle('Title');
$form_add->edit->input->title->setRequire();

$form_add->edit->addInput('description', 'textarea');
$form_add->edit->input->description->setTitle('Description');

$form_add->edit->addInput('timeline', 'text');
$form_add->edit->input->timeline->setTitle('timeline');
$form_add->edit->input->timeline->setNumberFormat(true);
$form_add->edit->input->timeline->setRequire();

$form_add->edit->addInput('type', 'text');
$form_add->edit->input->type->setTitle('type');
$form_add->edit->input->type->setRequire();

// $form_add->edit->addInput('interns', 'multiform');
// $form_add->edit->input->interns->setTitle(lang('Add Tasks Interns'));
// $form_add->edit->input->interns->setReferenceTable('interns_tasks_list');
// $form_add->edit->input->interns->setReferenceField('interns_tasks_id', 'id');

// $form_add->edit->input->interns->addInput('interns_id', 'selecttable', 'Name');
// $form_add->edit->input->interns->addInput('notes', 'text', 'Notes');
// $form_add->edit->input->interns_id->setReferenceTable('interns');
// $form_add->edit->input->interns_id->setReferenceField('name', 'id');
// $form_add->edit->input->interns_id->addOption('-- Select Interns --', '');

$form_add->edit->action();

// if ($id == 0 && !empty($_POST['title'])) {
//   $new_id = $db->Insert_ID();
//   if ($new_id > 0) {
//     $db->Execute("UPDATE interns_tasks_list SET updated = NULL WHERE id = $new_id");
//   }
//   $redirect_url = $_SERVER['PHP_SELF'] . (isset($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : '');
//   header("Location: $redirect_url");
//   exit;
// }

echo '<div class="panel panel-default"><div class="panel-body">' . $form_add->edit->getForm() . '</div></div>';

// $new = ($id > 0) ? $db->getRow("SELECT * FROM interns_tasks_list WHERE id=$id") : [];

// if (!empty($old) && !empty($new)) {
//   $changes = [];
//   foreach ($new as $key => $val) {
//     if ($old[$key] != $val) {
//       $changes[] = strtoupper($key) . " berubah dari '" . $old[$key] . "' menjadi '" . $val . "'";
//     }
//   }

//   if (!empty($changes)) {
//     $report_text = implode("; ", $changes);
//     $sql = "
//           INSERT INTO interns_tasks_list_history
//               (interns_id, interns_tasks_id, report, created)
//           VALUES
//               (
//                   {$new['interns_id']},
//                   {$new['id']},
//                   " . $db->quote($report_text) . ",
//                   NOW()
//               )
//       ";
//     $db->Execute($sql);
//   }
// }