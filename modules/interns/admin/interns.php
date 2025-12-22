
<?php
if (!defined('_VALID_BBC')) exit('No direct script access allowed');
_func('date');
if (isset($_GET['act']) && $_GET['act'] == 'sample_intern') {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment;filename="sample_import_intern.csv"');
    echo "email,name,school,major,start_date,end_date\n";
    echo "choirulanam@gmail.com,Muhammad Choirul Anam,SMK Raden Umar Said,PPLG,2025-10-06,2026-03-28\n";
    echo "azzanisham@gmail.com,Azzan Isham Allawiy,SMK Raden Umar Said,PPLG,2025-11-01,2026-04-30\n";
    die();
}
$formSearch = _lib('pea', 'interns');
$formSearch->initSearch();
$formSearch->search->addInput('name','keyword');
$formSearch->search->input->name->setTitle('Name');
$formSearch->search->input->name->addSearchField('name', false);
$add_sql = $formSearch->search->action();
echo $formSearch->search->getForm();
$tabs = array(
    'Interns' => '',
    'Add Intern' => ''
);
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
$formAdd->edit->onSave(function($rows) use ($db){
    $email = trim($rows['email']);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Error: Format email tidak valid!";
    }
    $existing_id = $db->getOne("SELECT id FROM interns WHERE email = '{$db->escape($email)}'");
    if ($existing_id) {
        $current_id = (is_numeric($rows) && is_int($rows)) ? $rows : ($rows['id'] ?? 0);
        if ($current_id != $existing_id) {
            return "Error: Email sudah terdaftar di interns! Gunakan email lain.";
        }
    }
    if (!empty($rows['start_date']) && !empty($rows['end_date'])) {
        if (strtotime($rows['end_date']) < strtotime($rows['start_date'])) {
            return "Error: End Date tidak boleh lebih kecil dari Start Date!";
        }
    }
    $user_id = 0;
    $user_check = $db->getOne("SELECT id FROM bbc_user WHERE username = '{$db->escape($email)}'");
    if ($user_check) {
        $user_id = $user_check;
    } else {
        $password = password_hash(bin2hex(random_bytes(8)), PASSWORD_DEFAULT);
        $q = "INSERT INTO bbc_user (username, password, active, created) 
              VALUES ('{$db->escape($email)}', '{$db->escape($password)}', 1, NOW())";
        if ($db->Execute($q)) {
            $user_id = $db->insertID();
        } else {
            return "Error: Gagal membuat akun baru di bbc_user!";
        }
    }
    $rows['user_id'] = $user_id;
    return true;
});
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
$formAdd->edit->action();
$tabs['Add Intern'] = $formAdd->edit->getForm();
$formList = _lib('pea', 'interns');
$formList->initRoll($add_sql . ' ORDER BY id DESC', 'id');
$formList->roll->setDeleteTool(false);
$formList->roll->setSaveTool(false);

$formList->roll->addInput('user_id','selecttable');
$formList->roll->input->user_id->setTitle('Username BBC');
$formList->roll->input->user_id->setPlaintext(true);
$formList->roll->input->user_id->setReferenceTable('bbc_user');
$formList->roll->input->user_id->setReferenceField('username', 'id');

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
?>
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
if (!empty($_POST['transfer']) && $_POST['transfer'] == 'upload' && !empty($_FILES['excel']['tmp_name'])) {
    global $db;
    $file = $_FILES['excel']['tmp_name'];
    $handle = fopen($file, "r");
    if ($handle === false) {
        echo '<div class="alert alert-danger">Gagal membuka file CSV!</div>';
        exit;
    }
    $success = $fail = 0;
    $row = 0;
    $messages = [];
    $success_names = [];
    while (($data = fgetcsv($handle, 1000, ",")) !== false) {
        $row++;
        if ($row == 1) continue;
        if (count($data) < 2) continue;
        $email = trim($data[0] ?? '');
        $name = trim($data[1] ?? '');
        $school = trim($data[2] ?? '');
        $major = trim($data[3] ?? '');
        $start = trim($data[4] ?? '');
        $end = trim($data[5] ?? '');
        if (empty($email) || empty($name)) {
            $messages[] = '<li class="text-danger">Baris '.$row.': Skip - email atau name kosong</li>';
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
        if ($start_ts && $end_ts && $end_ts < $start_ts) {
            $messages[] = '<li class="text-danger">Baris '.$row.': Skip - End Date lebih kecil dari Start Date</li>';
            $fail++;
            continue;
        }
        $start_sql = $start_ts ? "'".date('Y-m-d', $start_ts)."'" : "NULL";
        $end_sql = $end_ts ? "'".date('Y-m-d', $end_ts)."'" : "NULL";
        $q = "INSERT INTO interns
              (email, name, school, major, start_date, end_date, created, updated)
              VALUES
              ('".addslashes($email)."', '".addslashes($name)."',
               '".addslashes($school)."', '".addslashes($major)."',
               $start_sql, $end_sql, NOW(), NOW())";
        if ($db->Execute($q)) {
            $messages[] = '<li class="text-success">Baris '.$row.': <b>'.$name.'</b> berhasil ditambahkan</li>';
            $success_names[] = $name;
            $success++;
        } else {
            $messages[] = '<li class="text-danger">Baris '.$row.': Gagal insert</li>';
            $fail++;
        }
    }
    fclose($handle);
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