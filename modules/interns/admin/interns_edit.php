<?php
if (!defined('_VALID_BBC')) exit('No direct script access allowed');

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$db = $GLOBALS['db'];

$formAdd = _lib('pea', 'interns');
$formAdd->initEdit($id > 0 ? "WHERE id=$id" : "");

$header_title = ($id > 0) ? 'Edit Data Intern' : 'Add New Intern';
$formAdd->edit->addInput('header','header');
$formAdd->edit->input->header->setTitle($header_title);

$formAdd->edit->addInput('name','text');
$formAdd->edit->input->name->setTitle('Name');
$formAdd->edit->input->name->setRequire();

$formAdd->edit->addInput('email','text');
$formAdd->edit->input->email->setTitle('Email');
$formAdd->edit->input->email->setRequire();

$formAdd->edit->addInput('no_hp','text');
$formAdd->edit->input->no_hp->setTitle('No HP');
$formAdd->edit->input->no_hp->setNumberFormat(true);
$formAdd->edit->input->no_hp->setExtra(' minlength="9" maxlength="14"');
$formAdd->edit->input->no_hp->setRequire();


$formAdd->edit->addInput('school','text');
$formAdd->edit->input->school->setTitle('School');

$formAdd->edit->addInput('major','text');
$formAdd->edit->input->major->setTitle('Major');

$formAdd->edit->addInput('start_date', 'dateinterval');
$formAdd->edit->input->start_date->setTitle('Internship Period');
$formAdd->edit->input->start_date->setCaption('Start Date');
$formAdd->edit->input->start_date->setEndDateField('end_date');
$formAdd->edit->input->start_date->setRequire();

$formAdd->edit->input->end_date->setTitle('End Date');
$formAdd->edit->input->end_date->setRequire();

// Callback sebelum dan sesudah simpan
$formAdd->edit->onSave('intern_edit_before_save', '', false);
$formAdd->edit->onSave('intern_edit_after_save', '', true);

$formAdd->edit->action();

// Tampilkan Form
echo '<div class="panel panel-default">';
echo '<div class="panel-body">';
echo $formAdd->edit->getForm();
echo '</div>';
echo '</div>';

// --- FUNCTIONS ---

function intern_edit_before_save($intern_id) {
    global $db;
    $email = trim($_POST['add_email'] ?? '');
    $name  = trim($_POST['add_name'] ?? '');
    $start = $_POST['add_start_date'] ?? '';
    $end   = $_POST['add_end_date'] ?? '';
    $curr_id = intval($_POST['add_id'] ?? 0);

    // Cek Tanggal
    if (!empty($start) && !empty($end)) {
        if (strtotime($end) < strtotime($start)) {
            return "Error: Tanggal Selesai tidak boleh mendahului Tanggal Mulai!";
        }
    }

    // Cek Email Duplikat (Kecuali record sendiri saat edit)
    $check_email = $db->getOne("SELECT id FROM interns WHERE email = '".addslashes($email)."' AND id != $curr_id");
    if ($check_email) return "Error: Email sudah digunakan oleh intern lain!";
    
    // Logika Akun User (Hanya jika data baru)
    if ($curr_id == 0) {
        $user_check = $db->getOne("SELECT id FROM bbc_user WHERE username = '".addslashes($email)."'");
        if ($user_check) {
            $_SESSION['intern_temp_user_id'] = $user_check;
        } else {
            _func('user');
            $params = array('username' => $email, 'name' => $name, 'email' => $email, 'params' => ['_padding' => 1]);
            $user_id = user_create($params);
            if (!$user_id) return "Error: Gagal membuat akun user!";
            $_SESSION['intern_temp_user_id'] = $user_id;
        }
    }
    return true;
}

function intern_edit_after_save($intern_id) {
    global $db;
    // Hubungkan user_id ke tabel interns
    if (!empty($_SESSION['intern_temp_user_id']) && !empty($intern_id)) {
        $db->Execute("UPDATE interns SET user_id = ".intval($_SESSION['intern_temp_user_id'])." WHERE id = ".intval($intern_id));
        unset($_SESSION['intern_temp_user_id']);
    }
    return true;
}