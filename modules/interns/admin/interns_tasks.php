<?php if (!defined('_VALID_BBC')) exit('No direct script access allowed');

// === DOWNLOAD SAMPLE — DI PALING ATAS, BYPASS SEMUA ===
if (!empty($_GET['act']) && $_GET['act'] == 'sample_task') {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment;filename="sample_import_tasks.csv"');
    header('Cache-Control: no-cache, no-store, must-revalidate');
    header('Pragma: no-cache');
    header('Expires: 0');
    echo "title,description\n";
    echo "Install Linux,Install Linux Mint atau Ubuntu pada laptop intern\n";
    echo "Setup Development Environment,Install VSCode, Git, PHP, Node.js\n";
    echo "Belajar Git Dasar,Push project pertama ke GitHub\n";
    die();
}

//search
$formSearch = _lib('pea', 'interns_tasks');
$formSearch->initSearch();
$formSearch->search->addInput('title','keyword');
$formSearch->search->input->title->setTitle('Title'); 
$formSearch->search->input->title->addSearchField('title', false);
$add_sql = $formSearch->search->action();
echo $formSearch->search->getForm();



//list taks
$formList = _lib('pea', 'interns_tasks');
$formList->initRoll($add_sql.' ORDER BY id DESC', 'id');

$formList->roll->setSaveTool(false);
$formList->roll->setDeleteTool(true);

// id
$formList->roll->addInput('id','sqlplaintext');
$formList->roll->input->id->setDisplayColumn(false);

// title
$formList->roll->addInput('title','sqllinks');
$formList->roll->input->title->setLinks($Bbc->mod['circuit'].'.interns_tasks_edit');
$formList->roll->input->title->setTitle('Title');

// description
$formList->roll->addInput('description','sqlplaintext');
$formList->roll->input->description->setTitle('Description');

// created
$formList->roll->addInput('created','sqlplaintext');
$formList->roll->input->created->setTitle('Created');

// updated
$formList->roll->addInput('updated','sqlplaintext');
$formList->roll->input->updated->setTitle('Updated');

// action
$formList->roll->action();
$formList->roll->onDelete(true);

echo '<div class="panel panel-default">';
echo '<div class="panel-heading"><h3 class="panel-title">Daftar Task</h3></div>';
echo '<div class="panel-body">';
echo $formList->roll->getForm();
echo '</div>';
echo '</div>';

// =====================================================
// FORM ADD / EDIT TASK (DITARO DI BAWAH LIST)
// =====================================================
include 'interns_tasks_edit.php';  // File yang berisi formAdd

echo '<div class="panel panel-default">';
echo '<div class="panel-heading"><h3 class="panel-title">Add / Edit Task</h3></div>';
echo '<div class="panel-body">';
echo $formAdd->edit->getForm();
echo '</div>';
echo '</div>';

// =====================================================
// IMPORT MASTER TASK (CSV)
// =====================================================
?>
<!-- IMPORT MASTER TASK -->
<div class="col-xs-12 no-both">
    <div class="panel-group" id="accordion_tasks_import">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title collapsed" data-toggle="collapse" data-parent="#accordion_tasks_import" href="#import_tasks_panel" style="cursor: pointer;">
                    Klik Disini Untuk Import Task From Excel (CSV)
                </h4>
            </div>
            <div id="import_tasks_panel" class="panel-collapse collapse">
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="panel-body">
                        <div class="form-group">
                            <label>Upload File CSV</label>
                            <input type="file" name="excel_task" class="form-control" accept=".csv" required />
                            <div class="help-block">
                                Upload file daftar task dalam format CSV. Silahkan download "sample file" untuk menentukan kolom-kolom apa saja yang perlu diisikan di <a href="?mod=interns.interns_tasks&act=sample_task">sini</a> (urutan: title,description).
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <button type="submit" name="import_task" value="upload" class="btn btn-default">
                            <?php echo icon('fa-upload') ?> Upload Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
// === PROSES IMPORT CSV ===
if (!empty($_POST['import_task']) && $_POST['import_task'] == 'upload' && !empty($_FILES['excel_task']['tmp_name'])) {
    global $db;
    $file = $_FILES['excel_task']['tmp_name'];
    $handle = fopen($file, "r");
    if ($handle === false) {
        die('<div class="alert alert-danger">Gagal buka file!</div>');
    }
    $success = $fail = 0;
    $row = 0;
    echo '<div class="alert alert-info"><h4>Hasil Import Master Task:</h4><ul>';
    while (($data = fgetcsv($handle, 1000, ",")) !== false) {
        $row++;
        if ($row == 1) continue; // skip header
        if (count($data) < 1) continue;

        $title = trim($data[0]);
        $desc  = trim($data[1] ?? '');

        if (empty($title)) {
            echo "<li>Baris $row: Skip (title kosong)</li>";
            $fail++;
            continue;
        }

        // Cek duplikat title
        if ($db->getOne("SELECT id FROM interns_tasks WHERE title='".addslashes($title)."'")) {
            echo "<li>Baris $row: Skip - Task <b>$title</b> sudah ada</li>";
            $fail++;
            continue;
        }

        $q = "INSERT INTO interns_tasks (title, description, created, updated)
              VALUES ('".addslashes($title)."', '".addslashes($desc)."', NOW(), NOW())";
        if ($db->Execute($q)) {
            echo "<li>Baris $row: <b>$title</b> berhasil ditambah</li>";
            $success++;
        } else {
            echo "<li>Baris $row: Gagal insert</li>";
            $fail++;
        }
    }
    fclose($handle);
    echo '</ul><b>Selesai!</b> Berhasil: '.$success.' | Gagal: '.$fail.'</div>';
}
?>