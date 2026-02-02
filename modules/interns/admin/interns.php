<?php
if (!defined('_VALID_BBC')) exit('No direct script access allowed');

_func('download');

// ========== 1. HANDLE SAMPLE EXCEL DOWNLOAD ==========
if (@$_GET['act'] == 'sample_intern') {
  $sample_data = array(
    array(
      'email'      => 'choirulanam@gmail.com',
      'name'       => 'Muhammad Choirul Anam',
      'phone'      => '081234567890',
      'school'     => 'SMK Raden Umar Said',
      'major'      => 'PPLG',
      'start_date' => '2025-10-06',
      'end_date'   => '2026-04-06'
    )
  );
  download_excel('sample_import_intern', $sample_data, 'Intern Data');
  die();
}

// ========== 2. SEARCH FORM (PEA) ==========
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

// ========== 3. INTERNS LIST FORM (PEA) ==========
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
    if (empty($value) || !isset($status_map[$value])) {
        return '<span class="label" style="background-color: #6c757d; color: white; padding: 5px 12px; border-radius: 12px; font-size: 11px; font-weight: 600;">Unknown</span>';
    }
    $status = $status_map[$value];
    return '<span class="label" style="background-color: ' . $status['color'] . '; color: white; padding: 5px 12px; border-radius: 12px; font-size: 11px; font-weight: 600;">' . $status['label'] . '</span>';
});

$form_list->roll->addInput('task_link', 'sqllinks');
$form_list->roll->input->task_link->setTitle('Tasks');
$form_list->roll->input->task_link->setFieldName('id AS task_link');
$form_list->roll->input->task_link->setDisplayFunction(function ($intern_id) {
  global $Bbc;
  $url = $Bbc->mod['circuit'] . '.interns_tasks_list&force_intern_id=' . intval($intern_id);
  return '<a href="' . $url . '" class="btn btn-xs btn-primary">Lihat Pengerjaan</a>';
});

$form_list->roll->action();

// LOAD EDIT FORM
ob_start();
include 'interns_edit.php';
$form_edit_content = ob_get_clean();

// TAMPILKAN TABS
$tabs = array(
  'List Interns' => $form_list->roll->getForm(),
  ($is_edit ? 'Edit Intern' : 'Add Intern') => $form_edit_content
);
echo tabs($tabs, ($is_edit ? 2 : 1), 'tabs_interns');


// ========== 4. IMPORT EXCEL LOGIC (FIXED DETAIL ERROR) ==========
if (!empty($_POST['transfer']) && $_POST['transfer'] == 'upload') {
  $msg = '';
  if (!empty($_FILES['excel']['tmp_name']) && is_uploaded_file($_FILES['excel']['tmp_name'])) {
    
    // Validasi MIME Type
    $mimes = array('application/vnd.ms-excel','text/xls','text/xlsx','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    if (in_array($_FILES['excel']['type'], $mimes)) {
        
        // Baca File menggunakan Lib Excel Framework
        $excel_lib = _lib('excel')->read($_FILES['excel']['tmp_name']);
        $data      = $excel_lib->sheet(1)->fetch(); 

        if (!empty($data) && is_array($data) && count($data) > 1) {
            
            $db->Execute("SET FOREIGN_KEY_CHECKS=0");
            $success = 0;
            $failed  = 0;
            $errors  = [];

            foreach ($data as $i => $cells) {
                // SKIP HEADER: Baris 1 atau jika kolom A adalah 'Email'
                if ($i == 1 || strtolower(trim($cells['A'])) == 'email') continue;

                $email = strtolower(trim($cells['A']));
                $name  = trim($cells['B']);

                // Validasi Email
                if (is_email($email)) {
                    // Cek duplikat di tabel interns
                    $is_exist = $db->getOne("SELECT 1 FROM interns WHERE email='".addslashes($email)."'");
                    
                    if (!$is_exist) {
                        // 1. Handle User Creation (BBC User)
                        $user_id = $db->getOne("SELECT id FROM bbc_user WHERE username='".addslashes($email)."'");
                        
                        if (!$user_id) {
                            $user_params = array(
                                'username'  => $email,
                                'name'      => $name,
                                'email'     => $email,
                                'password'  => 'intern123',
                                'group_ids' => array(3), // Group ID Intern
                                'params'    => array('register_at' => date('Y-m-d H:i:s'))
                            );
                            $user_id = user_create($user_params);
                        }

                        if ($user_id) {
                            // 2. Handle Tanggal & Status
                            $start_date = fix_excel_date_import($cells['F']);
                            $end_date   = fix_excel_date_import($cells['G']);
                            $status     = calculate_intern_status_import($start_date, $end_date);

                            $q = "INSERT INTO interns SET 
                                user_id    = $user_id, 
                                email      = '".addslashes($email)."', 
                                name       = '".addslashes($name)."', 
                                phone      = '".addslashes($cells['C'])."', 
                                school     = '".addslashes($cells['D'])."', 
                                major      = '".addslashes($cells['E'])."', 
                                start_date = '$start_date', 
                                end_date   = '$end_date', 
                                status     = $status, 
                                created    = NOW()";
                            
                            if($db->Execute($q)) {
                                $success++;
                            } else {
                                $failed++;
                                $errors[] = "Baris $i: DB Error - " . $db->ErrorMsg();
                            }
                        } else {
                            $failed++;
                            $errors[] = "Baris $i: Gagal membuat User ID BBC.";
                        }
                    } else {
                        // --- PERBAIKAN DISINI ---
                        // Tambahkan pesan ke array errors agar muncul di detail
                        $failed++; 
                        $errors[] = "Baris $i: Email <b>$email</b> sudah terdaftar.";
                    }
                } else {
                    $failed++;
                    $errors[] = "Baris $i: Format email salah ($email).";
                }
            }
            $db->Execute("SET FOREIGN_KEY_CHECKS=1");

            // TAMPILKAN HASIL
            if ($success > 0) {
                // Jika ada error tapi ada juga yang sukses, tampilkan warning/success info
                $msg_type = ($failed > 0) ? 'warning' : 'success';
                $extra_info = ($failed > 0) ? "<br>Gagal: $failed data (Lihat detail bawah)." : "";
                
                echo msg("Import Selesai! Berhasil: $success. $extra_info", $msg_type);
                
                // Jika ada error, tampilkan detailnya di bawah pesan sukses
                if (!empty($errors)) {
                    $err_str = implode("<br>", array_slice($errors, 0, 10)); // Batasi 10 error agar tidak kepanjangan
                    echo msg("<b>Detail Data Gagal:</b><br>" . $err_str, 'danger');
                }

            } else {
                // Jika GAGAL SEMUA (0 success)
                if (!empty($errors)) {
                    $err_str = implode("<br>", array_slice($errors, 0, 10));
                    echo msg("Gagal mengimport data.<br><b>Detail:</b><br>" . $err_str, 'danger');
                } else {
                    echo msg("Gagal mengimport data. Tidak ada data valid yang ditemukan.", 'danger');
                }
            }

        } else {
             echo msg('File Excel kosong atau format tidak terbaca.', 'danger');
        }
    } else {
        echo msg('Format file harus .xlsx atau .xls', 'danger');
    }
  } else {
      echo msg('Mohon pilih file excel.', 'danger');
  }
}

// Helper: Fix Date format
function fix_excel_date_import($date_str) {
    if (empty($date_str)) return date('Y-m-d');
    $date_str = str_replace('/', '-', $date_str);
    $ts = strtotime($date_str);
    if ($ts === false || $ts < strtotime('1980-01-01')) return date('Y-m-d');
    return date('Y-m-d', $ts);
}

// Helper: Calculate Status
function calculate_intern_status_import($start, $end) {
  $curr = date('Y-m-d');
  if ($curr < $start) return 3; // Coming Soon
  if ($curr <= $end) return 1;  // Active
  return 2;                     // Ended
}
?>

<div class="col-xs-12 no-both">
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title" data-toggle="collapse" href="#import_panel" style="cursor:pointer;">
        <?php echo icon('fa-file-excel-o') ?> Klik disini untuk import data intern dari Excel
      </h4>
    </div>
    <div id="import_panel" class="panel-collapse collapse">
      <form action="" method="POST" enctype="multipart/form-data">
        <div class="panel-body">
          <div class="form-group">
            <label>Upload File Excel (.xlsx atau .xls)</label>
            <input type="file" name="excel" class="form-control" accept=".xlsx,.xls" required />
            <div class="help-block">Format kolom wajib: Email, Name, Phone, School, Major, Start Date, End Date</div>
          </div>
        </div>
        <div class="panel-footer">
          <button type="submit" name="transfer" value="upload" class="btn btn-primary"><?php echo icon('fa-upload') ?> Upload Sekarang</button>
          <a href="<?php echo $Bbc->mod['circuit'].'.interns&act=sample_intern';?>" class="btn btn-default pull-right"><?php echo icon('fa-download') ?> Download Sample</a>
        </div>
      </form>
    </div>
  </div>
</div>