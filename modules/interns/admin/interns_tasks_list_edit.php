<?php
if (!defined('_VALID_BBC')) exit('No direct script access allowed');
?>
<style>
/* Hilangkan button Edit di header modal */
.modal-header .btn:not(.close),
.modal-header button[type="button"]:not(.close) {
    display: none !important;
}
</style>
<?php
$db = $GLOBALS['db'];
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    $old = $db->getRow("SELECT * FROM interns_tasks_list WHERE id={$id}");
    $intern_name = $db->getOne("SELECT name FROM interns WHERE id={$old['interns_id']}");
    $task_title = $db->getOne("SELECT title FROM interns_tasks WHERE id={$old['interns_tasks_id']}");
}

$formAdd = _lib('pea', 'interns_tasks_list');
$formAdd->initEdit($id > 0 ? "WHERE id={$id}" : "");

// ========== TAMBAHKAN HEADER HANYA UNTUK EDIT MODE (ID > 0) ==========
if ($id > 0) {
    $formAdd->edit->addInput('header','header');
    $formAdd->edit->input->header->setTitle('Edit Daftar Tugas Intern');
}

if ($id > 0) {
    // EDIT MODE: Show plaintext
    
    // ✅ 1. TASK (PERTAMA)
    $formAdd->edit->addInput('task_title','plaintext');
    $formAdd->edit->input->task_title->setTitle('Task');
    $formAdd->edit->input->task_title->setValue($task_title);
    
    $formAdd->edit->addInput('interns_tasks_id','hidden');
    
    // ✅ 2. INTERN (KEDUA)
    $formAdd->edit->addInput('intern_name','plaintext');
    $formAdd->edit->input->intern_name->setTitle('Intern');
    $formAdd->edit->input->intern_name->setValue($intern_name);
    
    $formAdd->edit->addInput('interns_id','hidden');
} else {
    // ADD MODE: Autocomplete untuk TASK dan INTERN
    
    // ✅ 1. TASK (PERTAMA) - DENGAN AUTOCOMPLETE
    $formAdd->edit->addInput('interns_tasks_id','selecttable');
    $formAdd->edit->input->interns_tasks_id->setTitle('Task');
    $formAdd->edit->input->interns_tasks_id->setReferenceTable('interns_tasks');
    $formAdd->edit->input->interns_tasks_id->setReferenceField('title','id');
    $formAdd->edit->input->interns_tasks_id->setAutoComplete(true);
    $formAdd->edit->input->interns_tasks_id->setRequire();
    
    // ✅ 2. INTERN (KEDUA) - DENGAN AUTOCOMPLETE
    $formAdd->edit->addInput('interns_id','selecttable');
    $formAdd->edit->input->interns_id->setTitle('Intern');
    $formAdd->edit->input->interns_id->setReferenceTable('interns');
    $formAdd->edit->input->interns_id->setReferenceField('name','id');
    $formAdd->edit->input->interns_id->setAutoComplete(true);
    $formAdd->edit->input->interns_id->setRequire();
}

$formAdd->edit->addInput('notes','textarea');
$formAdd->edit->input->notes->setTitle('Notes');

$formAdd->edit->addInput('status','select');
$formAdd->edit->input->status->setTitle('Status');
$formAdd->edit->input->status->addOption('To Do', 1);
$formAdd->edit->input->status->addOption('In Progress', 2);
$formAdd->edit->input->status->addOption('Submit', 3);
$formAdd->edit->input->status->addOption('Revised', 4);
$formAdd->edit->input->status->addOption('Done', 5);
$formAdd->edit->input->status->addOption('Cancel', 6);
$formAdd->edit->input->status->setRequire();

// ========== PANGGIL action() DULU SEBELUM HANDLE POST ==========
$formAdd->edit->action();

// ========== HANDLE POST SUBMIT - SETELAH action() ==========
if (!empty($_POST) && !empty($_POST['edit_submit_update'])) {
    // ✅ AMBIL ID BARU SETELAH INSERT (Insert_ID() harus dipanggil SETELAH action())
    $new_id = $db->Insert_ID();
    
    if ($id == 0 && $new_id > 0) {
        // ========== ADD MODE ==========
        $new = $db->getRow("SELECT * FROM interns_tasks_list WHERE id={$new_id}");
        
        if (!empty($new)) {
            // Insert ke history
            $db->Execute("INSERT INTO interns_tasks_list_history (interns_id, interns_tasks_list_id, status, created) VALUES ({$new['interns_id']}, {$new_id}, {$new['status']}, NOW())");
            $db->Execute("UPDATE interns_tasks_list SET updated = NOW() WHERE id = {$new_id}");
            
            // ========== SET SESSION UNTUK NOTIFIKASI ==========
            $task_title = $db->getOne("SELECT title FROM interns_tasks WHERE id = {$new['interns_tasks_id']}");
            $intern_name = $db->getOne("SELECT name FROM interns WHERE id = {$new['interns_id']}");
            
            $_SESSION['tasklist_add_success'] = [
                'task' => $task_title,
                'intern' => $intern_name
            ];
        }
        
        // Redirect ke halaman utama
        $redirect_url = 'index.php?mod=interns.interns_tasks_list';
        header("Location: {$redirect_url}");
        exit;
        
    } elseif ($id > 0) {
        // ========== EDIT MODE ==========
        $new = $db->getRow("SELECT * FROM interns_tasks_list WHERE id={$id}");
        if (!empty($new)) {
            $db->Execute("INSERT INTO interns_tasks_list_history (interns_id, interns_tasks_list_id, status, created) VALUES ({$new['interns_id']}, {$id}, {$new['status']}, NOW())");
            $db->Execute("UPDATE interns_tasks_list SET updated = NOW() WHERE id = {$id}");
        }
        
        // Redirect ke halaman utama
        $redirect_url = 'index.php?mod=interns.interns_tasks_list';
        header("Location: {$redirect_url}");
        exit;
    }
}

// Hanya tampilkan form untuk EDIT mode (dipanggil via modal)
if ($id > 0) {
    echo $formAdd->edit->getForm();
}
?>

<?php if (!empty($_GET['is_ajax'])): ?>
<script>
_Bbc($ => {
    // ========== HILANGKAN BUTTON "EDIT" DI HEADER MODAL ==========
    setTimeout(() => {
        // Cari modal yang sedang aktif
        const activeModal = parent.$('.modal.in');
        if (activeModal.length) {
            // Hapus semua button di modal header (kecuali tombol close [X])
            activeModal.find('.modal-header .btn, .modal-header button[type="button"]:not(.close)').remove();
        }
    }, 100);
    
    const editFormURL = new URL(<?php echo json_encode(seo_url()) ?>);
    const editForm = $('form[name="edit"]');
    editFormURL.searchParams.delete('is_ajax');
    editFormURL.searchParams.delete('return');
    
    editForm.on('submit', e => {
        e.preventDefault();
        let data = editForm.serialize() + '&edit_submit_update=SAVE';
        
        $.ajax({
            type: "POST",
            url: editFormURL.toString(),
            data: data,
            success: function(data) {
                location.reload();
            }
        });
    });
});
</script>
<?php endif; ?>