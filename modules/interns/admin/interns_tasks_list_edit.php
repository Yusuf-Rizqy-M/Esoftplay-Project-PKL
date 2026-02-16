<?php
if (!defined('_VALID_BBC')) exit('No direct script access allowed');
?>
<style>
  .modal-header .btn:not(.close),
  .modal-header button[type="button"]:not(.close) {
    display: none !important;
  }
</style>
<?php
$db_obj = $GLOBALS['db'];
$task_list_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$form_add = _lib('pea', 'interns_tasks_list');
$form_add->initEdit($task_list_id > 0 ? "WHERE `id`={$task_list_id}" : "");

if ($task_list_id > 0) {
  $form_add->edit->addInput('header', 'header');
  $form_add->edit->input->header->setTitle('Edit Notes Intern');
  $form_add->edit->addInput('interns_tasks_id', 'hidden');
  $form_add->edit->addInput('interns_id', 'hidden');
} else {
  $form_add->edit->addInput('header', 'header');
  $form_add->edit->input->header->setTitle('Add New Task');

  $form_add->edit->addInput('interns_tasks_id', 'selecttable');
  $form_add->edit->input->interns_tasks_id->setTitle('Task');
  $form_add->edit->input->interns_tasks_id->setReferenceTable('interns_tasks');
  $form_add->edit->input->interns_tasks_id->setReferenceField('title', 'id');
  $form_add->edit->input->interns_tasks_id->setAllowNew(true);
  $form_add->edit->input->interns_tasks_id->setRequire();

  $form_add->edit->addInput('interns_id', 'selecttable');
  $form_add->edit->input->interns_id->setTitle('Name');
  $form_add->edit->input->interns_id->setReferenceTable('interns');
  $form_add->edit->input->interns_id->setReferenceField('name', 'id');
  $form_add->edit->input->interns_id->setAutoComplete(true);
  $form_add->edit->input->interns_id->setRequire();

  if (!empty($_GET['interns_id'])) {
    $form_add->edit->input->interns_id->setDefaultValue(($_GET['interns_id']));
  }

  $form_add->edit->addInput('status', 'select');
  $form_add->edit->input->status->setTitle('Status');
  $form_add->edit->input->status->addOption('To Do', 1);
  $form_add->edit->input->status->addOption('In Progress', 2);
  $form_add->edit->input->status->addOption('Submit', 3);
  $form_add->edit->input->status->addOption('Revised', 4);
  $form_add->edit->input->status->addOption('Done', 5);
  $form_add->edit->input->status->addOption('Cancel', 6);
  $form_add->edit->input->status->setRequire();
}

$form_add->edit->addInput('notes', 'textarea');
$form_add->edit->input->notes->setTitle('Notes');

$form_add->edit->action();

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
?>

<?php if (!empty($_GET['is_ajax'])): ?>
  <script>
    _Bbc($ => {
      setTimeout(() => {
        const active_modal = parent.$('.modal.in');
        if (active_modal.length) {
          active_modal.find('.modal-header .btn, .modal-header button[type="button"]:not(.close)').remove();
        }
      }, 100);

      const edit_form_url = new URL(<?php echo json_encode(seo_url()) ?>);
      const edit_form_obj = $('form[name="edit"]');
      edit_form_url.searchParams.delete('is_ajax');
      edit_form_url.searchParams.delete('return');

      edit_form_obj.on('submit', e => {
        e.preventDefault();
        let form_data = edit_form_obj.serialize() + '&edit_submit_update=SAVE';
        $.ajax({
          type: "POST",
          url: edit_form_url.toString(),
          data: form_data,
          success: function(response) {
            location.reload();
          }
        });
      });
    });
  </script>
<?php endif; ?>