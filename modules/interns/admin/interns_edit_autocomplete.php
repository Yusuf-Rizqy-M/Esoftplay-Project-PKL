<?php
if (!defined('_VALID_BBC')) exit('No direct script access allowed');

// Sertakan file interns_edit.php HANYA untuk mengambil fungsi intern_logic_save
// Kita gunakan output buffering agar tampilan dari file tersebut tidak muncul
ob_start();
include_once 'interns_edit.php';
ob_end_clean(); 

$id = @intval($_GET['id']);

$form_add = _lib('pea', 'interns');
$form_add->initEdit($id > 0 ? "WHERE `id`=$id" : "");
$form_add->edit->setSuccessSaveMessage = ($id > 0) ? 'Berhasil update data intern!' : 'Berhasil menambah intern baru!';

$form_add->edit->addInput('header', 'header');
$form_add->edit->input->header->setTitle($id > 0 ? 'Edit Intern (Autocomplete Mode)' : 'Add Intern (Autocomplete Mode)');

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
$form_add->edit->input->phone->setTitle('Phone');

$form_add->edit->addInput('school_id', 'selecttable');
$form_add->edit->input->school_id->setTitle('School');
$form_add->edit->input->school_id->setReferenceTable('interns_school');
$form_add->edit->input->school_id->setReferenceField('school_name', 'id');

// AKTIFKAN AUTOCOMPLETE SAJA
$form_add->edit->input->school_id->setAutoComplete(true);
$form_add->edit->input->school_id->setAllowNew(false);

// Link untuk kembali ke mode input manual (tambah sekolah baru)
$form_add->edit->input->school_id->addTip('Mode pencarian aktif. Jika sekolah tidak ditemukan, klik <a href="'.$Bbc->mod['circuit'].'.interns_edit&id='.$id.'">disini</a> untuk menambah sekolah baru.');

$form_add->edit->addInput('major', 'text');
$form_add->edit->input->major->setTitle('Major');

$form_add->edit->addInput('start_date', 'dateinterval');
$form_add->edit->input->start_date->setTitle('Start Date');
$form_add->edit->input->start_date->setEndDateField('end_date');

// Gunakan fungsi save yang sudah didefinisikan di interns_edit.php
$form_add->edit->onSave('intern_logic_save', array(), false);
$form_add->edit->action();

echo '<div class="panel panel-default"><div class="panel-body">' . $form_add->edit->getForm() . '</div></div>';