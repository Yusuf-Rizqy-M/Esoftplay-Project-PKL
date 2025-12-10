<?php if (!defined('_VALID_BBC')) exit('No direct script access allowed');

$formSearch = _lib('pea', 'interns');
$formSearch->initSearch();

$formSearch->search->addInput('keyword','keyword');
$formSearch->search->input->keyword->addSearchField('name', false);
$formSearch->search->input->keyword->addSearchField('email');

$add_sql = $formSearch->search->action();
echo $formSearch->search->getForm();


$tabs = array(
    'Interns'     => '',
    'Add Intern'  => ''
);

// form add
$formAdd = _lib('pea', 'interns');
$formAdd->initEdit();

$formAdd->edit->addInput('header','header');
$formAdd->edit->input->header->setTitle('Add New Intern');

// name
$formAdd->edit->addInput('name','text');
$formAdd->edit->input->name->setTitle('Name');
$formAdd->edit->input->name->setRequire();

// email wajib unik
$formAdd->edit->addInput('email','text');
$formAdd->edit->input->email->setTitle('Email');
$formAdd->edit->input->email->setRequire();

// custom validation cek email unik + auto fix tanggal
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

    // AUTO FIX: Jika end_date < start_date, start_date otomatis dibuat sama dengan end_date
    if (!empty($rows['start_date']) && !empty($rows['end_date'])) {
        $start = strtotime($rows['start_date']);
        $end   = strtotime($rows['end_date']);

        if ($end < $start) {
            $rows['start_date'] = $rows['end_date'];
        }
    }

    return $rows;
});

// school
$formAdd->edit->addInput('school','text');
$formAdd->edit->input->school->setTitle('School');

// major/jurusan
$formAdd->edit->addInput('major','text');
$formAdd->edit->input->major->setTitle('Major');

$formAdd->edit->addInput('start_date', 'dateinterval');
$formAdd->edit->input->start_date->setTitle('Start Date Internship');
$formAdd->edit->input->start_date->setParam(array(
    'autoclose'       => true,
    'format'          => 'yyyy-mm-dd',
    'today-btn'       => true,
    'today-highlight' => true
));

$formAdd->edit->input->start_date->setEndDateField('end date');

$formAdd->edit->action();
$tabs['Add Intern'] = $formAdd->edit->getForm();


$formList = _lib('pea', 'interns');
$formList->initRoll($add_sql . ' ORDER BY id DESC', 'id');

// save dan delete saya hapus
$formList->roll->setDeleteTool(false);
$formList->roll->setSaveTool(false);

// id
$formList->roll->addInput('id','sqlplaintext');
$formList->roll->input->id->setDisplayColumn(false);

// name
$formList->roll->addInput('name','text');
$formList->roll->input->name->setTitle('Name');
$formList->roll->input->name->setPlaintext(true);

// email
$formList->roll->addInput('email','text');
$formList->roll->input->email->setTitle('Email');
$formList->roll->input->email->setPlaintext(true);

// school
$formList->roll->addInput('school','text');
$formList->roll->input->school->setTitle('School');
$formList->roll->input->school->setPlaintext(true);

// major 
$formList->roll->addInput('major','text');
$formList->roll->input->major->setTitle('Major');
$formList->roll->input->major->setPlaintext(true);

// start date
$formList->roll->addInput('start_date','text');
$formList->roll->input->start_date->setTitle('Start Date');
$formList->roll->input->start_date->setPlaintext(true);

// end date
$formList->roll->addInput('end_date','text');
$formList->roll->input->end_date->setTitle('End Date');
$formList->roll->input->end_date->setPlaintext(true);

// created
$formList->roll->addInput('created','sqlplaintext');
$formList->roll->input->created->setTitle('Created');
$formList->roll->input->created->setPlaintext(true);

// delete
$formList->roll->action();
$formList->roll->onDelete(true);

$tabs['Interns'] = $formList->roll->getForm();

// show tabs
echo tabs($tabs, 1, 'tabs_interns');
