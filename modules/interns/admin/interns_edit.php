<?php
if (!defined('_VALID_BBC')) exit('No direct script access allowed');

$id = @intval($_GET['id']);

$p_action = isset($_POST['add_submit_add']) ? 'add_' : (isset($_POST['edit_submit_edit']) ? 'edit_' : '');

if ($p_action == 'add_') {
    $email = strtolower(trim($_POST['add_email']));
    $name  = $_POST['add_name'];

    $user_id = $db->getOne("SELECT id FROM bbc_user WHERE username='" . addslashes($email) . "'");
    if (!$user_id && is_email($email)) {
        $user_params = array(
            'username'  => $email,
            'name'      => $name,
            'email'     => $email,
            'password'  => 'intern123',
            'group_ids' => array(3),
            'params'    => array('register_at' => date('Y-m-d H:i:s'))
        );
        $user_id = user_create($user_params);
    }
    if ($user_id) {
        $_POST['add_user_id'] = $user_id;
    }
}

$form_add = _lib('pea', 'interns');
$form_add->initEdit($id > 0 ? "WHERE id=$id" : "");

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
$form_add->edit->addInput('school', 'selecttable');
$form_add->edit->input->school->setTitle('School');
$form_add->edit->input->school->setReferenceTable('interns');
$form_add->edit->input->school->setReferenceField('school', 'school');
$form_add->edit->input->school->setReferenceCondition('1 GROUP BY school');
$form_add->edit->input->school->setAutoComplete(true);
$form_add->edit->input->school->setAllowNew(true);

$form_add->edit->addInput('major', 'text');
$form_add->edit->addInput('start_date', 'dateinterval');
$form_add->edit->input->start_date->setTitle('Internship Period');
$form_add->edit->input->start_date->setEndDateField('end_date');

$form_add->edit->addInput('user_id', 'hidden');

$form_add->edit->onSave('intern_logic_save', array(), false);

$form_add->edit->action();
echo '<div class="panel panel-default"><div class="panel-body">' . $form_add->edit->getForm() . '</div></div>';

function intern_logic_save($intern_id)
{
    global $form_add;

    $p = isset($_POST['add_submit_add']) ? 'add_' : (isset($_POST['edit_submit_edit']) ? 'edit_' : '');
    if (empty($p)) {
        $p = ($intern_id > 0) ? 'edit_' : 'add_';
    }

    $start = isset($_POST[$p . 'start_date']) ? $_POST[$p . 'start_date'] : '';
    $end   = isset($_POST[$p . 'end_date']) ? $_POST[$p . 'end_date'] : '';
    $email = isset($_POST[$p . 'email']) ? $_POST[$p . 'email'] : '';
    $phone = isset($_POST[$p . 'phone']) ? $_POST[$p . 'phone'] : '';

    if (!empty($start) && !empty($end)) {
        if (strtotime($end) < strtotime($start)) {
            return "Tanggal Selesai harus setelah Tanggal Mulai!";
        }
        
        $status = (date('Y-m-d') < $start) ? 3 : ((date('Y-m-d') <= $end) ? 1 : 2);
        $form_add->edit->addExtraField('status', $status);
    }

    if (isset($_POST[$p . 'email']) && !is_email($email)) {
        $form_add->edit->setFailSaveMessage('Email Minimal 6 digit bro');
        $form_add->edit->error = true;
        return false;
    }
    if (isset($_POST[$p . 'phone']) && !is_phone($phone)) {
        $form_add->edit->setFailSaveMessage('Phone Minimal 5 Digit Bro');
        $form_add->edit->error = true;
        return false;
    }
    if ($intern_id == 0 && empty($_POST['add_user_id'])) {
        return user_create_validate_msg();
    }

    return true;
}
?>

