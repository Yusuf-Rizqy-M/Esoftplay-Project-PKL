<?php
if (!defined('_VALID_BBC')) exit('No direct script access allowed');
_func('user');

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$db = $GLOBALS['db'];

$formAdd = _lib('pea', 'interns');
$formAdd->initEdit($id > 0 ? "WHERE id=$id" : "");

$header_title = ($id > 0) ? 'Edit Data Intern' : 'Add New Intern';
$formAdd->edit->addInput('header','header');
$formAdd->edit->input->header->setTitle($header_title);

$formAdd->edit->addInput('name','text');
$formAdd->edit->input->name->setTitle('Name');
$formAdd->edit->input->name->setRequire();

// ========== EMAIL FIELD - READONLY SAAT EDIT ==========
if ($id > 0) {
    // SAAT EDIT: Pakai 'sqlplaintext' agar terlihat tapi tidak bisa diubah
    $formAdd->edit->addInput('email','sqlplaintext');
    $formAdd->edit->input->email->setTitle('Email');
} else {
    // SAAT ADD: Pakai 'text' biasa
    $formAdd->edit->addInput('email','text');
    $formAdd->edit->input->email->setTitle('Email');
    $formAdd->edit->input->email->setRequire();
}

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

$formAdd->edit->input->end_date->setTitle('End Date');
$formAdd->edit->input->end_date->setRequire();

// Callback sebelum dan sesudah simpan
$formAdd->edit->onSave('intern_edit_before_save', '', false);
$formAdd->edit->onSave('intern_edit_after_save', '', true);

$formAdd->edit->action();

// Tampilkan Form
echo '<div class="panel panel-default">';
echo '<div class="panel-body">';
echo $formAdd->edit->getForm();
echo '</div>';
echo '</div>';

// --- FUNCTIONS ---

function intern_edit_before_save($intern_id) {
    global $db;
    
    // Cari value secara dinamis (karena PEA pakai array/prefix)
    $email = '';
    $name = '';
    $start = '';
    $end = '';
    $curr_id = 0;
    
    foreach($_POST as $k => $v){
        if(strpos($k, 'email') !== false) $email = strtolower(trim(is_array($v) ? current($v) : $v));
        if(strpos($k, 'name') !== false && strpos($k, 'email') === false) $name = trim(is_array($v) ? current($v) : $v);
        if(strpos($k, 'start_date') !== false && !is_array($v)) $start = $v;
        if(strpos($k, 'end_date') !== false && !is_array($v)) $end = $v;
        if(strpos($k, 'id') !== false && !is_array($v)) $curr_id = intval($v);
    }
    
    // Jika curr_id masih 0, coba ambil dari GET
    if ($curr_id == 0 && !empty($_GET['id'])) {
        $curr_id = intval($_GET['id']);
    }

    // 1. Validasi format email (hanya saat ADD, karena saat EDIT email readonly)
    if ($curr_id == 0 && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Gagal Simpan: Format email tidak valid!";
    }

    // 2. Validasi Tanggal - HARUS ADA KEDUA TANGGAL
    if(empty($start) || empty($end)){
        return "Gagal Simpan: Tanggal Mulai dan Tanggal Selesai harus diisi!";
    }
    
    // 3. Validasi Tanggal - END DATE HARUS LEBIH DARI START DATE (TIDAK BOLEH SAMA)
    if (strtotime($end) <= strtotime($start)) {
        return "Gagal Simpan: Tanggal Selesai (".date('d-m-Y', strtotime($end)).") harus setelah Tanggal Mulai (".date('d-m-Y', strtotime($start)).")!";
    }

    // 4. Cek Email Duplikat (hanya saat ADD, karena saat EDIT email tidak bisa diubah)
    if ($curr_id == 0) {
        $check_email = $db->getOne("SELECT id FROM interns WHERE email = '".addslashes($email)."'");
        if ($check_email) {
            return "Gagal Simpan: Email '$email' sudah digunakan oleh intern lain!";
        }
    }
    
    // 5. Logika Akun User (Hanya jika data baru)
    if ($curr_id == 0) {
        $user_check = $db->getOne("SELECT id FROM bbc_user WHERE username = '".addslashes($email)."'");
        if ($user_check) {
            $_SESSION['intern_temp_user_id'] = $user_check;
        } else {
            $params = array(
                'username' => $email, 
                'name' => $name, 
                'email' => $email,
                'password' => password_hash('intern123', PASSWORD_DEFAULT),
                'params' => ['_padding' => 1]
            );
            $user_id = user_create($params);
            if (!$user_id) {
                return "Gagal Simpan: Gagal membuat akun user!";
            }
            $_SESSION['intern_temp_user_id'] = $user_id;
        }
    }
    return true;
}

function intern_edit_after_save($intern_id) {
    global $db;
    // Hubungkan user_id ke tabel interns
    if (!empty($_SESSION['intern_temp_user_id']) && !empty($intern_id)) {
        $db->Execute("UPDATE interns SET user_id = ".intval($_SESSION['intern_temp_user_id'])." WHERE id = ".intval($intern_id));
        unset($_SESSION['intern_temp_user_id']);
    }
    return true;
}
?>

<script>
(function() {
    // Fungsi untuk setup validasi tanggal menggunakan Bootstrap Datepicker
    function setupDateValidation() {
        // Tunggu sampai jQuery dan datepicker ready
        if (typeof jQuery === 'undefined' || typeof jQuery.fn.datepicker === 'undefined') {
            setTimeout(setupDateValidation, 200);
            return;
        }
        
        // Cari input start_date dan end_date
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
        
        // Event saat start_date berubah
        $startDate.on('changeDate change', function() {
            var startVal = $startDate.val();
            if (startVal) {
                // Parse tanggal start
                var startDate = new Date(startVal);
                
                // Tambah 1 hari untuk minimum end_date
                var minEndDate = new Date(startDate);
                minEndDate.setDate(minEndDate.getDate() + 1);
                
                // Set startDate pada end_date datepicker
                // Ini akan membuat tanggal sebelum minEndDate tidak bisa diklik
                $endDate.datepicker('setStartDate', minEndDate);
                
                console.log('End date minimum set to:', minEndDate);
                
                // Jika end_date sudah terisi tapi invalid, kosongkan
                var endVal = $endDate.val();
                if (endVal) {
                    var endDate = new Date(endVal);
                    if (endDate <= startDate) {
                        $endDate.val('');
                        $endDate.datepicker('update', '');
                    }
                }
            } else {
                // Jika start_date dikosongkan, reset end_date restriction
                $endDate.datepicker('setStartDate', null);
            }
        });
        
        // Trigger event untuk set initial state jika start_date sudah ada value
        if ($startDate.val()) {
            $startDate.trigger('changeDate');
        }
    }
    
    // Init saat document ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', setupDateValidation);
    } else {
        setupDateValidation();
    }
    
    // Init ulang setelah beberapa delay untuk memastikan datepicker sudah ready
    setTimeout(setupDateValidation, 500);
    setTimeout(setupDateValidation, 1000);
})();
</script>