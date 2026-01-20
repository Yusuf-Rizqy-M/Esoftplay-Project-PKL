<?php
if (!defined('_VALID_BBC')) exit('No direct script access allowed');
_func('date');
_func('user');

if (isset($_GET['act']) && $_GET['act'] == 'sample_intern') {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment;filename="sample_import_intern.csv"');
    echo "email,name,phone,school,major,start_date,end_date\n";
    echo "choirulanam@gmail.com,Muhammad Choirul Anam,081234567890,SMK Raden Umar Said,PPLG,2025-10-06,2026-04-06\n";
    die();
}


$formSearch = _lib('pea', 'interns');
$formSearch->initSearch();

$formSearch->search->addInput('status_filter', 'select');
$formSearch->search->input->status_filter->setTitle('Status');
$formSearch->search->input->status_filter->setFieldName('id AS status_filter');
$formSearch->search->input->status_filter->addOption('-- All Status --', 'all');
$formSearch->search->input->status_filter->addOption('Active', 'active');
$formSearch->search->input->status_filter->addOption('Ended', 'ended');

$formSearch->search->addInput('name','keyword');
$formSearch->search->input->name->setTitle('Name');
$formSearch->search->input->name->addSearchField('name', false);

$formSearch->search->addInput('school','keyword');
$formSearch->search->input->school->setTitle('School');
$formSearch->search->input->school->addSearchField('school', false);

$formSearch->search->addInput('start_date','date');
$formSearch->search->input->start_date->setTitle('Start Date');

$formSearch->search->addInput('end_date','date');
$formSearch->search->input->end_date->setTitle('End Date');

$add_sql = $formSearch->search->action();
echo $formSearch->search->getForm();

$where = [];

if (!empty($_SESSION['search']['interns'])) {
    $s = $_SESSION['search']['interns'];

    if (!empty($s['search_status_filter'])) {
        if ($s['search_status_filter'] == 'active') {
            $where[] = "CURRENT_DATE BETWEEN start_date AND end_date";
        } elseif ($s['search_status_filter'] == 'coming_soon') {
            $where[] = "CURRENT_DATE < start_date";
        } elseif ($s['search_status_filter'] == 'ended') {
            $where[] = "CURRENT_DATE > end_date";
        }
    }

    if (!empty($s['search_name'])) {
        $where[] = "`name` LIKE '%{$s['search_name']}%'";
    }

    if (!empty($s['search_school'])) {
        $where[] = "`school` LIKE '%{$s['search_school']}%'";
    }

    if (!empty($s['search_start_date']) && !empty($s['search_end_date'])) {
        $filter_start = date('Y-m-d', strtotime(str_replace('/', '-', $s['search_start_date'])));
        $filter_end = date('Y-m-d', strtotime(str_replace('/', '-', $s['search_end_date'])));
        $where[] = "(start_date <= '{$filter_end}' AND end_date >= '{$filter_start}')";
    } 
    elseif (!empty($s['search_start_date'])) {
        $filter_start = date('Y-m-d', strtotime(str_replace('/', '-', $s['search_start_date'])));
        $where[] = "end_date >= '{$filter_start}'";
    }
    elseif (!empty($s['search_end_date'])) {
        $filter_end = date('Y-m-d', strtotime(str_replace('/', '-', $s['search_end_date'])));
        $where[] = "start_date <= '{$filter_end}'";
    }
}

$sqlWhere = $where ? 'WHERE '.implode(' AND ', $where) : 'WHERE 1';

$tabs = array();
$is_edit = (!empty($_GET['id']) && is_numeric($_GET['id'])) ? true : false;

$formList = _lib('pea', 'interns');
$formList->initRoll($sqlWhere . ' ORDER BY id DESC', 'id');
$formList->roll->setDeleteTool(true);
$formList->roll->setSaveTool(false);

$formList->roll->addInput('name','sqllinks');
$formList->roll->input->name->setLinks($Bbc->mod['circuit'].'.interns_edit');
$formList->roll->input->name->setTitle('Name');

$formList->roll->addInput('email','sqlplaintext');
$formList->roll->input->email->setTitle('Email');

$formList->roll->addInput('phone','sqlplaintext');
$formList->roll->input->phone->setTitle('Phone');

$formList->roll->addInput('school','sqlplaintext');
$formList->roll->input->school->setTitle('School');

$formList->roll->addInput('major','sqlplaintext');
$formList->roll->input->major->setTitle('Major');

$formList->roll->addInput('period', 'sqlplaintext');
$formList->roll->input->period->setTitle('Internship Period');
$formList->roll->input->period->setFieldName('CONCAT(DATE_FORMAT(start_date,"%d %b %Y")," - ",DATE_FORMAT(IFNULL(end_date,start_date),"%d %b %Y")) AS period');

$formList->roll->addInput('status', 'sqlplaintext');
$formList->roll->input->status->setTitle('Status');
$formList->roll->input->status->setFieldName('CASE WHEN CURRENT_DATE < start_date THEN "Coming Soon" WHEN CURRENT_DATE BETWEEN start_date AND end_date THEN "Active" ELSE "Ended" END as status');
$formList->roll->input->status->setDisplayFunction(function ($value) {
    $colors = [
        'Coming Soon' => '#007bff',
        'Active' => '#28a745',
        'Ended' => '#dc3545'
    ];
    $color = $colors[$value] ?? '#6c757d';
    return '<span class="label" style="background-color: '.$color.'; color: white; padding: 5px 12px; border-radius: 12px; font-size: 11px; font-weight: 600; display: inline-block;">'.$value.'</span>';
});

// ========== INI YANG DIUBAH - PAKAI setGetName() ==========
$formList->roll->addInput('task_link', 'sqllinks');
$formList->roll->input->task_link->setLinks('#'); // Set ke # agar tidak auto-generate
$formList->roll->input->task_link->setTitle('Tasks');
$formList->roll->input->task_link->setFieldName('id as task_link');
$formList->roll->input->task_link->setDisplayFunction(function($intern_id) {
    global $Bbc;
    
    // Generate URL dengan parameter force_intern_id
    $url = $Bbc->mod['circuit'] . '.interns_tasks_list&force_intern_id=' . intval($intern_id);
    
    // Return button HTML
    return '<a href="' . $url . '" class="btn btn-xs btn-primary">Lihat Pengerjaan</a>';
});

$formList->roll->action();

$formAdd = _lib('pea', 'interns');
$formAdd->initEdit($is_edit ? "WHERE id=".intval($_GET['id']) : "");

$formAdd->edit->addInput('header','header');
$formAdd->edit->input->header->setTitle($is_edit ? 'Edit Data Intern' : 'Add New Intern');

$formAdd->edit->addInput('name','text');
$formAdd->edit->input->name->setTitle('Name');
$formAdd->edit->input->name->setRequire();

$formAdd->edit->addInput('email','text');
$formAdd->edit->input->email->setTitle('Email');
$formAdd->edit->input->email->setRequire();

$formAdd->edit->addInput('phone','text');
$formAdd->edit->input->phone->setTitle('Phone');
$formAdd->edit->input->phone->setNumberFormat(true);
$formAdd->edit->input->phone->setExtra(' minlength="9" maxlength="14"');
$formAdd->edit->input->phone->setRequire();

$formAdd->edit->addInput('school','text');
$formAdd->edit->input->school->setTitle('School');

$formAdd->edit->addInput('major','text');
$formAdd->edit->input->major->setTitle('Major');

$formAdd->edit->addInput('start_date', 'dateinterval');
$formAdd->edit->input->start_date->setTitle('Internship Period');
$formAdd->edit->input->start_date->setCaption('Start Date');
$formAdd->edit->input->start_date->setEndDateField('end_date');
$formAdd->edit->input->start_date->setRequire();

$formAdd->edit->onSave('intern_before_save', '', false);
$formAdd->edit->onSave('intern_after_save', '', true);
$formAdd->edit->action();

$tabs['Interns'] = $formList->roll->getForm();
$tabs[$is_edit ? 'Edit Intern' : 'Add Intern'] = $formAdd->edit->getForm();
echo tabs($tabs, ($is_edit ? 2 : 1), 'tabs_interns');
?>

<style>
.loading-overlay{position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(255,255,255,.95);z-index:9999;display:flex;flex-direction:column;justify-content:center;align-items:center;text-align:center}
.loader-spinner{border:8px solid #f3f3f3;border-top:8px solid #3498db;border-radius:50%;width:60px;height:60px;animation:spin 1s linear infinite;margin-bottom:20px}
@keyframes spin{0%{transform:rotate(0deg)}100%{transform:rotate(360deg)}}
.loading-text{font-family:Arial,sans-serif;font-size:20px;color:#333;font-weight:bold;max-width:600px}
.success-list{margin-top:20px;text-align:left;max-height:200px;overflow-y:auto;padding:10px;background:#f0f8ff;border:1px solid #ccc;border-radius:6px;width:90%;max-width:500px}
.success-list li{margin-bottom:5px}
</style>

<div class="col-xs-12 no-both">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title" data-toggle="collapse" href="#import_panel" style="cursor:pointer;">
                <?php echo icon('fa-file-excel-o') ?> klik disini untuk import data intern dari CSV
            </h4>
        </div>
        <div id="import_panel" class="panel-collapse collapse">
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="panel-body">
                    <div class="form-group">
                        <label>Upload File CSV</label>
                        <input type="file" name="excel" class="form-control" accept=".csv" />
                        <div class="help-block">
                            Urutan kolom: email, name, phone, school, major, start_date, end_date.<br>
                            Download contoh: <a href="?mod=interns&act=sample_intern" style="text-decoration:underline;">di sini</a>
                        </div>
                    </div>
                </div>
                <div class="panel-footer">
                    <button type="submit" name="transfer" value="upload" class="btn btn-primary">
                        <?php echo icon('fa-upload') ?> Upload Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
function intern_before_save($intern_id) {
    global $db;
    
    $email = ''; 
    $start = ''; 
    $end = ''; 
    $name = '';
    
    foreach($_POST as $k => $v){
        if(strpos($k, 'email') !== false) $email = strtolower(trim(is_array($v) ? current($v) : $v));
        if(strpos($k, 'start_date') !== false && !is_array($v)) $start = $v;
        if(strpos($k, 'end_date') !== false && !is_array($v)) $end = $v;
        if(strpos($k, 'name') !== false) $name = trim(is_array($v) ? current($v) : $v);
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Gagal Simpan: Format email tidak valid!";
    }

    if(empty($start) || empty($end)){
        return "Gagal Simpan: Tanggal Mulai dan Tanggal Selesai harus diisi!";
    }
    
    if(strtotime($end) <= strtotime($start)){
        return "Gagal Simpan: Tanggal Selesai (".date('d-m-Y', strtotime($end)).") harus setelah Tanggal Mulai (".date('d-m-Y', strtotime($start)).")!";
    }

    $curr_id = !empty($_GET['id']) ? intval($_GET['id']) : 0;
    $is_exist = $db->getOne("SELECT id FROM interns WHERE email='".addslashes($email)."' AND id != $curr_id");
    if($is_exist){
        return "Gagal Simpan: Email '$email' sudah digunakan oleh intern lain!";
    }

    if($curr_id == 0){
        $user_id = 0;
        $user_check = $db->getOne("SELECT id FROM bbc_user WHERE username = '".addslashes($email)."'");
        
        if ($user_check) {
            $user_id = $user_check;
            $_SESSION['intern_temp_user_id'] = $user_id;
            return true;
        }
        
        $params = array(
            'username' => trim($email),
            'name'     => trim($name),
            'email'    => trim($email),
            'password' => password_hash('intern123', PASSWORD_DEFAULT),
            'params'   => ['_padding' => 1],
        );
        
        $user_id = user_create($params);
        
        if (!$user_id) {
            $error_msg = user_create_validate_msg();
            if (!empty($error_msg)) {
                return "Gagal Simpan: Gagal membuat BBC user - " . $error_msg;
            }
            return "Gagal Simpan: Gagal membuat akun baru di bbc_user! user_create() return 0";
        }
        
        $verify = $db->getOne("SELECT id FROM bbc_user WHERE id = " . intval($user_id));
        if (!$verify) {
            return "Gagal Simpan: user_create() return ID $user_id tapi user tidak ditemukan di database!";
        }
        
        $_SESSION['intern_temp_user_id'] = $user_id;
    }
    
    return true;
}

function intern_after_save($intern_id) {
    global $db;
    
    if (!empty($_SESSION['intern_temp_user_id']) && !empty($intern_id)) {
        $user_id = intval($_SESSION['intern_temp_user_id']);
        $db->Execute("UPDATE interns SET user_id = {$user_id} WHERE id = " . intval($intern_id));
        unset($_SESSION['intern_temp_user_id']);
    }
    
    return true;
}

if (!empty($_POST['transfer']) && $_POST['transfer'] == 'upload' && !empty($_FILES['excel']['tmp_name'])) {
    global $db;
    
    $db->Execute("SET FOREIGN_KEY_CHECKS=0");
    
    $file = $_FILES['excel']['tmp_name'];
    $handle = fopen($file, "r");
    
    if ($handle === false) {
        echo '<div class="alert alert-danger">Gagal membuka file CSV!</div>';
        $db->Execute("SET FOREIGN_KEY_CHECKS=1");
    } else {
        $success = $fail = 0;
        $row = 0;
        $messages = [];
        $success_names = [];
        
        while (($data = fgetcsv($handle, 1000, ",")) !== false) {
            $row++;
            
            if ($row == 1) continue;
            if (count($data) < 2) continue;
            
            $email = strtolower(trim($data[0] ?? ''));
            $name = trim($data[1] ?? '');
            $phone = trim($data[2] ?? '');
            $school = trim($data[3] ?? '');
            $major = trim($data[4] ?? '');
            $start = trim($data[5] ?? '');
            $end = trim($data[6] ?? '');
            
            if (empty($email) || empty($name)) {
                $messages[] = '<li class="text-danger">Baris '.$row.': Skip - email atau name kosong</li>';
                $fail++;
                continue;
            }
            
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $messages[] = '<li class="text-danger">Baris '.$row.': Skip - Format email tidak valid</li>';
                $fail++;
                continue;
            }
            
            if ($db->getOne("SELECT id FROM interns WHERE email = '".addslashes($email)."'")) {
                $messages[] = '<li class="text-danger">Baris '.$row.': Skip - Email <b>'.$email.'</b> sudah terdaftar</li>';
                $fail++;
                continue;
            }
            
            $start_ts = $start ? strtotime($start) : false;
            $end_ts = $end ? strtotime($end) : false;
            
            if ($start_ts && $end_ts && $end_ts <= $start_ts) {
                $messages[] = '<li class="text-danger">Baris '.$row.': Skip - End Date harus setelah Start Date</li>';
                $fail++;
                continue;
            }
            
            $start_sql = $start_ts ? "'".date('Y-m-d', $start_ts)."'" : "NULL";
            $end_sql = $end_ts ? "'".date('Y-m-d', $end_ts)."'" : "NULL";
            
            $user_id = 0;
            $user_check = $db->getOne("SELECT id FROM bbc_user WHERE username = '".addslashes($email)."'");
            
            if ($user_check) {
                $user_id = $user_check;
            } else {
                $params = array(
                    'username' => $email,
                    'name'     => $name,
                    'email'    => $email,
                    'password' => password_hash('intern123', PASSWORD_DEFAULT),
                    'params'   => ['_padding' => 1],
                );
                
                $user_id = user_create($params);
                
                if (!$user_id) {
                    $messages[] = '<li class="text-danger">Baris '.$row.': Gagal membuat user menggunakan user_create()</li>';
                    $fail++;
                    continue;
                }
            }
            
            $user_id_sql = $user_id > 0 ? $user_id : "NULL";
            
            $q = "INSERT INTO interns
                  (email, name, phone, school, major, start_date, end_date, user_id, created, updated)
                  VALUES
                  ('".addslashes($email)."', '".addslashes($name)."', '".addslashes($phone)."',
                   '".addslashes($school)."', '".addslashes($major)."',
                   $start_sql, $end_sql, $user_id_sql, NOW(), NOW())";
            
            if ($db->Execute($q)) {
                $intern_id = $db->insert_ID();
                $messages[] = '<li class="text-success">Baris '.$row.': <b>'.$name.'</b> berhasil ditambahkan (User ID: '.$user_id.', Intern ID: '.$intern_id.')</li>';
                $success_names[] = $name;
                $success++;
            } else {
                $messages[] = '<li class="text-danger">Baris '.$row.': Gagal insert intern - ' . htmlspecialchars($db->ErrorMsg()) . '</li>';
                $fail++;
            }
        }
        
        fclose($handle);
        $db->Execute("SET FOREIGN_KEY_CHECKS=1");
        
        if ($fail > 0) {
            echo '<div class="alert alert-danger" id="import-error-alert" style="margin-top:20px;"><h4>Hasil Import:</h4><ul>';
            foreach($messages as $msg) echo $msg;
            echo '</ul><button type="button" class="btn btn-danger" onclick="closeErrorAndOpenPanel()">Tutup & Perbaiki</button></div>';
            
            echo '<script>
            function closeErrorAndOpenPanel() {
                document.getElementById("import-error-alert").style.display = "none";
                if(typeof jQuery !== "undefined") {
                    jQuery("#import_panel").collapse("show");
                }
                setTimeout(function() {
                    var panel = document.getElementById("import_panel");
                    if(panel) {
                        panel.scrollIntoView({ behavior: "smooth", block: "start" });
                    }
                }, 300);
            }
            </script>';
        }
        
        if ($success > 0) {
            $redirect_url = $_SERVER['PHP_SELF'] . (isset($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : '');
            ?>
<div class="loading-overlay">
    <div class="loader-spinner"></div>
    <div class="loading-text">
        Import Berhasil!<br>
        <small>Sedang memperbarui data...</small>
    </div>
    <div class="success-list">
        <strong>Data baru yang berhasil ditambahkan (<?php echo $success; ?>):</strong><br>
        <?php if (count($success_names) <= 10): ?>
        <ul>
        <?php foreach ($success_names as $name): ?>
            <li><?php echo htmlspecialchars($name); ?></li>
        <?php endforeach; ?>
        </ul>
        <?php else: ?>
        <ul>
        <?php for ($i = 0; $i < 10; $i++): ?>
            <li><?php echo htmlspecialchars($success_names[$i]); ?></li>
        <?php endfor; ?>
        </ul>
        <p style="margin-top: 10px;">+ <?php echo ($success - 10); ?> data lainnya</p>
        <?php endif; ?>
    </div>
</div>
<script type="text/javascript">
setTimeout(function() {
    window.location.href = "<?php echo $redirect_url; ?>";
}, 7000);
</script>
<?php
        }
    }
}
?>

<script>
(function() {
    function setupDateValidation() {
        if (typeof jQuery === 'undefined' || typeof jQuery.fn.datepicker === 'undefined') {
            setTimeout(setupDateValidation, 200);
            return;
        }
        
        var $inputs = jQuery('input[type="text"], input[type="date"]');
        var $startDate = null;
        var $endDate = null;
        
        $inputs.each(function() {
            var name = jQuery(this).attr('name') || '';
            if (name.indexOf('start_date') !== -1 && name.indexOf('search') === -1) {
                $startDate = jQuery(this);
            }
            if (name.indexOf('end_date') !== -1 && name.indexOf('search') === -1) {
                $endDate = jQuery(this);
            }
        });
        
        if (!$startDate || !$endDate) {
            return;
        }
        
        console.log('Date inputs found:', $startDate.attr('name'), $endDate.attr('name'));
        
        $startDate.on('changeDate change', function() {
            var startVal = $startDate.val();
            if (startVal) {
                var startDate = new Date(startVal);
                var minEndDate = new Date(startDate);
                minEndDate.setDate(minEndDate.getDate() + 1);
                
                $endDate.datepicker('setStartDate', minEndDate);
                console.log('End date minimum set to:', minEndDate);
                
                var endVal = $endDate.val();
                if (endVal) {
                    var endDate = new Date(endVal);
                    if (endDate <= startDate) {
                        $endDate.val('');
                        $endDate.datepicker('update', '');
                    }
                }
            } else {
                $endDate.datepicker('setStartDate', null);
            }
        });
        
        if ($startDate.val()) {
            $startDate.trigger('changeDate');
        }
    }
    
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', setupDateValidation);
    } else {
        setupDateValidation();
    }
    
    if (typeof jQuery !== 'undefined') {
        jQuery(document).ready(function() {
            jQuery('a[data-toggle="tab"]').on('shown.bs.tab', function() {
                setTimeout(setupDateValidation, 300);
            });
        });
    }
})();
</script>