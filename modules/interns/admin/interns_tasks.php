<?php
if (!defined('_VALID_BBC')) exit('No direct script access allowed');

_func('download');

if (!empty($_POST['transfer'])) {
  if ($_POST['transfer'] == 'download') {
    $data_sample = [
      [
        'title'       => 'Implementasi API Login',
        'description' => 'Membuat endpoint login menggunakan JWT',
        'timeline'    => '2',
        'type'        => 'Backend',
      ],
      [
        'title'       => 'Slicing Landing Page',
        'description' => 'Mengubah desain Figma ke HTML/CSS/JS',
        'timeline'    => '3',
        'type'        => 'Frontend',
      ],
      [
        'title'       => 'Bug Fixing Dashboard',
        'description' => 'Memperbaiki tampilan grafik yang berantakan',
        'timeline'    => '1',
        'type'        => 'UI/UX',
      ]
    ];

    download_excel('Sample_Tasks_Template_' . date('Y-m-d'), $data_sample);
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
          $headers = array(
            'A' => 'Title',
            'B' => 'Description',
            'C' => 'Timeline',
            'D' => 'Type'
          );

          $is_valid = true;
          foreach ($headers as $col => $title_col) {
            if (trim($output[1][$col]) != $title_col) {
              $is_valid = false;
              break;
            }
          }

          if ($is_valid) {
            $success_count = 0;
            foreach ($output as $i => $cells) {
              if ($i == 1 || empty($cells['A'])) continue;

              $task_title = trim($cells['A']);
              $task_desc  = trim($cells['B']);
              $task_time  = trim($cells['C']);
              $task_type  = trim($cells['D']);

              $is_exist = $db->getOne("SELECT 1 FROM interns_tasks WHERE title='" . addslashes($task_title) . "'");
              if (!$is_exist) {
                $type_id = $db->getOne("SELECT id FROM interns_tasks_type WHERE type_name='" . addslashes($task_type) . "'");
                if (!$type_id && !empty($task_type)) {
                  $db->Execute("INSERT INTO interns_tasks_type SET type_name='" . addslashes($task_type) . "'");
                  $type_id = $db->Insert_ID();
                }

                $timeline_val = !empty($task_time) && is_numeric($task_time) ? intval($task_time) : 0;
                $sql_query = "INSERT INTO interns_tasks SET 
                                    title           = '" . addslashes($task_title) . "', 
                                    description     = '" . addslashes($task_desc) . "', 
                                    timeline        = $timeline_val, 
                                    task_type_id    = " . intval($type_id) . ", 
                                    created         = NOW()";
                if ($db->Execute($sql_query)) $success_count++;
              }
            }
            $msg = msg("Upload tasks berhasil. $success_count data baru berhasil diimport.", 'success');
          } else {
            $msg = msg('Maaf, format kolom file tidak sesuai. Pastikan urutan: Title, Description, Timeline, Type.', 'danger');
          }
        } else {
          $msg = msg('Maaf, file yang anda upload tidak terbaca.', 'danger');
        }
      } else {
        $msg = msg('Mohon upload file dengan format Excel yang benar (.xlsx)', 'danger');
      }
    }
    if (!empty($msg)) echo $msg;
  }
}

$form_search = _lib('pea', 'interns_tasks');
$form_search->initSearch();

$form_search->search->addInput('title', 'selecttable');
$form_search->search->input->title->setTitle('Search Task Title');
$form_search->search->input->title->setReferenceTable('interns_tasks');
$form_search->search->input->title->setReferenceField('title', 'id');
$form_search->search->input->title->setAutoComplete(true);

$form_search->search->addInput('task_type_id', 'selecttable');
$form_search->search->input->task_type_id->setTitle('Type');
$form_search->search->input->task_type_id->setReferenceTable('interns_tasks_type');
$form_search->search->input->task_type_id->setReferenceField('type_name', 'id');
$form_search->search->input->task_type_id->setAutoComplete(true);

$add_sql = $form_search->search->action();

echo '<div style="margin-bottom: 20px;">';
echo $form_search->search->getForm();
echo '</div>';

$is_edit = (!empty($_GET['id']) && is_numeric($_GET['id']));

$form_list = _lib('pea', 'interns_tasks');
$form_list->initRoll($add_sql . ' ORDER BY id DESC', 'id');
$form_list->roll->setSaveTool(false);
$form_list->roll->setDeleteTool(true);

$form_list->roll->addInput('title', 'sqllinks');
$form_list->roll->input->title->setLinks($Bbc->mod['circuit'] . '.interns_tasks_edit');
$form_list->roll->input->title->setTitle('Title');

$form_list->roll->addInput('description', 'sqlplaintext');
$form_list->roll->input->description->setTitle('Description');

$form_list->roll->addInput('timeline', 'sqlplaintext');
$form_list->roll->input->timeline->setTitle('Timeline');

$form_list->roll->addInput('task_type_id', 'sqlplaintext');
$form_list->roll->input->task_type_id->setTitle('Type');
$form_list->roll->input->task_type_id->setFieldName('(SELECT `type_name` FROM `interns_tasks_type` WHERE `interns_tasks_type`.id=`interns_tasks`.`task_type_id`) AS type_name');


$form_list->roll->addInput('assigned_to', 'sqlplaintext');
$form_list->roll->input->assigned_to->setTitle('Assigned To');
$form_list->roll->input->assigned_to->setFieldName('id AS assigned_to');
$form_list->roll->input->assigned_to->setDisplayFunction(function ($id) {
  global $db;
  $names = $db->getOne("SELECT GROUP_CONCAT(i.name SEPARATOR ', ') 
                          FROM interns_tasks_list itl 
                          LEFT JOIN interns i ON itl.interns_id = i.id 
                          WHERE itl.interns_tasks_id = " . intval($id) . " 
                          AND i.status = 1");

  return !empty($names) ? $names : '-';
});



$form_list->roll->addInput('assigned', 'editlinks');
$form_list->roll->input->assigned->setTitle('Action');
$form_list->roll->input->assigned->setCaption(icon('fa-list') . ' Assigned');
$form_list->roll->input->assigned->setFieldName('id');
$form_list->roll->input->assigned->setGetName('interns_tasks_id');
$form_list->roll->input->assigned->setLinks($Bbc->mod['circuit'] . '.interns_tasks_assigned');


$form_list->roll->addInput('task_link', 'sqllinks');
$form_list->roll->input->task_link->setLinks('#');
$form_list->roll->input->task_link->setTitle('View');
$form_list->roll->input->task_link->setFieldName('id as task_link');
$form_list->roll->input->task_link->setDisplayFunction(function ($id) {
  global $Bbc;
  
  // 1. Ambil URL halaman daftar Task saat ini untuk tombol Back
  $return_url = urlencode(seo_url());
  
  // 2. Tambahkan &return= ke URL tujuan
  $target_url = $Bbc->mod['circuit'] . '.interns_tasks_list&internal_tasks_id=' . $id . '&return=' . $return_url;
  
  return '<a href="' . $target_url . '" class="btn btn-xs btn-primary">Activities</a>';
});

$form_list->roll->addInput('created', 'sqlplaintext');
$form_list->roll->input->created->setDisplayColumn(false);

$form_list->roll->action();

ob_start();
include 'interns_tasks_edit.php';
$form_edit_content = ob_get_clean();

$tab_list = array(
  'List Task' => $form_list->roll->getForm(),
  ($is_edit ? 'Edit Task' : 'Add Task') => $form_edit_content
);
echo tabs($tab_list, ($is_edit ? 2 : 1), 'tabs_interns_tasks');

?>

<div class="col-xs-12 no-both">
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title" data-toggle="collapse" href="#import_tasks_panel" style="cursor:pointer;">
        <?php echo icon('fa-file-excel-o') ?> Klik disini untuk import data Excel
      </h4>
    </div>
    <div id="import_tasks_panel" class="panel-collapse collapse">
      <form action="" method="POST" enctype="multipart/form-data">
        <div class="panel-body">
          <div class="form-group">
            <label>Upload File Excel (.xlsx atau .xls)</label>
            <input type="file" name="excel" class="form-control" accept=".xlsx,.xls" />
            <div class="help-block">Pastikan kolom sesuai: Title, Description, Timeline, Type.</div>
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