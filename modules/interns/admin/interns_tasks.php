<?php
if (!defined('_VALID_BBC')) exit('No direct script access allowed');

_func('download');

if (!empty($_POST['transfer'])) {
    if ($_POST['transfer'] == 'download') {
        $q = "SELECT title, description, timeline, type FROM interns_tasks ORDER BY id DESC LIMIT 10";
        $r = $db->getAll($q);

        if (!empty($r)) {
            foreach ($r as $k => &$val) {
                if ($k == 0) {
                    $val['Keterangan'] = 'Timeline dalam satuan hari (angka).';
                }
                if ($k == 1) {
                    $val['Keterangan'] = 'Title tugas harus unik.';
                }
            }
            download_excel('Sample_Tasks_Data_'.date('Y-m-d'), $r);
            die();
        } else {
            echo msg('Maaf, tidak ada data task yang bisa dijadikan sampel.', 'danger');
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
                        'A' => 'Title',
                        'B' => 'Description',
                        'C' => 'Timeline',
                        'D' => 'Type'
                    );

                    $is_valid = true;
                    foreach ($headers as $col => $title_col) {
                        if (trim($output[1][$col]) != $title_col) {
                            $is_valid = false;
                            break;
                        }
                    }

                    if ($is_valid) {
                        $success = 0;
                        foreach ($output as $i => $cells) {
                            if ($i == 1 || empty($cells['A'])) continue;

                            $title = trim($cells['A']);
                            $desc  = trim($cells['B']);
                            $time  = trim($cells['C']);
                            $type  = trim($cells['D']);

                            $is_exist = $db->getOne("SELECT 1 FROM interns_tasks WHERE title='" . addslashes($title) . "'");
                            if (!$is_exist) {
                                $timeline_sql = !empty($time) && is_numeric($time) ? intval($time) : 0;
                                $q = "INSERT INTO interns_tasks SET 
                                    title       = '" . addslashes($title) . "', 
                                    description = '" . addslashes($desc) . "', 
                                    timeline    = $timeline_sql, 
                                    type        = '" . addslashes($type) . "', 
                                    created     = NOW()";
                                if ($db->Execute($q)) $success++;
                            }
                        }
                        $msg = msg("Upload tasks berhasil. $success data baru berhasil diimport.", 'success');
                    } else {
                        $msg = msg('Maaf, format kolom file tidak sesuai. Pastikan urutan: Title, Description, Timeline, Type.', 'danger');
                    }
                } else {
                    $msg = msg('Maaf, file yang anda upload tidak terbaca.', 'danger');
                }
            } else {
                $msg = msg('Mohon upload file dengan format Excel yang benar (.xlsx)', 'danger');
            }
        }
        if (!empty($msg)) echo $msg;
    }
}

$formSearch = _lib('pea', 'interns_tasks');
$formSearch->initSearch();
$formSearch->search->addInput('keyword', 'keyword');
$formSearch->search->input->keyword->setTitle('Search Task Title');
$formSearch->search->input->keyword->addSearchField('title', false);
$formSearch->search->addInput('type_keyword', 'keyword');
$formSearch->search->input->type_keyword->setTitle('Search Type');
$formSearch->search->input->type_keyword->addSearchField('type', false);

$add_sql = $formSearch->search->action();

echo '<div style="margin-bottom: 20px;">';
echo $formSearch->search->getForm();
echo '</div>';

include 'interns_tasks_edit.php';

$formList = _lib('pea', 'interns_tasks');
$formList->initRoll($add_sql . ' ORDER BY id DESC', 'id');
$formList->roll->setSaveTool(false);
$formList->roll->setDeleteTool(true);

$formList->roll->addInput('title', 'sqllinks');
$formList->roll->input->title->setLinks($Bbc->mod['circuit'] . '.interns_tasks_edit');
$formList->roll->input->title->setTitle('Title');

$formList->roll->addInput('description', 'sqlplaintext');
$formList->roll->input->description->setTitle('Description');

$formList->roll->addInput('timeline', 'sqlplaintext');
$formList->roll->input->timeline->setTitle('Timeline (Days)');

$formList->roll->addInput('type', 'sqlplaintext');
$formList->roll->input->type->setTitle('Type');

$formList->roll->addInput('task_link', 'sqllinks');
$formList->roll->input->task_link->setLinks('#');
$formList->roll->input->task_link->setTitle('Tasks');
$formList->roll->input->task_link->setFieldName('title as task_link');
$formList->roll->input->task_link->setDisplayFunction(function ($title) {
    global $Bbc;
    $url = $Bbc->mod['circuit'] . '.interns_tasks_list&task_title=' . urlencode($title);
    return '<a href="' . $url . '" class="btn btn-xs btn-primary">Lihat Pengerjaan</a>';
});

$formList->roll->action();

echo '<div class="panel panel-default">';
echo '<div class="panel-heading"><h3 class="panel-title">Daftar Tugas</h3></div>';
echo '<div class="panel-body">' . $formList->roll->getForm() . '</div>';
echo '</div>';

echo '<div class="panel panel-default">';
echo '<div class="panel-heading"><h3 class="panel-title">Add Task</h3></div>';
echo '<div class="panel-body">' . $formAdd->edit->getForm() . '</div>';
echo '</div>';
?>

<div class="col-xs-12 no-both">
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title" data-toggle="collapse" href="#import_tasks_panel" style="cursor:pointer;">
        <?php echo icon('fa-file-excel-o') ?> Klik disini untuk Manage Task (Import/Download)
      </h4>
    </div>
    <div id="import_tasks_panel" class="panel-collapse collapse">
      <form action="" method="POST" enctype="multipart/form-data">
        <div class="panel-body">
          <div class="form-group">
            <label>Upload File Excel (.xlsx atau .xls)</label>
            <input type="file" name="excel" class="form-control" accept=".xlsx,.xls" />
            <div class="help-block">Pastikan kolom: Title, Description, Timeline, Type.</div>
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