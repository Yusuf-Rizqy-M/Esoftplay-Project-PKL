<?php
if (!defined('_VALID_BBC')) exit('No direct script access allowed');
_func('user');

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$form_add = _lib('pea', 'interns');
$form_add->initEdit($id > 0 ? "WHERE id=$id" : "");

$header_title = ($id > 0) ? 'Edit Data Intern' : 'Add New Intern';
$form_add->edit->addInput('header', 'header');
$form_add->edit->input->header->setTitle($header_title);

$form_add->edit->addInput('name', 'text');
$form_add->edit->input->name->setTitle('Name');
$form_add->edit->input->name->setRequire();

// ========== EMAIL FIELD - READONLY SAAT EDIT ==========
if ($id > 0) {
  $form_add->edit->addInput('email', 'sqlplaintext');
  $form_add->edit->input->email->setTitle('Email');
} else {
  $form_add->edit->addInput('email', 'text');
  $form_add->edit->input->email->setTitle('Email');
  $form_add->edit->input->email->setRequire();
}

$form_add->edit->addInput('phone', 'text');
$form_add->edit->input->phone->setTitle('Phone');
$form_add->edit->input->phone->setNumberFormat(true);
$form_add->edit->input->phone->setExtra(' minlength="9" maxlength="14"');
$form_add->edit->input->phone->setRequire();

$form_add->edit->addInput('school', 'text');
$form_add->edit->input->school->setTitle('School');

$form_add->edit->addInput('major', 'text');
$form_add->edit->input->major->setTitle('Major');

$form_add->edit->addInput('start_date', 'dateinterval');
$form_add->edit->input->start_date->setTitle('Internship Period');
$form_add->edit->input->start_date->setCaption('Start Date');
$form_add->edit->input->start_date->setEndDateField('end_date');
$form_add->edit->input->start_date->setRequire();

$form_add->edit->input->end_date->setTitle('End Date');
$form_add->edit->input->end_date->setRequire();

$form_add->edit->onSave('intern_edit_before_save', '', false);
$form_add->edit->onSave('intern_edit_after_save', '', true);

$form_add->edit->action();

echo '<div class="panel panel-default">';
echo '<div class="panel-body">';
echo $form_add->edit->getForm();
echo '</div>';
echo '</div>';

// ========== CALLBACK FUNCTIONS ==========

function intern_edit_before_save($intern_id)
{
  global $db;

  $email = '';
  $name = '';
  $start = '';
  $end = '';
  $curr_id = 0;

  foreach ($_POST as $k => $v) {
    if (strpos($k, 'email') !== false) $email = strtolower(trim(is_array($v) ? current($v) : $v));
    if (strpos($k, 'name') !== false && strpos($k, 'email') === false) $name = trim(is_array($v) ? current($v) : $v);
    if (strpos($k, 'start_date') !== false && !is_array($v)) $start = $v;
    if (strpos($k, 'end_date') !== false && !is_array($v)) $end = $v;
    if (strpos($k, 'id') !== false && !is_array($v)) $curr_id = intval($v);
  }

  if ($curr_id == 0 && !empty($_GET['id'])) {
    $curr_id = intval($_GET['id']);
  }

  if ($curr_id == 0 && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return "Gagal Simpan: Format email tidak valid!";
  }

  if (empty($start) || empty($end)) {
    return "Gagal Simpan: Tanggal Mulai dan Tanggal Selesai harus diisi!";
  }

  if (strtotime($end) <= strtotime($start)) {
    return "Gagal Simpan: Tanggal Selesai (" . date('d-m-Y', strtotime($end)) . ") harus setelah Tanggal Mulai (" . date('d-m-Y', strtotime($start)) . ")!";
  }

  if ($curr_id == 0) {
    $check_email = $db->getOne("SELECT id FROM interns WHERE email = '" . addslashes($email) . "'");
    if ($check_email) {
      return "Gagal Simpan: Email '$email' sudah digunakan oleh intern lain!";
    }
  }

  // ========== CALCULATE STATUS BERDASARKAN TANGGAL ==========
  $current = date('Y-m-d');
  $status = 1; // Default Active
  
  if ($current < $start) {
    $status = 3; // Coming Soon
  } elseif ($current >= $start && $current <= $end) {
    $status = 1; // Active
  } else {
    $status = 2; // Ended
  }
  
  // Simpan status ke session untuk digunakan di after_save
  $_SESSION['intern_temp_status'] = $status;

  if ($curr_id == 0) {
    $user_check = $db->getOne("SELECT id FROM bbc_user WHERE username = '" . addslashes($email) . "'");
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
  } else {
    // Jika edit, langsung update status di sini
    $db->Execute("UPDATE interns SET status = {$status} WHERE id = {$curr_id}");
  }
  
  return true;
}

function intern_edit_after_save($intern_id)
{
  global $db;

  if (!empty($_SESSION['intern_temp_user_id']) && !empty($intern_id)) {
    $db->Execute("UPDATE interns SET user_id = " . intval($_SESSION['intern_temp_user_id']) . " WHERE id = " . intval($intern_id));
    unset($_SESSION['intern_temp_user_id']);
  }

  // Update status untuk data yang baru di-insert
  if (!empty($_SESSION['intern_temp_status']) && !empty($intern_id)) {
    $db->Execute("UPDATE interns SET status = " . intval($_SESSION['intern_temp_status']) . " WHERE id = " . intval($intern_id));
    unset($_SESSION['intern_temp_status']);
  }

  return true;
}
?>

<script type="text/javascript">
/**
 * Interns Edit Module - JavaScript Functions
 * Naming Convention: camelCase for JavaScript variables
 * PHP Variables use: snake_case
 */
(function() {
  'use strict';

  // ========== NAMESPACE: InternEditDateValidation ==========
  var InternEditDateValidation = {
    /**
     * Setup date validation for start_date and end_date fields
     */
    setupValidation: function() {
      if (typeof jQuery === 'undefined' || typeof jQuery.fn.datepicker === 'undefined') {
        setTimeout(InternEditDateValidation.setupValidation, 200);
        return;
      }

      var inputFields = jQuery('input[type="text"], input[type="date"]');
      var startDateInput = null;
      var endDateInput = null;

      inputFields.each(function() {
        var fieldName = jQuery(this).attr('name') || '';
        if (fieldName.indexOf('start_date') !== -1 && fieldName.indexOf('search') === -1) {
          startDateInput = jQuery(this);
        }
        if (fieldName.indexOf('end_date') !== -1 && fieldName.indexOf('search') === -1) {
          endDateInput = jQuery(this);
        }
      });

      if (!startDateInput || !endDateInput) {
        return;
      }

      console.log('Date inputs found:', startDateInput.attr('name'), endDateInput.attr('name'));

      startDateInput.on('changeDate change', function() {
        var startValue = startDateInput.val();

        if (startValue) {
          var startDateObj = new Date(startValue);
          var minEndDate = new Date(startDateObj);
          minEndDate.setDate(minEndDate.getDate() + 1);

          endDateInput.datepicker('setStartDate', minEndDate);
          console.log('End date minimum set to:', minEndDate);

          var endValue = endDateInput.val();
          if (endValue) {
            var endDateObj = new Date(endValue);
            if (endDateObj <= startDateObj) {
              endDateInput.val('');
              endDateInput.datepicker('update', '');
            }
          }
        } else {
          endDateInput.datepicker('setStartDate', null);
        }
      });

      if (startDateInput.val()) {
        startDateInput.trigger('changeDate');
      }
    },

    /**
     * Initialize date validation
     */
    init: function() {
      if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', InternEditDateValidation.setupValidation);
      } else {
        InternEditDateValidation.setupValidation();
      }

      setTimeout(InternEditDateValidation.setupValidation, 500);
      setTimeout(InternEditDateValidation.setupValidation, 1000);
    }
  };

  // ========== EXPORT TO WINDOW ==========
  window.InternEditDateValidation = InternEditDateValidation;

  // ========== AUTO INITIALIZE ==========
  InternEditDateValidation.init();

})();
</script>