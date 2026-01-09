<?php
if (!defined('_VALID_BBC')) exit('No direct script access allowed');

$db = $GLOBALS['db'];

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$formAdd = _lib('pea', 'interns_tasks');
$formAdd->initEdit($id > 0 ? "WHERE id=$id" : "");

$header_title = ($id > 0) ? 'Edit Data Intern' : 'Add New Intern';
$formAdd->edit->addInput('header','header');
$formAdd->edit->input->header->setTitle($header_title);

$formAdd->edit->addInput('title','text');
$formAdd->edit->input->title->setTitle('Title');
$formAdd->edit->input->title->setRequire();

$formAdd->edit->addInput('description','textarea');
$formAdd->edit->input->description->setTitle('Description');

$formAdd->edit->action();

if ($id == 0 && !empty($_POST['title'])) {
    $new_id = $db->Insert_ID();
    if ($new_id > 0) {
        $db->Execute("UPDATE interns_tasks_list SET updated = NULL WHERE id = $new_id");
    }
    $redirect_url = $_SERVER['PHP_SELF'] . (isset($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : '');
    header("Location: $redirect_url");
    exit;
}

if ($id > 0) {
    echo $formAdd->edit->getForm();
}

$old = ($id > 0) ? $db->getRow("SELECT * FROM interns_tasks_list WHERE id=$id") : [];
$new = ($id > 0) ? $db->getRow("SELECT * FROM interns_tasks_list WHERE id=$id") : [];

if (empty($old) || empty($new)) {
    return;
}

$changes = [];
foreach ($new as $key => $val) {
    if ($old[$key] != $val) {
        $changes[] = strtoupper($key)." berubah dari '".$old[$key]."' menjadi '".$val."'";
    }
}

if (!empty($changes)) {
    $report_text = implode("; ", $changes);
    $sql = "
        INSERT INTO interns_tasks_list_history
            (interns_id, interns_tasks_id, report, created)
        VALUES
            (
                {$new['interns_id']},
                {$new['id']},
                ".$db->quote($report_text).",
                NOW()
            )
    ";
    $db->Execute($sql);
}