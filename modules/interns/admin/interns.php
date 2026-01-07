<?php
if (!defined('_VALID_BBC')) exit('No direct script access allowed');
_func('date');
_func('user'); // Load user functions

// Handle sample CSV download
if (isset($_GET['act']) && $_GET['act'] == 'sample_intern') {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment;filename="sample_import_intern.csv"');
    echo "email,name,school,major,start_date,end_date\n";
    echo "choirulanam@gmail.com,Muhammad Choirul Anam,SMK Raden Umar Said,PPLG,2025-10-06,2026-03-28\n";
    echo "azzanisham@gmail.com,Azzan Isham Allawiy,SMK Raden Umar Said,PPLG,2025-11-01,2026-04-30\n";
    die();
}

// Search Form
$formSearch = _lib('pea', 'interns');
$formSearch->initSearch();
$formSearch->search->addInput('name','keyword');
$formSearch->search->input->name->setTitle('Name');
$formSearch->search->input->name->addSearchField('name', false);
$add_sql = $formSearch->search->action();
echo $formSearch->search->getForm();

// Tabs
$tabs = array(
    'Interns' => '',
    'Add Intern' => ''
);

// Add Intern Form
$formAdd = _lib('pea', 'interns');
$formAdd->initEdit();
$formAdd->edit->addInput('header','header');
$formAdd->edit->input->header->setTitle('Add New Intern');

$formAdd->edit->addInput('name','text');
$formAdd->edit->input->name->setTitle('Name');
$formAdd->edit->input->name->setRequire();

$formAdd->edit->addInput('email','text');
$formAdd->edit->input->email->setTitle('Email');
$formAdd->edit->input->email->setRequire();

$formAdd->edit->addInput('school','text');
$formAdd->edit->input->school->setTitle('School');

$formAdd->edit->addInput('major','text');
$formAdd->edit->input->major->setTitle('Major');

$formAdd->edit->addInput('start_date', 'dateinterval');
$formAdd->edit->input->start_date->setTitle('Internship Period');
$formAdd->edit->input->start_date->setCaption('Start Date');
$formAdd->edit->input->start_date->setEndDateField('end_date');
$formAdd->edit->input->start_date->setRequire();
$formAdd->edit->input->end_date->setTitle('End Date');
$formAdd->edit->input->end_date->setRequire();

// onSave callback - BEFORE save (untuk validasi dan create user)
// Signature: onSave($function_name, $args, $call_after_saved)
$formAdd->edit->onSave('intern_before_save', '', false);

// onSave callback - AFTER save (untuk update user_id)
$formAdd->edit->onSave('intern_after_save', '', true);

$formAdd->edit->action();
$tabs['Add Intern'] = $formAdd->edit->getForm();

// List Interns
$formList = _lib('pea', 'interns');
$formList->initRoll($add_sql . ' ORDER BY id DESC', 'id');
$formList->roll->setDeleteTool(false);
$formList->roll->setSaveTool(false);

// Tambahkan kolom name untuk ditampilkan sebagai username BBC
$formList->roll->addInput('intern_name','sqlplaintext');
$formList->roll->input->intern_name->setTitle('Username BBC');
$formList->roll->input->intern_name->setFieldName('name AS intern_name');
$formList->roll->input->intern_name->setPlaintext(true);

$formList->roll->addInput('id','sqlplaintext');
$formList->roll->input->id->setDisplayColumn(false);

$formList->roll->addInput('name','text');
$formList->roll->input->name->setTitle('Name');
$formList->roll->input->name->setPlaintext(true);

$formList->roll->addInput('email','text');
$formList->roll->input->email->setTitle('Email');
$formList->roll->input->email->setPlaintext(true);

$formList->roll->addInput('school','text');
$formList->roll->input->school->setTitle('School');
$formList->roll->input->school->setPlaintext(true);

$formList->roll->addInput('major','text');
$formList->roll->input->major->setTitle('Major');
$formList->roll->input->major->setPlaintext(true);

$formList->roll->addInput('period', 'sqlplaintext');
$formList->roll->input->period->setTitle('Internship Period');
$formList->roll->input->period->setFieldName('CONCAT(DATE_FORMAT(start_date,"%Y %b %d")," -> ",DATE_FORMAT(IFNULL(end_date,start_date),"%Y %b %d")) AS period');

$formList->roll->addInput('created','sqlplaintext');
$formList->roll->input->created->setTitle('Created');
$formList->roll->input->created->setPlaintext(true);

$formList->roll->action();
$formList->roll->onDelete(true);
$tabs['Interns'] = $formList->roll->getForm();

echo tabs($tabs, 1, 'tabs_interns');

// ========== CALLBACK FUNCTIONS ==========

// BEFORE SAVE: Validasi dan Create BBC User
function intern_before_save($intern_id) {
    global $db;
    
    $email = trim($_POST['add_email'] ?? '');
    $name = trim($_POST['add_name'] ?? '');
    
    // Validasi format email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Error: Format email tidak valid!";
    }
    
    // Cek apakah email sudah terdaftar di interns (hanya untuk new record)
    if (empty($_POST['add_id'])) {
        $existing_intern = $db->getOne("SELECT id FROM interns WHERE email = '".addslashes($email)."'");
        if ($existing_intern) {
            return "Error: Email sudah terdaftar di interns! Gunakan email lain.";
        }
    }
    
    // Validasi tanggal
    if (!empty($_POST['add_start_date']) && !empty($_POST['add_end_date'])) {
        if (strtotime($_POST['add_end_date']) < strtotime($_POST['add_start_date'])) {
            return "Error: End Date tidak boleh lebih kecil dari Start Date!";
        }
    }
    
    // CREATE BBC USER (hanya untuk new record)
    if (empty($_POST['add_id'])) {
        $user_id = 0;
        
        // Cek apakah user sudah ada
        $user_check = $db->getOne("SELECT id FROM bbc_user WHERE username = '".addslashes($email)."'");
        
        if ($user_check) {
            $user_id = $user_check;
            $_SESSION['intern_temp_user_id'] = $user_id;
            return true;
        }
        
        // Buat user baru menggunakan user_create()
        $params = array(
            'username' => trim($email),
            'name'     => trim($name),
            'email'    => trim($email),
            'params'   => ['_padding' => 1],
        );
        
        $user_id = user_create($params);
        
        // Debug: Cek hasil user_create
        if (!$user_id) {
            // Cek error message dari user_create_validate_msg
            $error_msg = user_create_validate_msg();
            if (!empty($error_msg)) {
                return "Error: Gagal membuat BBC user - " . $error_msg;
            }
            return "Error: Gagal membuat akun baru di bbc_user! user_create() return 0";
        }
        
        // Verify user actually created
        $verify = $db->getOne("SELECT id FROM bbc_user WHERE id = " . intval($user_id));
        if (!$verify) {
            return "Error: user_create() return ID $user_id tapi user tidak ditemukan di database!";
        }
        
        // Simpan user_id ke SESSION untuk diambil di after_save
        $_SESSION['intern_temp_user_id'] = $user_id;
    }
  
    return true; // Lanjutkan proses save
}

// AFTER SAVE: Update user_id di intern
function intern_after_save($intern_id) {
    global $db;
    
    // Ambil user_id dari SESSION
    if (!empty($_SESSION['intern_temp_user_id']) && !empty($intern_id)) {
        $user_id = intval($_SESSION['intern_temp_user_id']);
        
        // Update intern dengan user_id
        $q = "UPDATE interns SET user_id = {$user_id} WHERE id = " . intval($intern_id);
        $db->Execute($q);
        
        // Hapus dari SESSION
        unset($_SESSION['intern_temp_user_id']);
    }
    
    return true;
}
?>

<!-- IMPORT CSV SECTION -->
<div class="col-xs-12 no-both">
<div class="panel-group" id="accordionadd_company">
<div class="panel panel-default">
<div class="panel-heading">
<h4 aria-expanded="false" class="panel-title collapsed" data-parent="#accordionadd_company" data-toggle="collapse" href="#pea_isHideToolOnadd_r_params" style="cursor: pointer;">
    Klik Disini Untuk Import Data Intern From Excel (CSV)
</h4>
</div>
<div aria-expanded="false" class="panel-collapse collapse" id="pea_isHideToolOnadd_r_params">
<form action="" method="POST" class="form" role="form" enctype="multipart/form-data">
<div class="panel-body">
<div class="form-group">
<label>Upload File</label>
<input type="file" name="excel" class="form-control" accept=".csv" />
<div class="help-block">
    Upload file daftar intern dalam format CSV. Download contoh file di
<a href="?mod=interns&act=sample_intern">sini</a>
    (urutan kolom: email,name,school,major,start_date,end_date).
</div>
</div>
</div>
<div class="panel-footer">
<button type="submit" name="transfer" value="upload" class="btn btn-default">
<?php echo icon('fa-upload') ?> Upload Data
</button>
</div>
</form>
</div>
</div>
</div>
</div>

<?php
// HANDLE CSV IMPORT WITH AUTO USER CREATION USING user_create()
if (!empty($_POST['transfer']) && $_POST['transfer'] == 'upload' && !empty($_FILES['excel']['tmp_name'])) {
    global $db;
    
    // DISABLE FOREIGN KEY CHECKS TEMPORARILY
    $db->Execute("SET FOREIGN_KEY_CHECKS=0");
    
    $file = $_FILES['excel']['tmp_name'];
    $handle = fopen($file, "r");
    
    if ($handle === false) {
        echo '<div class="alert alert-danger">Gagal membuka file CSV!</div>';
        $db->Execute("SET FOREIGN_KEY_CHECKS=1"); // Re-enable
        exit;
    }
    
    $success = $fail = 0;
    $row = 0;
    $messages = [];
    $success_names = [];
    
    while (($data = fgetcsv($handle, 1000, ",")) !== false) {
        $row++;
        
        // Skip header row
        if ($row == 1) continue;
        
        // Skip empty rows
        if (count($data) < 2) continue;
        
        $email = trim($data[0] ?? '');
        $name = trim($data[1] ?? '');
        $school = trim($data[2] ?? '');
        $major = trim($data[3] ?? '');
        $start = trim($data[4] ?? '');
        $end = trim($data[5] ?? '');
        
        // Validasi email dan name tidak boleh kosong
        if (empty($email) || empty($name)) {
            $messages[] = '<li class="text-danger">Baris '.$row.': Skip - email atau name kosong</li>';
            $fail++;
            continue;
        }
        
        // Validasi format email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $messages[] = '<li class="text-danger">Baris '.$row.': Skip - Format email tidak valid</li>';
            $fail++;
            continue;
        }
        
        // Cek apakah email sudah terdaftar
        if ($db->getOne("SELECT id FROM interns WHERE email = '".addslashes($email)."'")) {
            $messages[] = '<li class="text-danger">Baris '.$row.': Skip - Email <b>'.$email.'</b> sudah terdaftar</li>';
            $fail++;
            continue;
        }
        
        // Validasi tanggal
        $start_ts = $start ? strtotime($start) : false;
        $end_ts = $end ? strtotime($end) : false;
        
        if ($start_ts && $end_ts && $end_ts < $start_ts) {
            $messages[] = '<li class="text-danger">Baris '.$row.': Skip - End Date lebih kecil dari Start Date</li>';
            $fail++;
            continue;
        }
        
        $start_sql = $start_ts ? "'".date('Y-m-d', $start_ts)."'" : "NULL";
        $end_sql = $end_ts ? "'".date('Y-m-d', $end_ts)."'" : "NULL";
        
        // AUTO CREATE USER MENGGUNAKAN user_create()
        $user_id = 0;
        
        // Cek apakah user dengan username (email) sudah ada
        $user_check = $db->getOne("SELECT id FROM bbc_user WHERE username = '".addslashes($email)."'");
        
        if ($user_check) {
            // User sudah ada
            $user_id = $user_check;
        } else {
            // Buat user baru menggunakan user_create()
            $params = array(
                'username' => $email,
                'password' => '',
                'name'     => $name,
                'email'    => $email,
                'params'   => array('dummy' => '1'),
                'group_ids' => ''
            );
            
            $user_id = user_create($params);
            pr($user_id);
            if (!$user_id) {
                $messages[] = '<li class="text-danger">Baris '.$row.': Gagal membuat user menggunakan user_create()</li>';
                $fail++;
                continue;
            }
        }
        
        // Insert intern data dengan user_id
        $user_id_sql = $user_id > 0 ? $user_id : "NULL";
        
        $q = "INSERT INTO interns
              (email, name, school, major, start_date, end_date, user_id, created, updated)
              VALUES
              ('".addslashes($email)."', '".addslashes($name)."',
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
    
    // RE-ENABLE FOREIGN KEY CHECKS
    $db->Execute("SET FOREIGN_KEY_CHECKS=1");
    
    echo '<div class="alert alert-info"><h4>Hasil Import:</h4><ul>';
    foreach ($messages as $msg) {
        echo $msg;
    }
    echo '</ul>';
    echo '<strong>Selesai!</strong> Berhasil: <span class="text-success">'.$success.'</span> | Gagal: <span class="text-danger">'.$fail.'</span></div>';
    
    if ($success > 0) {
        $redirect_url = $_SERVER['PHP_SELF'] . (isset($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : '');
?>
<style>
.loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.95);
    z-index: 9999;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    text-align: center;
}
.loader-spinner {
    border: 8px solid #f3f3f3;
    border-top: 8px solid #3498db;
    border-radius: 50%;
    width: 60px;
    height: 60px;
    animation: spin 1.5s linear infinite;
    margin-bottom: 20px;
}
.loading-text {
    font-family: Arial, sans-serif;
    font-size: 20px;
    color: #333;
    font-weight: bold;
    max-width: 600px;
}
.success-list {
    margin-top: 20px;
    text-align: left;
    max-height: 200px;
    overflow-y: auto;
    padding: 10px;
    background: #f0f8ff;
    border: 1px solid #ccc;
    border-radius: 6px;
    width: 90%;
    max-width: 500px;
}
.success-list li {
    margin-bottom: 5px;
}
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>
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
?>