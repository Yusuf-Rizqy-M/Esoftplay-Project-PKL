<?php
if (!defined('_VALID_BBC')) exit('No direct script access allowed');

_func('download');

if (!empty($_POST['transfer'])) {
  if ($_POST['transfer'] == 'download') {
    $sample_data = array(
      array(
        'Email'      => 'arsya@gmail.com',
        'Name'       => 'arsya',
        'Phone'      => '081234567890',
        'School'     => 'Muhamadiyah Kudus',
        'Major'      => 'PPLG',
        'Start Date' => '2025-10-06',
        'End Date'   => '2026-04-06',
        'Keterangan' => 'Format tanggal: YYYY-MM-DD'
      ),
    );

    download_excel('Sample_Import_Intern_' . date('Y-m-d'), $sample_data, 'Intern Data');
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
            'A' => 'Email',
            'B' => 'Name',
            'C' => 'Phone',
            'D' => 'School',
            'E' => 'Major',
            'F' => 'Start Date',
            'G' => 'End Date'
          );

          $is_valid = true;
          foreach ($headers as $col => $title) {
            if (trim($output[1][$col]) != $title) {
              $is_valid = false;
              break;
            }
          }

          if ($is_valid) {
            $db->Execute('SET FOREIGN_KEY_CHECKS=0');
            $success = 0;
            foreach ($output as $i => $cells) {
              if ($i == 1 || empty($cells['A'])) continue;

              $email = strtolower(trim($cells['A']));
              if (is_email($email)) {
                $is_exist = $db->getOne('SELECT 1 FROM `interns` WHERE `email`=\'' . addslashes($email) . '\'');
                if (!$is_exist) {
                  $school_name = trim($cells['D']);
                  $school_id   = $db->getOne('SELECT `id` FROM `interns_school` WHERE `school_name`=\'' . addslashes($school_name) . '\'');
                  
                  if (!$school_id && !empty($school_name)) {
                    $db->Execute('INSERT INTO `interns_school` SET `school_name`=\'' . addslashes($school_name) . '\'');
                    $school_id = $db->Insert_ID();
                  }

                  $user_id = $db->getOne('SELECT `id` FROM `bbc_user` WHERE `username`=\'' . addslashes($email) . '\'');
                  if (!$user_id) {
                    $user_params = array(
                      'username'  => $email,
                      'name'      => $cells['B'],
                      'email'     => $email,
                      'password'  => 'intern123',
                      'group_ids' => array(3),
                      'params'    => array('register_at' => date('Y-m-d H:i:s'))
                    );
                    $user_id = user_create($user_params);
                  }
                  
                  if ($user_id) {
                    $start_date = _date($cells['F']);
                    $end_date   = _date($cells['G']);
                    $status     = _status($start_date, $end_date);

                    $q = 'INSERT INTO `interns` SET 
                          `user_id`     = ' . intval($user_id) . ', 
                          `email`       = \'' . addslashes($email) . '\', 
                          `name`        = \'' . addslashes($cells['B']) . '\', 
                          `phone`       = \'' . addslashes($cells['C']) . '\', 
                          `school_id`   = ' . intval($school_id) . ', 
                          `major`       = \'' . addslashes($cells['E']) . '\', 
                          `start_date`  = \'' . $start_date . '\', 
                          `end_date`    = \'' . $end_date . '\', 
                          `status`      = ' . intval($status) . ', 
                          `created`     = NOW()';
                    if ($db->Execute($q)) $success++;
                  }
                }
              }
            }
            $db->Execute('SET FOREIGN_KEY_CHECKS=1');
            $msg = msg('Upload data berhasil dieksekusi. ' . $success . ' data berhasil diimport.', 'success');
          } else {
            $msg = msg('Maaf, format kolom file tidak sesuai dengan ketentuan.', 'danger');
          }
        }
      }
    }
    if (!empty($msg)) echo $msg;
  }
}

$form_search = _lib('pea', 'interns');
$form_search->initSearch();
$form_search->search->addInput('status', 'select');
$form_search->search->input->status->setTitle('Status');
$form_search->search->input->status->addOption('All Status', '');
$form_search->search->input->status->addOption('Active', '1');
$form_search->search->input->status->addOption('Ended', '2');
$form_search->search->input->status->addOption('Coming Soon', '3');
$form_search->search->addInput('name', 'keyword');
$form_search->search->input->name->setTitle('Name');
$form_search->search->input->name->addSearchField('name', false);

$form_search->search->addInput('school_id', 'selecttable');
$form_search->search->input->school_id->setTitle('School');
$form_search->search->input->school_id->setReferenceTable('interns_school');
$form_search->search->input->school_id->setReferenceField('school_name', 'id');
$form_search->search->input->school_id->setAutoComplete(true);

$form_search->search->addInput('start_date', 'dateinterval');
$form_search->search->input->start_date->setIsSearchRange();
$form_search->search->input->start_date->setTitle('Start Date');

$add_sql = $form_search->search->action();
echo $form_search->search->getForm();

$is_edit   = (!empty($_GET['id']) && is_numeric($_GET['id']));
$form_list = _lib('pea', 'interns');
$form_list->initRoll($add_sql . ' ORDER BY `id` DESC', 'id');
$form_list->roll->setDeleteTool(true);
$form_list->roll->setSaveTool(false);

$form_list->roll->addInput('name', 'sqllinks');
$form_list->roll->input->name->setTitle('Name');
$form_list->roll->input->name->setLinks($Bbc->mod['circuit'] . '.interns_edit');

$form_list->roll->addInput('email', 'sqlplaintext');
$form_list->roll->input->email->setTitle('Email');

// Menggunakan selecttable + setPlaintext(true) agar ringan (JOIN otomatis oleh PEA)
$form_list->roll->addInput('school_id', 'selecttable');
$form_list->roll->input->school_id->setTitle('School');
$form_list->roll->input->school_id->setReferenceTable('interns_school');
$form_list->roll->input->school_id->setReferenceField('school_name', 'id');
$form_list->roll->input->school_id->setPlaintext(true);

$form_list->roll->addInput('phone', 'sqlplaintext');
$form_list->roll->input->phone->setTitle('Phone');

$form_list->roll->addInput('custom_interval', 'sqlplaintext');
$form_list->roll->input->custom_interval->setTitle(lang('Internship Period'));
$form_list->roll->input->custom_interval->setFieldName('CONCAT(`start_date`,\' - \',`end_date`)');
$form_list->roll->input->custom_interval->setDisplayFunction(function($value) {
  $parts = explode('-', $value);
  if (count($parts) < 6) return $value;
  list($a_y, $a_m, $a_d, $b_y, $b_m, $b_d) = $parts;
  $a_ts = strtotime($a_y . '-' . $a_m . '-' . $a_d);
  $b_ts = strtotime($b_y . '-' . $b_m . '-' . $b_d);
  if ($a_y === $b_y) {
    if ($a_m === $b_m) {
      if ($a_d === $b_d) return date('d F Y', $a_ts);
      return date('d - ', $a_ts) . date('d F Y', $b_ts);
    }
    return date('d F - ', $a_ts) . date('d F Y', $b_ts);
  }
  return date('d F Y - ', $a_ts) . date('d F Y', $b_ts);
});

$form_list->roll->addInput('status', 'sqlplaintext');
$form_list->roll->input->status->setTitle('Status');
$form_list->roll->input->status->setDisplayFunction(function ($value) {
  $status_map = [
    1 => ['label' => 'Active', 'color' => '#28a745'],
    2 => ['label' => 'Ended', 'color' => '#dc3545'],
    3 => ['label' => 'Coming Soon', 'color' => '#007bff']
  ];
  if (empty($value) || !isset($status_map[$value])) return 'Unknown';
  $status = $status_map[$value];
  return '<span class="label" style="background-color: ' . $status['color'] . '; color: white; padding: 5px 12px; border-radius: 12px;">' . $status['label'] . '</span>';
});

$form_list->roll->addInput('id_menu_task', 'editlinks'); 
$form_list->roll->input->id_menu_task->setTitle('Action');
$form_list->roll->input->id_menu_task->setFieldName('id');
$form_list->roll->input->id_menu_task->setCaption('Opsi');
$form_list->roll->input->id_menu_task->setGetName('interns_id');
$form_list->roll->input->id_menu_task->setLinks(array(
  $Bbc->mod['circuit'] . '.interns_tasks_list_edit' => icon('fa-list') . ' Tambah Pengerjaan'
));

$form_list->roll->addInput('task_link', 'sqlplaintext');
$form_list->roll->input->task_link->setTitle('View');
$form_list->roll->input->task_link->setFieldName('id'); 
$form_list->roll->input->task_link->setDisplayFunction(function($intern_id){
  global $Bbc;
  $url = $Bbc->mod['circuit'] . '.interns_tasks_list&interns_id=' . $intern_id . '&is_list=1';
  return '<a href="' . $url . '" class="btn btn-xs btn-primary"> Lihat Pengerjaan User</a>';
});

$form_list->roll->addInput('created', 'sqlplaintext');
$form_list->roll->input->created->setDisplayColumn(false);
$form_list->roll->action();

ob_start();
include 'interns_edit.php';
$form_edit_content = ob_get_clean();

echo '<div class="btn-group" style="margin-bottom: 15px;">';
echo '  <a href="' . $Bbc->mod['circuit'] . '.interns_tasks" class="btn btn-default">' . icon('fa-tasks') . ' Halaman Data Tugas</a>';
echo '  <a href="' . $Bbc->mod['circuit'] . '.interns_tasks_list" class="btn btn-default">' . icon('fa-list-alt') . ' Halaman List Pengerjaan Tugas</a>';
echo '  <a href="' . $Bbc->mod['circuit'] . '.interns_tasks_list_history" class="btn btn-default">' . icon('fa-history') . ' Halaman History</a>';
echo '</div>';

$tabs = array(
  'List Interns' => $form_list->roll->getForm(),
  ($is_edit ? 'Edit Intern' : 'Add Intern') => $form_edit_content
);
echo tabs($tabs, ($is_edit ? 2 : 1), 'tabs_interns');

function _date($date_str) {
  $ts = strtotime(str_replace('/', '-', $date_str));
  return date('Y-m-d', $ts ?: time());
}
function _status($start, $end) {
  $curr = date('Y-m-d');
  if ($curr < $start) return 3;
  if ($curr <= $end) return 1;
  return 2;
}
?>

<div class="col-xs-12 no-both">
  <div class="panel panel-default">
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
            <p class="help-block">Pastikan kolom sesuai: Name, Email, School, Phone, Start Date, End Date</p>
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