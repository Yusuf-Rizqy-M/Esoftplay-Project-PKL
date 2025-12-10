<?php if (!defined('_VALID_BBC')) exit('No direct script access allowed');

$db = $GLOBALS['db'];

/* =====================================================
   AMANKAN ID
   ===================================================== */
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

/* =====================================================
   FORM EDIT
   ===================================================== */
$formAdd = _lib('pea', 'interns_tasks');
$formAdd->initEdit($id > 0 ? "WHERE id=$id" : "");

$formAdd->edit->addInput('header','header');
$formAdd->edit->input->header->setTitle('Add / Edit Task');

/* TITLE */
$formAdd->edit->addInput('title','text');
$formAdd->edit->input->title->setTitle('Title');
$formAdd->edit->input->title->setRequire();

/* DESCRIPTION */
$formAdd->edit->addInput('description','textarea');
$formAdd->edit->input->description->setTitle('Description');

/* =====================================================
   AMBIL DATA LAMA SEBELUM UPDATE
   ===================================================== */
$old = ($id > 0) ? $db->getRow("SELECT * FROM interns_tasks_list WHERE id=$id") : [];

/* =====================================================
   PROSES UPDATE
   ===================================================== */
$formAdd->edit->action();

/* TAMPILKAN FORM JIKA ID VALID */
if ($id > 0) {
    echo $formAdd->edit->getForm();
}

/* =====================================================
   AMBIL DATA BARU SETELAH UPDATE
   ===================================================== */
$new = ($id > 0) ? $db->getRow("SELECT * FROM interns_tasks_list WHERE id=$id") : [];

/* Jika salah satu kosong, jangan buat history */
if (empty($old) || empty($new)) {
    return;
}

/* =====================================================
   DETEKSI PERUBAHAN FIELD
   ===================================================== */
$changes = [];

foreach ($new as $key => $val) {
    if ($old[$key] != $val) {
        $changes[] = strtoupper($key)." berubah dari '".$old[$key]."' menjadi '".$val."'";
    }
}

/* =====================================================
   SIMPAN HISTORY JIKA ADA PERUBAHAN
   ===================================================== */
if (!empty($changes)) {
    $report_text = implode("; ", $changes);

    $sql = "
        INSERT INTO interns_tasks_list_history 
            (interns_id, interns_tasks_id, report, created)
        VALUES 
            (
                {$new['interns_id']},
                {$new['interns_tasks_id']},
                ".$db->quote($report_text).",
                NOW()
            )
    ";

    $db->Execute($sql);
}
