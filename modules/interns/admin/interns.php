<?php if (!defined('_VALID_BBC')) exit('No direct script access allowed');

/* ===========================
   SEARCH
   =========================== */
$formSearch = _lib('pea', 'interns');
$formSearch->initSearch();

$formSearch->search->addInput('keyword','keyword');
$formSearch->search->input->keyword->addSearchField('name', true);
$formSearch->search->input->keyword->addSearchField('email');

$add_sql = $formSearch->search->action();
echo $formSearch->search->getForm();

/* ===========================
   TABS
   =========================== */
$tabs = array(
    'Interns'     => '',
    'Add Intern'  => ''
);

/* ===========================
   FORM ADD INTERN (PLAIN TEXT + EMAIL UNIK)
   =========================== */
$formAdd = _lib('pea', 'interns');
$formAdd->initEdit();

$formAdd->edit->addInput('header','header');
$formAdd->edit->input->header->setTitle('Add New Intern');

/* NAME */
$formAdd->edit->addInput('name','text');
$formAdd->edit->input->name->setTitle('Name');
$formAdd->edit->input->name->setRequire();

/* EMAIL - WAJIB & UNIK */
$formAdd->edit->addInput('email','text');
$formAdd->edit->input->email->setTitle('Email');
$formAdd->edit->input->email->setRequire();

// Custom validation: cek email unik + valid format
$formAdd->edit->onSave(function($rows) {
    global $db;
    
    $email = trim($rows['email']);
    
    // Cek format email sederhana
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Error: Format email tidak valid!";
    }
    
    // Cek apakah email sudah ada
    $check = $db->getOne("SELECT id FROM interns WHERE email = '{$db->escape($email)}'");
    if ($check && ($rows['id'] ?? 0) != $check) {
        return "Error: Email sudah terdaftar! Gunakan email lain.";
    }
    
    // Cek end_date >= start_date
    if (!empty($rows['start_date']) && !empty($rows['end_date'])) {
        if (strtotime($rows['end_date']) < strtotime($rows['start_date'])) {
            return "Error: End Date tidak boleh lebih kecil dari Start Date!";
        }
    }
    
    return true;
});

/* SCHOOL */
$formAdd->edit->addInput('school','text');
$formAdd->edit->input->school->setTitle('School');

/* MAJOR */
$formAdd->edit->addInput('major','text');
$formAdd->edit->input->major->setTitle('Major');

/* START DATE */
$formAdd->edit->addInput('start_date','date');
$formAdd->edit->input->start_date->setTitle('Start Date');
$formAdd->edit->input->start_date->setRequire();

/* END DATE */
$formAdd->edit->addInput('end_date','date');
$formAdd->edit->input->end_date->setTitle('End Date');
$formAdd->edit->input->end_date->setRequire();

$formAdd->edit->action();
$tabs['Add Intern'] = $formAdd->edit->getForm();

/* ===========================
   LIST TABLE - PLAIN TEXT SEMUA, GAK BISA DIEDIT
   =========================== */
$formList = _lib('pea', 'interns');
$formList->initRoll($add_sql . ' ORDER BY id DESC', 'id');

// OBAT SAKTI ANTI CACHE ERROR
$formList->roll->resetField();
$formList->roll->setSavePanel(false);

// ID (hidden)
$formList->roll->addInput('id','sqlplaintext');
$formList->roll->input->id->setDisplayColumn(false);

/* NAME - PLAIN TEXT */
$formList->roll->addInput('name','text');
$formList->roll->input->name->setTitle('Name');
$formList->roll->input->name->setPlaintext(true);  // gak bisa diedit

/* EMAIL - PLAIN TEXT */
$formList->roll->addInput('email','text');
$formList->roll->input->email->setTitle('Email');
$formList->roll->input->email->setPlaintext(true);

/* SCHOOL - PLAIN TEXT */
$formList->roll->addInput('school','text');
$formList->roll->input->school->setTitle('School');
$formList->roll->input->school->setPlaintext(true);

/* MAJOR - PLAIN TEXT */
$formList->roll->addInput('major','text');
$formList->roll->input->major->setTitle('Major');
$formList->roll->input->major->setPlaintext(true);

/* START DATE - PLAIN TEXT */
$formList->roll->addInput('start_date','text');
$formList->roll->input->start_date->setTitle('Start Date');
$formList->roll->input->start_date->setPlaintext(true);

/* END DATE - PLAIN TEXT */
$formList->roll->addInput('end_date','text');
$formList->roll->input->end_date->setTitle('End Date');
$formList->roll->input->end_date->setPlaintext(true);

/* CREATED & UPDATED */
$formList->roll->addInput('created','sqlplaintext');
$formList->roll->input->created->setTitle('Created');
$formList->roll->input->created->setPlaintext(true);

$formList->roll->addInput('updated','sqlplaintext');
$formList->roll->input->updated->setTitle('Updated');
$formList->roll->input->updated->setPlaintext(true);

/* ACTION - CUMA DELETE DOANG, EDIT DIHAPUS */
$formList->roll->action();
$formList->roll->setDeleteTool(false);     // kalau mau hapus tombol delete juga
$formList->roll->onDelete(true);

$tabs['Interns'] = $formList->roll->getForm();

/* SHOW TABS */
echo tabs($tabs, 1, 'tabs_interns');