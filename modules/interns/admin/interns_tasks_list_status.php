<?php
if (!defined('_VALID_BBC')) exit('No direct script access allowed');

$db_obj       = $GLOBALS['db'];
$task_list_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$form_add = _lib('pea', 'interns_tasks_list');
$form_add->initEdit($task_list_id > 0 ? "WHERE `id`={$task_list_id}" : "");

$form_add->edit->addInput('status', 'select');
$form_add->edit->input->status->setTitle('Status');
$form_add->edit->input->status->addOption('In Progress', 2);
$form_add->edit->input->status->addOption('Revised', 4);
$form_add->edit->input->status->addOption('Done', 5);
$form_add->edit->input->status->addOption('Cancel', 6);

$form_add->edit->addInput('notes', 'textarea');
$form_add->edit->input->notes->setTitle('Notes');

$form_add->edit->action();
// kode untukstarted ended 
if (!empty($_POST) && !empty($_POST['edit_submit_update'])) {
    $final_id = ($task_list_id > 0) ? $task_list_id : $db_obj->Insert_ID();
    if ($final_id > 0) {
        $current_data = $db_obj->getRow("SELECT l.*, t.timeline FROM `interns_tasks_list` AS l LEFT JOIN `interns_tasks` AS t ON (l.interns_tasks_id=t.id) WHERE l.`id`={$final_id}");
        if (!empty($current_data)) {
            $db_obj->Execute("INSERT INTO `interns_tasks_list_history` (`interns_id`, `interns_tasks_list_id`, `status`, `notes`, `created`) VALUES ({$current_data['interns_id']}, {$final_id}, {$current_data['status']}, '".addslashes($current_data['notes'])."', NOW())");
            
            $update_fields = ["`updated` = NOW()"];
            if ($current_data['status'] == 2) {
                if (empty($current_data['started']) || $current_data['started'] == '0000-00-00 00:00:00') {
                    $update_fields[] = "`started` = NOW()";
                    $timeline = intval($current_data['timeline']);
                    $update_fields[] = "`deadline` = DATE_ADD(NOW(), INTERVAL {$timeline} DAY)";
                }
            }
            
            $db_obj->Execute("UPDATE `interns_tasks_list` SET " . implode(', ', $update_fields) . " WHERE `id` = {$final_id}");
        }
        header("Location: index.php?mod=interns.interns_tasks_list");
        exit;
    }
}
//kode untuk interns task list history
if (!empty($_POST) && !empty($_POST['edit_submit_update'])) {
    $final_id = ($task_list_id > 0) ? $task_list_id : $db_obj->Insert_ID();
    if ($final_id > 0) {
        $current_data = $db_obj->getRow("SELECT * FROM `interns_tasks_list` WHERE `id`={$final_id}");
        if (!empty($current_data)) {
            $db_obj->Execute("INSERT INTO `interns_tasks_list_history` (`interns_id`, `interns_tasks_list_id`, `status`, `created`) VALUES ({$current_data['interns_id']}, {$final_id}, {$current_data['status']}, NOW())");
            $db_obj->Execute("UPDATE `interns_tasks_list` SET `updated` = NOW() WHERE `id` = {$final_id}");
        }
        header("Location: index.php?mod=interns.interns_tasks_list");
        exit;
    }
}


echo $form_add->edit->getForm();

if (!empty($_GET['is_ajax'])): ?>
  <script>
    _Bbc($ => {
      const edit_form_url = new URL(<?php echo json_encode(seo_url()) ?>);
      const edit_form_obj = $('form[name="edit"]');
      const status_sel    = $('select[name="status"]');
      const notes_row     = $('textarea[name="notes"]').closest('.form-group');

      function toggleNotes() {
        if (['4', '5', '6'].includes(status_sel.val())) {
          notes_row.show();
        } else {
          notes_row.hide();
        }
      }

      status_sel.on('change', toggleNotes);
      toggleNotes();

      edit_form_url.searchParams.delete('is_ajax');
      edit_form_obj.on('submit', e => {
        e.preventDefault();
        $.ajax({
          type: "POST",
          url: edit_form_url.toString(),
          data: edit_form_obj.serialize() + '&edit_submit_update=SAVE',
          success: function() { location.reload(); }
        });
      });
    });
  </script>
<?php endif; ?>