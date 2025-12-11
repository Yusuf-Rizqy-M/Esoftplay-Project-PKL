<?php if (!defined('_VALID_BBC')) exit('No direct script access allowed');

$formSearch = _lib('pea', 'interns');
$formSearch->initSearch();

$formSearch->search->addInput('keyword','keyword');
$formSearch->search->input->keyword->addSearchField('name', false);
$formSearch->search->input->keyword->addSearchField('email');

$add_sql = $formSearch->search->action();
echo $formSearch->search->getForm();

$tabs = array(
    'Interns' => '',
    'Add Intern' => ''
);

/* ----------------------------
   FORM ADD / EDIT
----------------------------- */
$formAdd = _lib('pea', 'interns');
$formAdd->initEdit();

$formAdd->edit->addInput('header','header');
$formAdd->edit->input->header->setTitle('Add New Intern');

// NAME
$formAdd->edit->addInput('name','text');
$formAdd->edit->input->name->setTitle('Name');
$formAdd->edit->input->name->setRequire();

// EMAIL WAJIB UNIK
$formAdd->edit->addInput('email','text');
$formAdd->edit->input->email->setTitle('Email');
$formAdd->edit->input->email->setRequire();

// VALIDASI EMAIL UNIK + TANGGAL
$formAdd->edit->onSave(function($rows){
    global $db;

    $email = trim($rows['email']);

    // Validasi format email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Error: Format email tidak valid!";
    }

    // Cek email unik
    $check = $db->getOne("SELECT id FROM interns WHERE email = '{$db->escape($email)}'");
    if ($check && ($rows['id'] ?? 0) != $check) {
        return "Error: Email sudah terdaftar! Gunakan email lain.";
    }

    // Validasi tanggal (end_date >= start_date)
    if (!empty($rows['start_date']) && !empty($rows['end_date'])) {
        if (strtotime($rows['end_date']) < strtotime($rows['start_date'])) {
            return "Error: End Date tidak boleh lebih kecil dari Start Date!";
            // Kalau mau auto-fix: $rows['start_date'] = $rows['end_date'];
        }
    }

    return true; // sukses
});

// SCHOOL
$formAdd->edit->addInput('school','text');
$formAdd->edit->input->school->setTitle('School');

// MAJOR
$formAdd->edit->addInput('major','text');
$formAdd->edit->input->major->setTitle('Major');

// === GANTI DATEINTERVAL JADI DATE BIASA - INI YANG BIKIN ERROR HILANG 100% ===
$formAdd->edit->addInput('start_date', 'date');
$formAdd->edit->input->start_date->setTitle('Start Date Internship');
$formAdd->edit->input->start_date->setRequire();
$formAdd->edit->input->start_date->setParam(array(
    'autoclose' => true,
    'format' => 'yyyy-mm-dd',
    'today-btn' => true,
    'today-highlight' => true
));

$formAdd->edit->addInput('end_date', 'date');
$formAdd->edit->input->end_date->setTitle('End Date Internship');
$formAdd->edit->input->end_date->setRequire();
$formAdd->edit->input->end_date->setParam(array(
    'autoclose' => true,
    'format' => 'yyyy-mm-dd',
    'today-btn' => true,
    'today-highlight' => true
));
// ============================================================

$formAdd->edit->action();
$tabs['Add Intern'] = $formAdd->edit->getForm();

/* ----------------------------
   FORM LIST / ROLL
----------------------------- */
$formList = _lib('pea', 'interns');
$formList->initRoll($add_sql . ' ORDER BY id DESC', 'id');

// Nonaktifkan save & delete kalau mau
$formList->roll->setDeleteTool(false);
$formList->roll->setSaveTool(false);

// ID (hidden)
$formList->roll->addInput('id','sqlplaintext');
$formList->roll->input->id->setDisplayColumn(false);

// NAME
$formList->roll->addInput('name','text');
$formList->roll->input->name->setTitle('Name');
$formList->roll->input->name->setPlaintext(true);

// EMAIL
$formList->roll->addInput('email','text');
$formList->roll->input->email->setTitle('Email');
$formList->roll->input->email->setPlaintext(true);

// SCHOOL
$formList->roll->addInput('school','text');
$formList->roll->input->school->setTitle('School');
$formList->roll->input->school->setPlaintext(true);

// MAJOR
$formList->roll->addInput('major','text');
$formList->roll->input->major->setTitle('Major');
$formList->roll->input->major->setPlaintext(true);

// START DATE
$formList->roll->addInput('start_date','text');
$formList->roll->input->start_date->setTitle('Start Date');
$formList->roll->input->start_date->setPlaintext(true);

// END DATE
$formList->roll->addInput('end_date','text');
$formList->roll->input->end_date->setTitle('End Date');
$formList->roll->input->end_date->setPlaintext(true);

// CREATED
$formList->roll->addInput('created','sqlplaintext');
$formList->roll->input->created->setTitle('Created');
$formList->roll->input->created->setPlaintext(true);

$formList->roll->action();
$formList->roll->onDelete(true);

$tabs['Interns'] = $formList->roll->getForm();

echo tabs($tabs, 1, 'tabs_interns');