<?php
if (!defined('_VALID_BBC')) exit('No direct script access allowed');

$db_obj       = $GLOBALS['db'];
$task_list_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$form_add = _lib('pea', 'interns_tasks_list');
$form_add->initEdit($task_list_id > 0 ? "WHERE `id`={$task_list_id}" : "");

$current_data = $db_obj->getRow("SELECT l.*, t.timeline 
                                FROM `interns_tasks_list` AS l
                                LEFT JOIN `interns_tasks` AS t ON l.interns_tasks_id = t.id
                                WHERE l.`id` = {$task_list_id}");


$form_add->edit->addInput('header', 'header');
$form_add->edit->input->header->setTitle('Info Interns Tasks');

$form_add->edit->addInput('status', 'select');
$form_add->edit->input->status->setTitle('Status');

$status_aktif = intval(@$current_data['status']);
$options      = [];

if ($status_aktif == 6) {
    $options['Cancel'] = 6;
} else {
    $status_labels = [1=>'To Do', 2=>'In Progress', 3=>'Submit', 4=>'Revised', 5=>'Done', 6=>'Cancel'];
    
    if ($status_aktif > 1 && isset($status_labels[$status_aktif])) {
        $options[$status_labels[$status_aktif]] = $status_aktif;
    }

    switch ($status_aktif) {
        case 1: 
        case 4: 
            $options['In Progress'] = 2;
            $options['Cancel']      = 6;
            break;
        case 2: 
            $options['Submit']      = 3;
            $options['Cancel']      = 6;
            break;
        case 3: 
            $options['In Progress'] = 2;
            $options['Revised']     = 4;
            $options['Done']        = 5;
            $options['Cancel']      = 6;
            break;
        case 5: 
            $options['Revised']     = 4;
            $options['Cancel']      = 6;
            break;
        default: 
            $options['In Progress'] = 2;
            $options['Cancel']      = 6;
            break;
    }
}

foreach ($options as $label => $val) {
    $form_add->edit->input->status->addOption($label, $val);
}

$form_add->edit->addInput('notes', 'textarea');
$form_add->edit->input->notes->setTitle('Notes');

$form_add->edit->action();


if (!empty($_POST) && !empty($_POST['edit_submit_update'])) {
    $final_id = ($task_list_id > 0) ? $task_list_id : $db_obj->Insert_ID();

    if ($final_id > 0) {
        $current_data = $db_obj->getRow("SELECT l.*, t.timeline 
                                        FROM `interns_tasks_list` AS l
                                        LEFT JOIN `interns_tasks` AS t ON l.interns_tasks_id = t.id
                                        WHERE l.`id` = {$final_id}");

        if (!empty($current_data)) {
            $notes_safe = addslashes($current_data['notes']);
            
            $db_obj->Execute("INSERT INTO `interns_tasks_list_history` 
                (`interns_id`, `interns_tasks_list_id`, `status`, `notes`, `created`) 
                VALUES 
                ({$current_data['interns_id']}, {$final_id}, {$current_data['status']}, '{$notes_safe}', NOW())");
            
            $update_fields = ["`updated` = NOW()"];

            if ($current_data['status'] == 2) {
                if (empty($current_data['started']) || $current_data['started'] == '0000-00-00 00:00:00') {
                    $update_fields[] = "`started` = NOW()";
                    $timeline        = intval($current_data['timeline']);
                    $update_fields[] = "`deadline` = DATE_ADD(NOW(), INTERVAL {$timeline} DAY)";
                }
            }

            if ($current_data['status'] == 5) {
                $update_fields[] = "`done_at` = NOW()";
            }

            if (!empty($update_fields)) {
                $db_obj->Execute("UPDATE `interns_tasks_list` SET " . implode(', ', $update_fields) . " WHERE `id` = {$final_id}");
            }
        }
        header("Location: index.php?mod=interns.interns_tasks_list");
        exit;
    }
}


if ($task_list_id > 0) {
    $histories = $db_obj->getAll("SELECT * FROM `interns_tasks_list_history` WHERE `interns_tasks_list_id` = {$task_list_id} ORDER BY `created` DESC");
    
    if (!empty($histories)) {
        $status_labels_history = [1=>'To Do', 2=>'In Progress', 3=>'Submit', 4=>'Revised', 5=>'Done', 6=>'Cancel'];
        
        echo '<div class="panel panel-default" style="margin-bottom: 20px;">';
        echo '<div class="panel-heading"><b>History Notes</b></div>';
        echo '<div class="table-responsive">';
        echo '<table class="table table-bordered table-striped table-hover" style="margin-bottom: 0;">';
        echo '<thead><tr><th width="150">Tanggal</th><th width="120">Status</th><th>Notes</th></tr></thead>';
        echo '<tbody>';
        foreach($histories as $hist) {
            $stat_label = isset($status_labels_history[$hist['status']]) ? $status_labels_history[$hist['status']] : 'Unknown';
            echo '<tr>';
            echo '<td>'.date('d M Y H:i', strtotime($hist['created'])).'</td>';
            echo '<td>'.$stat_label.'</td>';
            echo '<td>'.nl2br(htmlentities($hist['notes'])).'</td>'; 
            echo '</tr>';
        }
        echo '</tbody></table>';
        echo '</div></div>';
    } else {
        echo '<div class="alert alert-info" style="margin-bottom: 20px;">Belum ada history notes untuk task ini.</div>';
    }
}


echo $form_add->edit->getForm();
?>

<script type="text/javascript">
  _Bbc($ => {
    const edit_form_url = new URL(<?php echo json_encode(seo_url()) ?>);
    const edit_form_obj = $('form[name="edit"]');
    const status_sel = $('select[name="status"]');
    const notes_row = $('textarea[name="notes"]').closest('.form-group');

    
    $('textarea[name="notes"]').val('');

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
        success: function() {
          location.reload();
        }
      });
    });
  });
</script>