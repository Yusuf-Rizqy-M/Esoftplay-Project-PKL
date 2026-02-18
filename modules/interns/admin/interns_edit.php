<?php
if (!defined('_VALID_BBC')) exit('No direct script access allowed');

$id = @intval($_GET['id'] ?? $_GET['interns_id']);

$form_add = _lib('pea', 'interns');
$form_add->initEdit($id > 0 ? "WHERE `id`=$id" : "");

if ($id > 0) {
  $form_add->edit->formEditLinks = array(
    'Profil Intern'      => $Bbc->mod['circuit'] . '.interns_edit&id=' . $id,
    'Tambah Pengerjaan' => $Bbc->mod['circuit'] . '.interns_tasks_list&interns_id=' . $id
  );
}

$form_add->edit->setSuccessSaveMessage = ($id > 0) ? 'Berhasil update data intern!' : 'Berhasil menambah intern baru!';
$form_add->edit->addInput('header', 'header');
$form_add->edit->input->header->setTitle($id > 0 ? 'Edit Intern' : 'Add Intern');
$form_add->edit->addInput('name', 'text');
$form_add->edit->input->name->setTitle('Name');
$form_add->edit->input->name->setRequire();

if ($id > 0) {
  $form_add->edit->addInput('email', 'sqlplaintext');
} else {
  $form_add->edit->addInput('email', 'text');
  $form_add->edit->input->email->setRequire();
}

$form_add->edit->input->email->setTitle('Email');
$form_add->edit->addInput('phone', 'text');

$form_add->edit->addInput('school_id', 'selecttable');
$form_add->edit->input->school_id->setTitle('School');
$form_add->edit->input->school_id->setReferenceTable('interns_school');
$form_add->edit->input->school_id->setReferenceField('school_name', 'id');
$form_add->edit->input->school_id->setAllowNew(true);
$form_add->edit->input->school_id->addTip('Ketik nama sekolah atau gunakan fitur autocomplete jika data sudah tersedia. Klik <a href="' . $Bbc->mod['circuit'] . '.interns_edit_autocomplete&id=' . $id . '">disini</a> untuk menggunakan mode pencarian.');

$form_add->edit->addInput('major', 'text');
$form_add->edit->addInput('start_date', 'dateinterval');
$form_add->edit->input->start_date->setTitle('Start Date');
$form_add->edit->input->start_date->setEndDateField('end_date');

$form_add->edit->onSave('intern_logic_save', array(), false);
$form_add->edit->action();

echo '<div class="panel panel-default"><div class="panel-body">' . $form_add->edit->getForm() . '</div></div>';

function intern_logic_save($intern_id)
{
  global $form_add;
  global $db;
  global $id;

  $is_edit = $db->getOne("SELECT 1 FROM `interns` WHERE `id`=" . intval($id));
  $prefix  = $is_edit ? 'edit_' : 'add_';

  $email = isset($_POST[$prefix . 'email']) ? $_POST[$prefix . 'email'] : '';
  $phone = isset($_POST[$prefix . 'phone']) ? $_POST[$prefix . 'phone'] : '';

  if (!empty($email) && !is_email($email)) {
    $form_add->edit->setFailSaveMessage('Email tidak valid bro!');
    $form_add->edit->error = true;
    return false;
  }
  if (!empty($phone) && !is_phone($phone)) {
    $form_add->edit->setFailSaveMessage('Phone Minimal 5 Digit Bro');
    $form_add->edit->error = true;
    return false;
  }
  $start = isset($_POST[$prefix . 'start_date']) ? $_POST[$prefix . 'start_date'] : '';
  $end   = isset($_POST[$prefix . 'end_date']) ? $_POST[$prefix . 'end_date'] : '';

  if (!empty($start) && !empty($end)) {
    $status = (date('Y-m-d') < $start) ? 3 : ((date('Y-m-d') <= $end) ? 1 : 2);
    $form_add->edit->addExtraField('status', $status);
  }

  if (!$is_edit) {
    $name = isset($_POST['add_name']) ? $_POST['add_name'] : '';
    $user_id = $db->getOne("SELECT `id` FROM `bbc_user` WHERE `username`='" . addslashes($email) . "'");

    if (!$user_id) {
      $user_id = user_create([
        'username'  => $email,
        'name'      => $name,
        'email'     => $email,
        'password'  => 'intern123',
        'group_ids' => array(3),
        'params'    => ['_padding' => 1],
      ]);
    }
    
    if ($user_id) {
      $form_add->edit->addExtraField('user_id', $user_id);
    }
  }
  return true;
}
