<?php
if (!defined('_VALID_BBC')) exit('No direct script access allowed');

$db_obj       = $GLOBALS['db'];
$task_list_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Pastikan yang mengakses adalah pemilik task tersebut (Security Check)
$interns   = $db_obj->getRow('SELECT id FROM interns WHERE user_id = ' . $user->id);
$intern_id = intval($interns['id']);

$check_owner = $db_obj->getOne("SELECT id FROM interns_tasks_list WHERE id={$task_list_id} AND interns_id={$intern_id}");
if (!$check_owner) {
    echo '<div class="alert alert-danger">Maaf, Anda tidak memiliki akses untuk mengubah tugas ini.</div>';
    return;
}

$form_add = _lib('pea', 'interns_tasks_list');
$form_add->initEdit("WHERE `id`={$task_list_id}");

$current_data = $db_obj->getRow("SELECT l.*, t.timeline 
                                FROM `interns_tasks_list` AS l
                                LEFT JOIN `interns_tasks` AS t ON l.interns_tasks_id = t.id
                                WHERE l.`id` = {$task_list_id}");

$form_add->edit->addInput('header', 'header');
$form_add->edit->input->header->setTitle('Update Progress Tugas');

$form_add->edit->addInput('status', 'select');
$form_add->edit->input->status->setTitle('Status');

$status_aktif = intval(@$current_data['status']);
$options      = [];

// Logika Filter Status (Hanya In Progress ke Submit, dsb)
$status_labels = [1=>'To Do', 2=>'In Progress', 3=>'Submit', 4=>'Revised', 5=>'Done', 6=>'Cancel'];
if ($status_aktif > 0 && isset($status_labels[$status_aktif])) {
    $options[$status_labels[$status_aktif]] = $status_aktif;
}

switch ($status_aktif) {
    case 1: // To Do
    case 4: // Revised
        $options['In Progress'] = 2;
        break;
    case 2: // In Progress
        $options['Submit']      = 3;
        break;
    case 3: // Submit (User menunggu review admin)
        break;
    default:
        break;
}

foreach ($options as $label => $val) {
    $form_add->edit->input->status->addOption($label, $val);
}

$form_add->edit->addInput('notes', 'textarea');
$form_add->edit->input->notes->setTitle('Notes / Keterangan');

$form_add->edit->action();

// Handle Simpan & History
if (!empty($_POST) && !empty($_POST['edit_submit_update'])) {
    if ($task_list_id > 0) {
        $notes_safe = addslashes($_POST['edit_notes']?? '');
        $new_status = intval($_POST['edit_status']?? '');
        
        // Simpan ke History
        $db_obj->Execute("INSERT INTO `interns_tasks_list_history` 
            (`interns_id`, `interns_tasks_list_id`, `status`, `notes`, `created`) 
            VALUES 
            ({$intern_id}, {$task_list_id}, {$new_status}, '{$notes_safe}', NOW())");
        
        $update_fields = ["`updated` = NOW()", "`status` = {$new_status}", "`notes` = '{$notes_safe}'"];

        if ($new_status == 2) { // In Progress
            if (empty($current_data['started']) || $current_data['started'] == '0000-00-00 00:00:00') {
                $update_fields[] = "`started` = NOW()";
                $timeline        = intval($current_data['timeline']);
                $update_fields[] = "`deadline` = DATE_ADD(NOW(), INTERVAL {$timeline} DAY)";
            }
        }

        $db_obj->Execute("UPDATE `interns_tasks_list` SET " . implode(', ', $update_fields) . " WHERE `id` = {$task_list_id}");
        header('Location:' . _URL. 'interns/task_pkl');
        $db_obj ->Execute('commit');
        exit;
    }
}

// Tampilkan History Notes di atas Form agar user bisa baca alasan Revised dari admin
$histories = $db_obj->getAll("SELECT * FROM `interns_tasks_list_history` WHERE `interns_tasks_list_id` = {$task_list_id} ORDER BY `created` DESC");
if (!empty($histories)) {
    echo '<div class="panel panel-default" style="margin-bottom: 20px;">';
    echo '<div class="panel-heading"><b>Riwayat Catatan</b></div>';
    echo '<div class="table-responsive"><table class="table table-bordered">';
    echo '<thead><tr><th width="150">Tanggal</th><th width="120">Status</th><th>Notes</th></tr></thead><tbody>';
    foreach($histories as $hist) {
        $stat_label = isset($status_labels[$hist['status']]) ? $status_labels[$hist['status']] : 'Unknown';
        echo '<tr><td>'.date('d M Y H:i', strtotime($hist['created'])).'</td><td>'.$stat_label.'</td><td>'.nl2br(htmlentities($hist['notes'])).'</td></tr>';
    }
    echo '</tbody></table></div></div>';
}

echo $form_add->edit->getForm();