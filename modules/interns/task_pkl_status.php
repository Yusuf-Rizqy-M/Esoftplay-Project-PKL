<?php
if (!defined('_VALID_BBC')) exit('No direct script access allowed');

$db_obj       = $GLOBALS['db'];
$task_list_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// 1. Security Check: Ambil data Intern & Pastikan Owner
$interns   = $db_obj->getRow('SELECT id FROM interns WHERE user_id = ' . intval($user->id));
$intern_id = intval($interns['id']);

$current_data = $db_obj->getRow("SELECT l.*, t.timeline 
                                FROM `interns_tasks_list` AS l
                                LEFT JOIN `interns_tasks` AS t ON l.interns_tasks_id = t.id
                                WHERE l.`id` = {$task_list_id} AND l.`interns_id` = {$intern_id}");

if (!$current_data) {
    echo '<div class="alert alert-danger">Maaf, Anda tidak memiliki akses untuk mengubah tugas ini.</div>';
    return;
}

// 2. Inisialisasi PEA Edit
$form_add = _lib('pea', 'interns_tasks_list');
$form_add->initEdit("WHERE `id`={$task_list_id}");

$form_add->edit->addInput('header', 'header');
$form_add->edit->input->header->setTitle('Update Progress Tugas');

// Input Status
$form_add->edit->addInput('status', 'select');
$form_add->edit->input->status->setTitle('Status');

$status_aktif  = intval($current_data['status']);
$status_labels = [1=>'To Do', 2=>'In Progress', 3=>'Submit', 4=>'Revised', 5=>'Done', 6=>'Cancel'];

// Filter Logika Status (Hanya yang diizinkan yang muncul di dropdown)
$options = [];
if (isset($status_labels[$status_aktif])) $options[$status_labels[$status_aktif]] = $status_aktif;

switch ($status_aktif) {
    case 1: case 4: $options['In Progress'] = 2; break;
    case 2:         $options['Submit']      = 3; break;
}
foreach ($options as $label => $val) {
    $form_add->edit->input->status->addOption($label, $val);
}

// Input Notes
$form_add->edit->addInput('notes', 'textarea');
$form_add->edit->input->notes->setTitle('Notes / Keterangan');

// 3. LOGIKA SIMPAN & HISTORY (onSave adalah cara terbersih di PEA)
$form_add->edit->onSave(function($id) use ($db_obj, $intern_id, $current_data) {
    // Ambil data terbaru yang baru saja di-input user
    $new_status = intval($_POST['edit_status']);
    $notes_safe = addslashes($_POST['edit_notes']);
    
    // Simpan ke History
    $db_obj->Execute("INSERT INTO `interns_tasks_list_history` 
        (`interns_id`, `interns_tasks_list_id`, `status`, `notes`, `created`) 
        VALUES 
        ({$intern_id}, {$id}, {$new_status}, '{$notes_safe}', NOW())");
    
    // Logika tambahan untuk Started & Deadline (khusus saat masuk In Progress)
    if ($new_status == 2 && (empty($current_data['started']) || $current_data['started'] == '0000-00-00 00:00:00')) {
        $started  = date('Y-m-d H:i:s');
        $deadline = date('Y-m-d H:i:s', strtotime("+".intval($current_data['timeline'])." days"));
        $db_obj->Execute("UPDATE `interns_tasks_list` SET `started`='{$started}', `deadline`='{$deadline}' WHERE `id`={$id}");
    }

    // Update field updated
    $db_obj->Execute("UPDATE `interns_tasks_list` SET `updated` = NOW() WHERE `id` = {$id}");
});

// Eksekusi Form Action
$form_add->edit->action();

// Cek jika sudah sukses submit, lakukan redirect
if (!empty($_POST['edit_submit_update'])) {
    header('Location: ' . _URL . 'interns/task_pkl');
    exit;
}

// 4. Tampilkan History Notes di atas Form
$histories = $db_obj->getAll("SELECT * FROM `interns_tasks_list_history` WHERE `interns_tasks_list_id` = {$task_list_id} ORDER BY `created` DESC");
if (!empty($histories)) {
    ?>
    <div class="panel panel-default" style="margin-bottom: 20px;">
        <div class="panel-heading"><b>Riwayat Catatan</b></div>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th width="150">Tanggal</th>
                        <th width="120">Status</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach($histories as $hist): ?>
                    <tr>
                        <td><?php echo date('d M Y H:i', strtotime($hist['created'])); ?></td>
                        <td><?php echo $status_labels[$hist['status']] ?? 'Unknown'; ?></td>
                        <td><?php echo nl2br(htmlentities($hist['notes'])); ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php
}

// 5. Render Form PEA
echo $form_add->edit->getForm();
?>

<script> _Bbc($ => $('form[method="POST"]').prop('action', <?php echo json_encode(seo_uri()) ?>)) </script>
