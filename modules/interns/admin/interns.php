<?php 
if (!defined('_VALID_BBC')) exit('No direct script access allowed');

if (isset($_GET['act']) && $_GET['act'] == 'sample_intern') {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment;filename="sample_import_intern.csv"');
    echo "email,name,school,major,start_date,end_date\n";
    echo "yusuf@example.com,Yusuf Rizqy,SMK Raden Umar Said,PPLG,2025-10-06,2026-03-28\n";
    echo "wisnu@example.com,Wisnu Adi,SMK Negeri 2,TKJ,2025-11-01,2026-04-30\n";
    die();
}

$formSearch = _lib('pea', 'interns');
$formSearch->initSearch();
$formSearch->search->addInput('keyword','keyword');
$formSearch->search->input->keyword->addSearchField('name', false);
$formSearch->search->input->keyword->addSearchField('email');
$add_sql = $formSearch->search->action();
echo $formSearch->search->getForm();

$tabs = array('Interns' => '', 'Add Intern' => '');

$formAdd = _lib('pea', 'interns');
$formAdd->initEdit();

$formAdd->edit->addInput('header','header');
$formAdd->edit->input->header->setTitle('Add New Intern');

$formAdd->edit->addInput('name','text');
$formAdd->edit->input->name->setTitle('Name');
$formAdd->edit->input->name->setRequire(true);

$formAdd->edit->addInput('email','text');
$formAdd->edit->input->email->setTitle('Email');
$formAdd->edit->input->email->setRequire(true);

$formAdd->edit->onSave(function($rows) {
    global $db;

    $email = trim($rows['email']);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return 'Error: Format email tidak valid!';
    }

    $esc_email = $db->escape($email);

    $check = $db->getOne("SELECT id FROM interns WHERE email='$esc_email'");
    if ($check && (!empty($rows['id']) ? $rows['id'] : 0) != $check) {
        return 'Error: Email sudah terdaftar!';
    }

    if (!empty($rows['start_date']) && !empty($rows['end_date'])) {
        if (strtotime($rows['end_date']) < strtotime($rows['start_date'])) {
            return 'Error: End Date tidak boleh lebih kecil dari Start Date!';
        }
    }
    return true;
});

$formAdd->edit->addInput('school','text');
$formAdd->edit->input->school->setTitle('School');

$formAdd->edit->addInput('major','text');
$formAdd->edit->input->major->setTitle('Major');

$formAdd->edit->addInput('start_date','date');
$formAdd->edit->input->start_date->setTitle('Start Date Internship');
$formAdd->edit->input->start_date->setRequire(true);
$formAdd->edit->input->start_date->setParam(array(
    'autoclose' => true,
    'format' => 'yyyy-mm-dd',
    'today-btn' => true,
    'today-highlight' => true
));

$formAdd->edit->addInput('end_date','date');
$formAdd->edit->input->end_date->setTitle('End Date Internship');
$formAdd->edit->input->end_date->setRequire(true);
$formAdd->edit->input->end_date->setParam(array(
    'autoclose' => true,
    'format' => 'yyyy-mm-dd',
    'today-btn' => true,
    'today-highlight' => true
));

$formAdd->edit->action();
$tabs['Add Intern'] = $formAdd->edit->getForm();

$formList = _lib('pea', 'interns');
$formList->initRoll($add_sql . ' ORDER BY id DESC', 'id');

$formList->roll->setDeleteTool(false);
$formList->roll->setSaveTool(false);

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

$formList->roll->addInput('start_date','text');
$formList->roll->input->start_date->setTitle('Start Date');
$formList->roll->input->start_date->setPlaintext(true);

$formList->roll->addInput('end_date','text');
$formList->roll->input->end_date->setTitle('End Date');
$formList->roll->input->end_date->setPlaintext(true);

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
        <div aria-expanded="false" class="panel-collapse collapse" id="pea_isHideToolOnadd_r_params" style="height: 0px;">
          <form action="" method="POST" class="form" role="form" enctype="multipart/form-data">
                    <div class="panel-body">
                        <div class="form-group">
                            <label>Upload File</label>
                            <input type="file" name="excel" class="form-control" placeholder="upload di sini!" accept=".csv" />
                            <div class="help-block">
                                Upload file daftar intern dalam format CSV. Silahkan download "sample file" untuk menentukan kolom-kolom apa saja yang perlu diisikan di <a href="?mod=interns&act=sample_intern">sini</a> (urutan: email,name,school,major,start_date,end_date).
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <button type="submit" name="transfer" value="upload" class="btn btn-default"><?php echo icon('fa-upload') ?> Upload Data</button>
                    </div>
                </form>
        </div>
      </div>
    </div>
</div>

<?php
if ((!empty($_POST['transfer'])) && ($_POST['transfer'] == 'upload') && !empty($_FILES['excel']['tmp_name'])) {
    global $db;

    $file = $_FILES['excel']['tmp_name'];

    function get_delimiter($file) {
        $file_handle = fopen($file, 'r');
        $line = fgets($file_handle);
        fclose($file_handle);

        $delimiters = [',' => 0, ';' => 0, "\t" => 0];
        foreach ($delimiters as $delim => &$count) {
            $count = substr_count($line, $delim);
        }
        return array_search(max($delimiters), $delimiters);
    }

    $delimiter = get_delimiter($file);
    $handle    = fopen($file, "r");

    if ($handle === false) {
        echo '<div class="alert alert-danger">Gagal membuka file CSV!</div>';
    } else {
        $success = $gagal = 0;
        $baris   = 0;

        echo '<div class="alert alert-info"><h4>Log Import:</h4><ul>';

        while (($data = fgetcsv($handle, 2000, $delimiter)) !== false) {
            $baris++;
            
            if ($baris == 1) continue;

            if (empty($data[0]) && count($data) < 2) continue;

            $raw_email  = trim($data[0] ?? '');
            $raw_name   = trim($data[1] ?? '');
            $raw_school = trim($data[2] ?? '');
            $raw_major  = trim($data[3] ?? '');
            $start      = trim($data[4] ?? '');
            $end        = trim($data[5] ?? '');

            if (empty($raw_email) || empty($raw_name)) {
                echo "<li>Baris $baris: <span class='label label-warning'>Skip</span> Baris kosong atau data tidak lengkap.</li>";
                $gagal++;
                continue;
            }

            $email  = addslashes($raw_email);
            $name   = addslashes($raw_name);
            $school = addslashes($raw_school);
            $major  = addslashes($raw_major);

            $check = $db->getOne("SELECT id FROM interns WHERE email='$email'");
            if ($check) {
                echo "<li>Baris $baris: <span class='label label-danger'>Skip</span> Email <strong>$raw_email</strong> sudah ada di database.</li>";
                $gagal++;
                continue;
            }

            $start_date = (!empty($start)) ? "'" . date('Y-m-d', strtotime($start)) . "'" : "NULL";
            $end_date   = (!empty($end)) ? "'" . date('Y-m-d', strtotime($end)) . "'" : "NULL";

            $q = "INSERT INTO interns (email, name, school, major, start_date, end_date, created, updated)
                  VALUES ('$email', '$name', '$school', '$major', $start_date, $end_date, NOW(), NOW())";

            if ($db->Execute($q)) {
                $success++;
            } else {
                echo "<li>Baris $baris: <span class='label label-danger'>Error</span> Gagal menyimpan data ke database.</li>";
                $gagal++;
            }
        }

        fclose($handle);
        echo '</ul><hr><strong>Selesai!</strong> Berhasil: <span class="badge">'.$success.'</span> | Gagal/Skip: <span class="badge">'.$gagal.'</span></div>';
    }
}
?>