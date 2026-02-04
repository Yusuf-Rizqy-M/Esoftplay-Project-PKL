<?php
if (!defined('_VALID_BBC')) exit('No direct script access allowed');

_func('download');

if (!empty($_POST['transfer'])) {
    if ($_POST['transfer'] == 'download') {
        $q = "SELECT email, name, phone, school, major, start_date, end_date FROM interns ORDER BY id DESC LIMIT 10";
        $r = $db->getAll($q);

        if (!empty($r)) {
            foreach ($r as $k => &$val) {
                if ($k == 0) {
                    $val['Keterangan'] = 'Format tanggal: YYYY-MM-DD (Contoh: 2025-10-06)';
                }
                if ($k == 1) {
                    $val['Keterangan'] = 'Email harus unik dan belum terdaftar di sistem.';
                }
            }
            download_excel('Sample_Intern_Data_'.date('Y-m-d'), $r);
            die();
        } else {
            echo msg('Maaf, tidak ada data intern yang bisa dijadikan sampel.', 'danger');
        }
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
                        $db->Execute("SET FOREIGN_KEY_CHECKS=0");
                        $success = 0;
                        foreach ($output as $i => $cells) {
                            if ($i == 1 || empty($cells['A'])) continue;

                            $email = strtolower(trim($cells['A']));
                            if (is_email($email)) {
                                $is_exist = $db->getOne("SELECT 1 FROM interns WHERE email='" . addslashes($email) . "'");
                                if (!$is_exist) {
                                    $user_id = $db->getOne("SELECT id FROM bbc_user WHERE username='" . addslashes($email) . "'");
                                    if (!$user_id) {
                                        $user_params = array(
                                            'username' => $email, 'name' => $cells['B'], 'email' => $email,
                                            'password' => 'intern123', 'group_ids' => array(3),
                                            'params'   => array('register_at' => date('Y-m-d H:i:s'))
                                        );
                                        $user_id = user_create($user_params);
                                    }
                                    if ($user_id) {
                                        $start_date = fix_excel_date_import($cells['F']);
                                        $end_date   = fix_excel_date_import($cells['G']);
                                        $status     = calculate_intern_status_import($start_date, $end_date);

                                        $q = "INSERT INTO interns SET user_id=$user_id, email='" . addslashes($email) . "', name='" . addslashes($cells['B']) . "', phone='" . addslashes($cells['C']) . "', school='" . addslashes($cells['D']) . "', major='" . addslashes($cells['E']) . "', start_date='$start_date', end_date='$end_date', status=$status, created=NOW()";
                                        if ($db->Execute($q)) $success++;
                                    }
                                }
                            }
                        }
                        $db->Execute("SET FOREIGN_KEY_CHECKS=1");
                        $msg = msg("Upload data berhasil dieksekusi. $success data berhasil diimport.", 'success');
                    } else {
                        $msg = msg('Maaf, format kolom file tidak sesuai dengan ketentuan. Mohon periksa lagi file nya.', 'danger');
                    }
                } else {
                    $msg = msg('Maaf, file yang anda upload tidak terbaca.', 'danger');
                }
            } else {
                $msg = msg('Mohon upload file dengan format yang benar (.xlsx)', 'danger');
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
$form_search->search->addInput('school', 'keyword');
$form_search->search->input->school->setTitle('School');
$form_search->search->input->school->addSearchField('school', false);
$form_search->search->addInput('start_date', 'dateinterval');
$form_search->search->input->start_date->setIsSearchRange();
$form_search->search->input->start_date->setTitle('Start Date');

$add_sql = $form_search->search->action();
echo $form_search->search->getForm();

$is_edit   = (!empty($_GET['id']) && is_numeric($_GET['id']));
$form_list = _lib('pea', 'interns');
$form_list->initRoll($add_sql . ' ORDER BY id DESC', 'id');
$form_list->roll->setDeleteTool(true);
$form_list->roll->setSaveTool(false);

$form_list->roll->addInput('name', 'sqllinks');
$form_list->roll->input->name->setLinks($Bbc->mod['circuit'] . '.interns_edit');
$form_list->roll->input->name->setTitle('Name');
$form_list->roll->addInput('email', 'sqlplaintext');
$form_list->roll->input->email->setTitle('Email');
$form_list->roll->addInput('school', 'sqlplaintext');
$form_list->roll->input->school->setTitle('School');
$form_list->roll->addInput('phone', 'sqlplaintext');
$form_list->roll->input->phone->setTitle('Phone');

$form_list->roll->addInput('period', 'sqlplaintext');
$form_list->roll->input->period->setTitle('Internship Period');
$form_list->roll->input->period->setFieldName('CONCAT(DATE_FORMAT(start_date,"%d %b %Y")," - ",DATE_FORMAT(IFNULL(end_date,start_date),"%d %b %Y")) AS period');

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

$form_list->roll->action();

ob_start();
include 'interns_edit.php';
$form_edit_content = ob_get_clean();

$tabs = array(
    'List Interns' => $form_list->roll->getForm(),
    ($is_edit ? 'Edit Intern' : 'Add Intern') => $form_edit_content
);
echo tabs($tabs, ($is_edit ? 2 : 1), 'tabs_interns');

function fix_excel_date_import($date_str) {
    if (empty($date_str)) return date('Y-m-d');
    $date_str = str_replace('/', '-', $date_str);
    $ts = strtotime($date_str);
    return ($ts === false) ? date('Y-m-d') : date('Y-m-d', $ts);
}

function calculate_intern_status_import($start, $end) {
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
        <?php echo icon('fa-file-excel-o') ?> Klik disini untuk Manage Data (Import/Download)
      </h4>
    </div>
    <div id="import_panel" class="panel-collapse collapse">
      <form action="" method="POST" enctype="multipart/form-data">
        <div class="panel-body">
          <div class="form-group">
            <label>Upload File Excel (.xlsx atau .xls)</label>
            <input type="file" name="excel" class="form-control" accept=".xlsx,.xls" />
            <div class="help-block">Pastikan susunan kolom sesuai dengan file sample.</div>
          </div>
        </div>
        <div class="panel-footer">
          <button type="submit" name="transfer" value="upload" class="btn btn-primary"><?php echo icon('fa-upload') ?> Upload Sekarang</button>
          <button type="submit" name="transfer" value="download" class="btn btn-default pull-right"><?php echo icon('fa-download') ?> Download Sample dari DB</button>
        </div>
      </form>
    </div>
  </div>
</div>