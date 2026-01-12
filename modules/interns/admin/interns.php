<?php
if (!defined('_VALID_BBC')) exit('No direct script access allowed');
_func('date');
_func('user');

if (isset($_GET['act']) && $_GET['act'] == 'sample_intern') {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment;filename="sample_import_intern.csv"');
    echo "email,name,no_hp,school,major,start_date,end_date\n";
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

    if (!empty($s['search_start_date'])) {
        $date = date('Y-m-d', strtotime(str_replace('/', '-', $s['search_start_date'])));
        $where[] = "`start_date` LIKE '{$date}%'";
    }

    if (!empty($s['search_end_date'])) {
        $date = date('Y-m-d', strtotime(str_replace('/', '-', $s['search_end_date'])));
        $where[] = "`end_date` LIKE '{$date}%'";
    }
}

$sqlWhere = $where ? 'WHERE '.implode(' AND ', $where) : 'WHERE 1';
// pr($sqlWhere);
// pr($add_sql);
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
$formList->roll->addInput('no_hp','sqlplaintext');
$formList->roll->input->no_hp->setTitle('No HP');
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

$formList->roll->addInput('task_link', 'sqllinks');
$formList->roll->input->task_link->setLinks($Bbc->mod['circuit'].'.interns_tasks_detail');
$formList->roll->input->task_link->setTitle('Tasks');
$formList->roll->input->task_link->setFieldName('user_id as detail' );
$formList->roll->input->task_link->setDisplayFunction(function( $row) {
    global $Bbc;
    $url = $Bbc->mod['circuit'].'.interns_tasks_detail&id=' . intval($row);
    return '<a href="'.$url.'" class="btn btn-xs btn-primary">Lihat User</a>';
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
$formAdd->edit->addInput('no_hp','text');
$formAdd->edit->input->no_hp->setTitle('No HP');
$formAdd->edit->input->no_hp->setNumberFormat(true);
$formAdd->edit->input->no_hp->setExtra(' minlength="9" maxlength="14"');
$formAdd->edit->input->no_hp->setRequire();
$formAdd->edit->addInput('school','text');
$formAdd->edit->input->school->setTitle('School');
$formAdd->edit->addInput('major','text');
$formAdd->edit->input->major->setTitle('Major');
$formAdd->edit->addInput('start_date', 'dateinterval');
$formAdd->edit->input->start_date->setTitle('Internship Period');
$formAdd->edit->input->start_date->setCaption('Start Date');
$formAdd->edit->input->start_date->setEndDateField('end_date');
$formAdd->edit->input->start_date->setRequire();

// LOGIKA VALIDASI SEBELUM SIMPAN (ADD & EDIT)
$formAdd->edit->onSave('intern_before_save');
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
</style>

<div class="col-xs-12 no-both">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title" data-toggle="collapse" href="#import_panel" style="cursor:pointer;">
                <?php echo icon('fa-file-excel-o') ?> Klik Disini Untuk Import Data Intern Dari CSV
            </h4>
        </div>
        <div id="import_panel" class="panel-collapse collapse">
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="panel-body">
                    <div class="form-group">
                        <label>Upload File CSV</label>
                        <input type="file" name="excel" class="form-control" accept=".csv" />
                        <div class="help-block">
                            Urutan kolom: email, name, no_hp, school, major, start_date, end_date.<br>
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
// VALIDASI & PROSES IMPORT CSV
if (!empty($_POST['transfer']) && $_POST['transfer']=='upload' && !empty($_FILES['excel']['tmp_name'])) {
    global $db;
    $file=$_FILES['excel']['tmp_name'];
    $handle=fopen($file,"r");
    $success=0;$fail=0;$row=0;$error_logs=[];
    while(($data=fgetcsv($handle,1000,","))!==false){
        $row++;
        if($row==1) continue;
        $email = strtolower(trim($data[0]??''));
        $name  = trim($data[1]??'');
        $no_hp = trim($data[2]??'');
        $school= trim($data[3]??'');
        $major = trim($data[4]??'');
        $start = trim($data[5]??'');
        $end   = trim($data[6]??'');

        if(empty($email)){ $error_logs[]="Baris $row: Email kosong"; $fail++; continue; }

        // Cek Tanggal Terbalik di CSV
        if (!empty($start) && !empty($end)) {
            if (strtotime($end) <= strtotime($start)) {
                $error_logs[]="Baris $row ($name): Tanggal selesai harus setelah tanggal mulai!";
                $fail++; continue;
            }
        }

        // Cek Email Duplikat di CSV
        $is_exist = $db->getOne("SELECT id FROM interns WHERE email='".addslashes($email)."'");
        if($is_exist){ $error_logs[]="Baris $row ($name): Email '$email' sudah terdaftar"; $fail++; continue; }

        if($db->Execute("INSERT INTO interns (email,name,no_hp,school,major,start_date,end_date,created) VALUES ('".addslashes($email)."','".addslashes($name)."','".addslashes($no_hp)."','".addslashes($school)."','".addslashes($major)."','$start','$end',NOW())")){
            $success++;
        }
    }
    fclose($handle);
    if($fail>0){
        echo '<div class="alert alert-danger"><h4>Gagal Import:</h4><ul>';
        foreach($error_logs as $log) echo "<li>$log</li>";
        echo '</ul><button class="btn btn-danger" onclick="location.reload();">Tutup & Perbaiki</button></div>';
    }
    if($success>0){
        echo '<div class="loading-overlay"><div class="loader-spinner"></div><h3>Import Berhasil!</h3><p>'.$success.' data ditambahkan.</p></div>';
        echo '<script>setTimeout(function(){location.href="index.php?mod=interns";},2000);</script>';
    }
}

function intern_before_save($intern_id){
    global $db, $Bbc;
    
    // Cari value secara dinamis (karena PEA pakai array/prefix)
    $email = ''; $start = ''; $end = ''; $name = '';
    foreach($_POST as $k => $v){
        if(strpos($k, 'email') !== false) $email = strtolower(trim(is_array($v) ? current($v) : $v));
        if(strpos($k, 'start_date') !== false && !is_array($v)) $start = $v;
        if(strpos($k, 'end_date') !== false && !is_array($v)) $end = $v;
        if(strpos($k, 'name') !== false) $name = trim(is_array($v) ? current($v) : $v);
    }

    // 1. Validasi Tanggal
    if(!empty($start) && !empty($end)){
        if(strtotime($end) <= strtotime($start)){
            return "Gagal Simpan: Tanggal Selesai harus lebih besar dari Tanggal Mulai!";
        }
    }

    // 2. Validasi Email Duplikat (Kecuali record diri sendiri saat Edit)
    $curr_id = !empty($_GET['id']) ? intval($_GET['id']) : 0;
    $is_exist = $db->getOne("SELECT id FROM interns WHERE email='".addslashes($email)."' AND id != $curr_id");
    if($is_exist){
        return "Gagal Simpan: Email '$email' sudah digunakan oleh intern lain!";
    }

    // Proses User BBC (Opsional jika diperlukan)
    if($curr_id == 0){
        $user_id = $db->getOne("SELECT id FROM bbc_user WHERE username='".addslashes($email)."'");
        if(!$user_id){
            $params = ['username'=>$email,'name'=>$name,'email'=>$email,'password'=>password_hash('intern123',PASSWORD_DEFAULT),'params'=>['_padding'=>1]];
            $user_id = user_create($params);
        }
        if($user_id > 0) $_SESSION['intern_temp_user_id'] = intval($user_id);
    }
    return true;
}

function intern_after_save($intern_id){
    global $db;
    if(!empty($_SESSION['intern_temp_user_id']) && !empty($intern_id)){
        $user_id = intval($_SESSION['intern_temp_user_id']);
        $db->Execute("UPDATE interns SET user_id=$user_id WHERE id=".intval($intern_id));
        unset($_SESSION['intern_temp_user_id']);
    }
    return true;
}
?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    function initLogic() {
        var allInputs = document.querySelectorAll('input');
        var sInp, eInp, mailInp;

        allInputs.forEach(function(el) {
            if (el.name && el.name.includes('start_date')) sInp = el;
            if (el.name && el.name.includes('end_date')) eInp = el;
            if (el.name && el.name.includes('email')) mailInp = el;
        });

        if (sInp && eInp) {
            function validateDates() {
                if (sInp.value && eInp.value) {
                    var s = new Date(sInp.value);
                    var e = new Date(eInp.value);
                    if (e <= s) {
                        alert("KESALAHAN:\nTanggal selesai ("+eInp.value+") tidak boleh sebelum/sama dengan tanggal mulai ("+sInp.value+").\n\nSilahkan perbaiki tanggalnya.");
                        eInp.value = ''; 
                    }
                }
            }
            sInp.addEventListener('change', validateDates);
            eInp.addEventListener('change', validateDates);
        }
    }

    initLogic();
    // Re-init jika user pindah tab
    if(typeof jQuery !== 'undefined') {
        jQuery('a[data-toggle="tab"]').on('shown.bs.tab', function() { initLogic(); });
    }
});
</script>