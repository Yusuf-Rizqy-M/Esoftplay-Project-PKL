<?php
if (!defined('_VALID_BBC')) exit('No direct script access allowed');
_func('date');
_func('user');

// ========== HANDLE SAMPLE CSV DOWNLOAD ==========
if (isset($_GET['act']) && $_GET['act'] == 'sample_intern') {
  header('Content-Type: text/csv; charset=utf-8');
  header('Content-Disposition: attachment;filename="sample_import_intern.csv"');
  echo "email,name,phone,school,major,start_date,end_date\n";
  echo "choirulanam@gmail.com,Muhammad Choirul Anam,081234567890,SMK Raden Umar Said,PPLG,2025-10-06,2026-04-06\n";
  die();
}

// ========== SEARCH FORM ==========
$form_search = _lib('pea', 'interns');
$form_search->initSearch();

$form_search->search->addInput('status', 'select');
$form_search->search->input->status->setTitle('Status');
$form_search->search->input->status->addOption('-- All Status --', '');
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
$form_search->search->input->start_date->setTitle('Internship Period');
$form_search->search->input->start_date->setIsSearchRange();

$add_sql = $form_search->search->action();
echo $form_search->search->getForm();

// ========== TABS ARRAY ==========
$tabs = array();
$is_edit = (!empty($_GET['id']) && is_numeric($_GET['id'])) ? true : false;

// ========== INTERNS LIST FORM ==========
$form_list = _lib('pea', 'interns');
$form_list->initRoll($add_sql . ' ORDER BY id DESC', 'id');
pr ($add_sql);
$form_list->roll->setDeleteTool(true);
$form_list->roll->setSaveTool(false);

$form_list->roll->addInput('name', 'sqllinks');
$form_list->roll->input->name->setLinks($Bbc->mod['circuit'] . '.interns_edit');
$form_list->roll->input->name->setTitle('Name');

$form_list->roll->addInput('email', 'sqlplaintext');
$form_list->roll->input->email->setTitle('Email');

$form_list->roll->addInput('phone', 'sqlplaintext');
$form_list->roll->input->phone->setTitle('Phone');

$form_list->roll->addInput('school', 'sqlplaintext');
$form_list->roll->input->school->setTitle('School');

$form_list->roll->addInput('major', 'sqlplaintext');
$form_list->roll->input->major->setTitle('Major');

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
  $status = $status_map[$value] ?? ['label' => 'Unknown', 'color' => '#6c757d'];
  return '<span class="label" style="background-color: ' . $status['color'] . '; color: white; padding: 5px 12px; border-radius: 12px; font-size: 11px; font-weight: 600; display: inline-block;">' . $status['label'] . '</span>';
});

$form_list->roll->addInput('task_link', 'sqllinks');
$form_list->roll->input->task_link->setLinks('#');
$form_list->roll->input->task_link->setTitle('Tasks');
$form_list->roll->input->task_link->setFieldName('id as task_link');
$form_list->roll->input->task_link->setDisplayFunction(function ($intern_id) {
  global $Bbc;
  $url = $Bbc->mod['circuit'] . '.interns_tasks_list&force_intern_id=' . intval($intern_id);
  return '<a href="' . $url . '" class="btn btn-xs btn-primary">Lihat Pengerjaan</a>';
});

$form_list->roll->action();

// ========== INCLUDE INTERNS_EDIT.PHP untuk Tab 2 ==========
ob_start();
include 'interns_edit.php';
$form_edit_content = ob_get_clean();

// ========== BUILD TABS ==========
$tabs['List Interns'] = $form_list->roll->getForm();
$tabs[$is_edit ? 'Edit Intern' : 'Add Intern'] = $form_edit_content;

echo tabs($tabs, ($is_edit ? 2 : 1), 'tabs_interns');
?>

<!-- ========== STYLES ========== -->
<style>
  .loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, .95);
    z-index: 9999;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    text-align: center
  }

  .loader-spinner {
    border: 8px solid #f3f3f3;
    border-top: 8px solid #3498db;
    border-radius: 50%;
    width: 60px;
    height: 60px;
    animation: spin 1s linear infinite;
    margin-bottom: 20px
  }

  @keyframes spin {
    0% {
      transform: rotate(0deg)
    }

    100% {
      transform: rotate(360deg)
    }
  }

  .loading-text {
    font-family: Arial, sans-serif;
    font-size: 20px;
    color: #333;
    font-weight: bold;
    max-width: 600px
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
    max-width: 500px
  }

  .success-list li {
    margin-bottom: 5px
  }
</style>

<!-- ========== IMPORT CSV PANEL ========== -->
<div class="col-xs-12 no-both">
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title" data-toggle="collapse" href="#import_panel" style="cursor:pointer;">
        <?php echo icon('fa-file-excel-o') ?> Klik disini untuk import data intern dari CSV
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
// ========== HELPER FUNCTION: Calculate Status ==========
function calculate_intern_status($start_date, $end_date)
{
  $current = date('Y-m-d');
  if ($current < $start_date) {
    return 3; // Coming Soon
  } elseif ($current >= $start_date && $current <= $end_date) {
    return 1; // Active
  } else {
    return 2; // Ended
  }
}

// ========== CSV IMPORT HANDLER ==========
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
        $messages[] = '<li class="text-danger">Baris ' . $row . ': Skip - email atau name kosong</li>';
        $fail++;
        continue;
      }

      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $messages[] = '<li class="text-danger">Baris ' . $row . ': Skip - Format email tidak valid</li>';
        $fail++;
        continue;
      }

      if ($db->getOne("SELECT id FROM interns WHERE email = '" . addslashes($email) . "'")) {
        $messages[] = '<li class="text-danger">Baris ' . $row . ': Skip - Email <b>' . $email . '</b> sudah terdaftar</li>';
        $fail++;
        continue;
      }

      $start_ts = $start ? strtotime($start) : false;
      $end_ts = $end ? strtotime($end) : false;

      if ($start_ts && $end_ts && $end_ts <= $start_ts) {
        $messages[] = '<li class="text-danger">Baris ' . $row . ': Skip - End Date harus setelah Start Date</li>';
        $fail++;
        continue;
      }

      $start_sql = $start_ts ? "'" . date('Y-m-d', $start_ts) . "'" : "NULL";
      $end_sql = $end_ts ? "'" . date('Y-m-d', $end_ts) . "'" : "NULL";

      // Calculate status
      $status = 1; // Default Active
      if ($start_ts && $end_ts) {
        $status = calculate_intern_status(date('Y-m-d', $start_ts), date('Y-m-d', $end_ts));
      }

      $user_id = 0;
      $user_check = $db->getOne("SELECT id FROM bbc_user WHERE username = '" . addslashes($email) . "'");

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
          $messages[] = '<li class="text-danger">Baris ' . $row . ': Gagal membuat user menggunakan user_create()</li>';
          $fail++;
          continue;
        }
      }

      $user_id_sql = $user_id > 0 ? $user_id : "NULL";

      $q = "INSERT INTO interns
                  (email, name, phone, school, major, start_date, end_date, status, user_id, created, updated)
                  VALUES
                  ('" . addslashes($email) . "', '" . addslashes($name) . "', '" . addslashes($phone) . "',
                   '" . addslashes($school) . "', '" . addslashes($major) . "',
                   $start_sql, $end_sql, $status, $user_id_sql, NOW(), NOW())";

      if ($db->Execute($q)) {
        $intern_id = $db->insert_ID();
        $messages[] = '<li class="text-success">Baris ' . $row . ': <b>' . $name . '</b> berhasil ditambahkan (User ID: ' . $user_id . ', Intern ID: ' . $intern_id . ')</li>';
        $success_names[] = $name;
        $success++;
      } else {
        $messages[] = '<li class="text-danger">Baris ' . $row . ': Gagal insert intern - ' . htmlspecialchars($db->ErrorMsg()) . '</li>';
        $fail++;
      }
    }

    fclose($handle);
    $db->Execute("SET FOREIGN_KEY_CHECKS=1");

    if ($fail > 0) {
      echo '<div class="alert alert-danger" id="import-error-alert" style="margin-top:20px;"><h4>Hasil Import:</h4><ul>';
      foreach ($messages as $msg) echo $msg;
      echo '</ul><button type="button" class="btn btn-danger" onclick="InternImport.closeErrorAndOpenPanel()">Tutup & Perbaiki</button></div>';
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

<!-- ========== JAVASCRIPT ========== -->
<script type="text/javascript">
(function() {
  'use strict';

  var InternDateValidation = {
    setupValidation: function() {
      if (typeof jQuery === 'undefined' || typeof jQuery.fn.datepicker === 'undefined') {
        setTimeout(InternDateValidation.setupValidation, 200);
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

      if (!startDateInput || !endDateInput) return;

      startDateInput.on('changeDate change', function() {
        var startValue = startDateInput.val();
        if (startValue) {
          var startDateObj = new Date(startValue);
          var minEndDate = new Date(startDateObj);
          minEndDate.setDate(minEndDate.getDate() + 1);
          endDateInput.datepicker('setStartDate', minEndDate);

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

    init: function() {
      if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', InternDateValidation.setupValidation);
      } else {
        InternDateValidation.setupValidation();
      }

      setTimeout(InternDateValidation.setupValidation, 500);
      setTimeout(InternDateValidation.setupValidation, 1000);

      if (typeof jQuery !== 'undefined') {
        jQuery(document).ready(function() {
          jQuery('a[data-toggle="tab"]').on('shown.bs.tab', function() {
            setTimeout(InternDateValidation.setupValidation, 300);
          });
        });
      }
    }
  };

  var InternImport = {
    closeErrorAndOpenPanel: function() {
      var errorAlert = document.getElementById('import-error-alert');
      if (errorAlert) {
        errorAlert.style.display = 'none';
      }
      if (typeof jQuery !== 'undefined') {
        jQuery('#import_panel').collapse('show');
      }
      setTimeout(function() {
        var panelElement = document.getElementById('import_panel');
        if (panelElement) {
          panelElement.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
          });
        }
      }, 300);
    }
  };
                                      
  window.InternDateValidation = InternDateValidation;
  window.InternImport = InternImport;
  InternDateValidation.init();
})();
</script>