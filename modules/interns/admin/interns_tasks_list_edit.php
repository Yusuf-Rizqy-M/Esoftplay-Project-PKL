<?php
if (!defined('_VALID_BBC')) exit('No direct script access allowed');
$db = $GLOBALS['db'];
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    $old = $db->getRow("SELECT * FROM interns_tasks_list WHERE id={$id}");
    $intern_name = $db->getOne("SELECT name FROM interns WHERE id={$old['interns_id']}");
    $task_title = $db->getOne("SELECT title FROM interns_tasks WHERE id={$old['interns_tasks_id']}");
}

$formAdd = _lib('pea', 'interns_tasks_list');
$formAdd->initEdit($id > 0 ? "WHERE id={$id}" : "");

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
    $formAdd->edit->input->interns_tasks_id->setAutoComplete(true); // ✨ Autocomplete aktif
    $formAdd->edit->input->interns_tasks_id->setRequire();
    
    // ✅ 2. INTERN (KEDUA) - DENGAN AUTOCOMPLETE
    $formAdd->edit->addInput('interns_id','selecttable');
    $formAdd->edit->input->interns_id->setTitle('Intern');
    $formAdd->edit->input->interns_id->setReferenceTable('interns');
    $formAdd->edit->input->interns_id->setReferenceField('name','id');
    $formAdd->edit->input->interns_id->setAutoComplete(true); // ✨ Autocomplete aktif
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

$formAdd->edit->action();

if (!empty($_POST)) {
    if ($id == 0) {
        $new_id = $db->Insert_ID();
        if ($new_id > 0) {
            $new = $db->getRow("SELECT * FROM interns_tasks_list WHERE id={$new_id}");
            $db->Execute("INSERT INTO interns_tasks_list_history (interns_id, interns_tasks_list_id, status, created) VALUES ({$new['interns_id']}, {$new_id}, {$new['status']}, NOW())");
            $db->Execute("UPDATE interns_tasks_list SET updated = NOW() WHERE id = {$new_id}");
        }
    } else {
        $new = $db->getRow("SELECT * FROM interns_tasks_list WHERE id={$id}");
        if (!empty($new)) {
            $db->Execute("INSERT INTO interns_tasks_list_history (interns_id, interns_tasks_list_id, status, created) VALUES ({$new['interns_id']}, {$id}, {$new['status']}, NOW())");
            $db->Execute("UPDATE interns_tasks_list SET updated = NOW() WHERE id = {$id}");
        }
    }
    
    $redirect_url = $_SERVER['PHP_SELF'] . (isset($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : '');
    header("Location: {$redirect_url}");
    exit;
}

// Hanya tampilkan form untuk EDIT mode (dipanggil via modal)
if ($id > 0) {
    echo $formAdd->edit->getForm();
}
?>

<?php if (!empty($_GET['is_ajax'])): ?>
<script>
_Bbc($ => {
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