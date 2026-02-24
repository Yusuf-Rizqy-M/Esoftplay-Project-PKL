<?php
if (!defined('_VALID_BBC')) exit('No direct script access allowed');

_func('download');

if (!empty($_POST['transfer'])) {
  if ($_POST['transfer'] == 'download') {
    $r = [
      [
        'Task Title'   => 'Bug Fixing Dashboard',
        'Intern Email' => 'yusufhabib290@gmail.com',
        'Notes'        => 'Mengerjakan modul auth',
        'Status'       => 'To Do'
      ],
      [
        'Task Title'   => 'Slicing Landing Page',
        'Intern Email' => 'yusufhabib290@gmail.com',
        'Notes'        => 'Gunakan Bootstrap 5',
        'Status'       => 'In Progress'
      ]
    ];
    download_excel('Template_Import_Task_List_' . date('Y-m-d'), $r);
    die();
  }

  if ($_POST['transfer'] == 'upload') {
    $msg = '';
    if (!empty($_FILES['excel']['tmp_name']) && is_uploaded_file($_FILES['excel']['tmp_name'])) {
      $mimes = array('application/vnd.ms-excel', 'text/xls', 'text/xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
      if (in_array($_FILES['excel']['type'], $mimes)) {
        $excel_lib = _lib('excel')->read($_FILES['excel']['tmp_name']);
        $output    = $excel_lib->sheet(1)->fetch();

        if (!empty($output) && is_array($output)) {
          $headers = array('A' => 'Task Title', 'B' => 'Intern Email', 'C' => 'Notes', 'D' => 'Status');
          $is_valid = true;
          foreach ($headers as $col => $title) {
            if (trim($output[1][$col]) != $title) {
              $is_valid = false;
              break;
            }
          }

          if ($is_valid) {
            $db->Execute("SET FOREIGN_KEY_CHECKS=0");
            $success = 0;
            $status_map = [
              'to do'       => 1,
              'in progress' => 2,
              'submit'      => 3,
              'revised'     => 4,
              'done'        => 5,
              'cancel'      => 6
            ];

            foreach ($output as $i => $cells) {
              if ($i == 1 || empty($cells['A']) || empty($cells['B'])) continue;

              $task_title = addslashes(trim($cells['A']));
              $intern_email = addslashes(strtolower(trim($cells['B'])));

              $task_id   = $db->getOne("SELECT id FROM interns_tasks WHERE title='{$task_title}' LIMIT 1");
              $intern_id = $db->getOne("SELECT id FROM interns WHERE email='{$intern_email}' LIMIT 1");

              if ($task_id && $intern_id) {
                $st_text = strtolower(trim($cells['D']));
                $status  = isset($status_map[$st_text]) ? $status_map[$st_text] : 1;
                $notes   = addslashes(trim($cells['C']));

                $q = "INSERT INTO interns_tasks_list SET 
                                        interns_id        = $intern_id, 
                                        interns_tasks_id = $task_id, 
                                        notes             = '$notes', 
                                        status            = $status, 
                                        created           = NOW(), 
                                        updated           = NOW()";
                if ($db->Execute($q)) $success++;
              }
            }
            $db->Execute("SET FOREIGN_KEY_CHECKS=1");
            $msg = msg("Upload selesai. $success data berhasil diimport.", 'success');
          } else {
            $msg = msg('Format header kolom Excel salah. Pastikan menggunakan template yang disediakan.', 'danger');
          }
        }
      } else {
        $msg = msg('Gunakan format file .xlsx atau .xls', 'danger');
      }
    }
    if (!empty($msg)) echo $msg;
  }
}


$form_search = _lib('pea', 'interns_tasks_list');
$form_search->initSearch();

$form_search->search->addInput('status_intern', 'select');
$form_search->search->input->status_intern->setTitle('Status');
$form_search->search->input->status_intern->addOption('All Status', '');
$form_search->search->input->status_intern->addOption('Active', '1');
$form_search->search->input->status_intern->addOption('Ended', '2');
$form_search->search->input->status_intern->addOption('Coming Soon', '3');

$form_search->search->addInput('interns_id', 'selecttable');
$form_search->search->input->interns_id->setTitle('');
$form_search->search->input->interns_id->setReferenceTable('interns');
$form_search->search->input->interns_id->setReferenceField('name', 'id');
$form_search->search->input->interns_id->setAutoComplete(true);

$form_search->search->addInput('interns_id', 'selecttable');
$form_search->search->input->interns_id->setTitle('Search Name');
$form_search->search->input->interns_id->setReferenceTable('interns');
$form_search->search->input->interns_id->setReferenceField('name', 'id');
$form_search->search->input->interns_id->setAutoComplete(true);

$form_search->search->addInput('interns_tasks_id', 'selecttable');
$form_search->search->input->interns_tasks_id->setTitle('Search Task');
$form_search->search->input->interns_tasks_id->setReferenceTable('interns_tasks');
$form_search->search->input->interns_tasks_id->setReferenceField('title', 'id');
$form_search->search->input->interns_tasks_id->setAutoComplete(true);

$form_search->search->addInput('status', 'select');
$form_search->search->input->status->addOption('---- Filter Status ----', '');
$form_search->search->input->status->addOption('To Do', '1');
$form_search->search->input->status->addOption('In Progress', '2');
$form_search->search->input->status->addOption('Submit', '3');
$form_search->search->input->status->addOption('Revised', '4');
$form_search->search->input->status->addOption('Done', '5');
$form_search->search->input->status->addOption('Cancel', '6');

$form_search->search->addInput('notes', 'keyword');
$form_search->search->input->notes->addSearchField('notes', false);

$add_sql = $form_search->search->action();
$keyword = $form_search->search->keyword();

$is_edit = (!empty($_GET['id']) && is_numeric($_GET['id']));
if (!empty($_GET['internal_tasks_id'])) {
  $add_sql .= ' AND interns_tasks_id = ' . $_GET['internal_tasks_id'];
}

$form_list = _lib('pea', 'interns_tasks_list');
$form_list->initRoll($add_sql . ' ORDER BY id DESC', 'id');

$form_list->roll->addInput('interns_tasks_id', 'selecttable');
$form_list->roll->input->interns_tasks_id->setTitle('Tasks Title');
$form_list->roll->input->interns_tasks_id->setReferenceTable('interns_tasks');
$form_list->roll->input->interns_tasks_id->setReferenceField('title', 'id');
$form_list->roll->input->interns_tasks_id->setModal(true);
$form_list->roll->input->interns_tasks_id->setLinks($Bbc->mod['circuit'] . '.interns_tasks_list_info');
$form_list->roll->input->interns_tasks_id->setPlaintext(true);

$form_list->roll->addInput('interns_id', 'selecttable');
$form_list->roll->input->interns_id->setTitle('Interns Name');
$form_list->roll->input->interns_id->setReferenceTable('interns');
$form_list->roll->input->interns_id->setReferenceField('name', 'id');
$form_list->roll->input->interns_id->setPlaintext(true);

$form_list->roll->addInput('notes', 'sqlplaintext');
$form_list->roll->input->notes->setTitle('Notes');

$form_list->roll->addInput('timeline', 'selecttable');
$form_list->roll->input->timeline->setTitle('Timeline (Days)');
$form_list->roll->input->timeline->setReferenceTable('interns_tasks');
$form_list->roll->input->timeline->setReferenceField('timeline', 'id');
$form_list->roll->input->timeline->setPlaintext(true);
$form_list->roll->input->timeline->setFieldName('interns_tasks_id AS timeline');

$form_list->roll->addInput('status', 'sqllinks');
$form_list->roll->input->status->setModal(true);
$form_list->roll->input->status->setLinks($Bbc->mod['circuit'] . '.interns_tasks_list_status');
$form_list->roll->input->status->setDisplayFunction(function ($v) {
  $colors = [1 => '#6c757d', 2 => '#007bff', 3 => '#ffc107', 4 => '#fd7e14', 5 => '#28a745', 6 => '#dc3545'];
  $labels = [1 => 'To Do', 2 => 'In Progress', 3 => 'Submit', 4 => 'Revised', 5 => 'Done', 6 => 'Cancel'];
  $tcolor = ($v == 3) ? 'black' : 'white';
  return '<span class="label" style="background-color:' . (@$colors[$v] ?: '#eee') . '; color:' . $tcolor . '; padding:5px 10px; border-radius:12px;">' . (@$labels[$v] ?: 'Unknown') . '</span>';
});

$form_list->roll->addInput('started', 'sqlplaintext');
$form_list->roll->input->started->setTitle('Started');

$form_list->roll->addInput('deadline', 'sqlplaintext');
$form_list->roll->input->deadline->setTitle('Deadline');

$form_list->roll->addInput('revised_history', 'sqlplaintext');
$form_list->roll->input->revised_history->setTitle('History');
$form_list->roll->input->revised_history->setFieldName('id');
$form_list->roll->input->revised_history->setDisplayFunction(function ($id) {
  global $Bbc;
  $url = $Bbc->mod['circuit'] . '.interns_tasks_list_history&tasks_list_id=' . $id;
  return '<a href="' . $url . '" class="btn btn-xs btn-primary">' . icon('fa-history') . ' View History</a>';
});

$form_list->roll->addInput('done_at', 'sqlplaintext');
$form_list->roll->input->done_at->setTitle('Done At');

$form_list->roll->setDeleteTool(false);
$form_list->roll->setSaveTool(false);

$form_list->roll->addInput('status_intern', 'selecttable');
$form_list->roll->input->status_intern->setTitle('Status Interns');
$form_list->roll->input->status_intern->setReferenceTable('interns');
$form_list->roll->input->status_intern->setReferenceField('status', 'id');
$form_list->roll->input->status_intern->setPlaintext(true);
$form_list->roll->input->status_intern->setFieldName('interns_id AS status_intern');
$form_list->roll->input->status_intern->setDisplayFunction(function ($value) {
  $status_map = [
    1 => ['label' => 'Active', 'color' => '#28a745'],
    2 => ['label' => 'Ended', 'color' => '#dc3545'],
    3 => ['label' => 'Coming Soon', 'color' => '#007bff']
  ];
  if (isset($status_map[$value])) {
    $status = $status_map[$value];
    return '<span class="label" style="background-color: ' . $status['color'] . '; color: white; padding: 5px 12px; border-radius: 12px;">' . $status['label'] . '</span>';
  }
  return '<span class="label" style="background-color: #6c757d; color: white; padding: 5px 12px; border-radius: 12px;">Unknown</span>';
});
$form_list->roll->input->status_intern->setDisplayColumn(false);

$form_list->roll->input->status_intern->setDisplayColumn(false);
$form_list->roll->addInput('created', 'sqlplaintext');
$form_list->roll->input->created->setDisplayColumn(false);
$form_list->roll->addInput('updated', 'sqlplaintext');
$form_list->roll->input->updated->setDisplayColumn(false);
$form_list->roll->action();


ob_start();
include 'interns_tasks_list_edit.php';
$form_edit_content = ob_get_clean();
$internal_tasks_id = @intval($_GET['internal_tasks_id']);
$intern_id         = @intval($_GET['interns_id']);

if ($internal_tasks_id > 0 || ($intern_id > 0 && !empty($_GET['is_list']))) {
  echo '<div class="panel panel-default">';
  echo '<div class="panel-heading">';

  if ($internal_tasks_id > 0) {

    $task = $db->getRow('SELECT title from interns_tasks where id = ' . $internal_tasks_id);
    echo icon('fa-tasks') . ' ' . $task['title'];
  } else {
    $user = $db->getRow('SELECT name from interns where id = ' . $intern_id);
    echo icon('fa-user') . ' ' . $user['name'];
  }

  echo '</div>';
  echo '<div class="panel-body">';
  echo '<div style="margin-bottom: 15px;">';
  echo '<a href="javascript:history.back()" class="btn btn-default btn-sm">' . icon('fa-chevron-left') . ' Kembali</a>';
  echo '</div>';

  echo $form_list->roll->getForm();
  echo '</div></div>';
} else {
  echo '<div style="margin-bottom: 20px;">';
  echo $form_search->search->getform();
  echo '</div>';

  $tabs = [
    'List To Do' => $form_list->roll->getForm(),
    ($is_edit ? 'Edit Task' : 'Add To Do') => $form_edit_content
  ];
  echo tabs($tabs, ($is_edit ? 2 : 1), 'tabs_task_list');

  echo '<div style="margin-bottom: 15px;">';
  echo '<a href="' . $Bbc->mod['circuit'] . '.interns" class="btn btn-default btn-sm">' . icon('fa-chevron-left') . ' Kembali</a>';
  echo '</div>';

?>
  <div class="col-xs-12 no-both" style="padding: 10px 0;">
    <div class="panel panel-default" style="margin-top:20px;">
      <div class="panel-heading">
        <h4 class="panel-title" data-toggle="collapse" href="#import_panel" style="cursor:pointer;">
          <?php echo icon('fa-file-excel-o') ?> Klik disini untuk import data Excel
        </h4>
      </div>
      <div id="import_panel" class="panel-collapse collapse">
        <form action="" method="POST" enctype="multipart/form-data">
          <div class="panel-body">
            <div class="form-group">
              <label>Upload File Excel (.xlsx atau .xls)</label>
              <input type="file" name="excel" class="form-control" accept=".xlsx,.xls" />
              <p class="help-block">Pastikan kolom sesuai: Task Title, Intern Email, Notes, Status</p>
            </div>
          </div>
          <div class="panel-footer">
            <button type="submit" name="transfer" value="upload" class="btn btn-primary"><?php echo icon('fa-upload') ?> Upload Sekarang</button>
            <button type="submit" name="transfer" value="download" class="btn btn-default pull-right"><?php echo icon('fa-download') ?> Download Sample</button>
          </div>
        </form>
      </div>
    </div>
  </div>
<?php
}
