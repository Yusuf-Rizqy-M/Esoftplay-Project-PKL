<?php
if (!defined('_VALID_BBC')) exit('No direct script access allowed');

// === DOWNLOAD SAMPLE - UPDATE FORMAT ===
if (!empty($_GET['act']) && $_GET['act'] == 'sample_task') {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment;filename="sample_import_tasks.csv"');
    header('Cache-Control: no-cache, no-store, must-revalidate');
    header('Pragma: no-cache');
    header('Expires: 0');
    echo "title,description,timeline,type\n";
    echo "Install Linux,Install Linux Mint atau Ubuntu pada laptop intern,6,Website\n";
    echo "Setup Development Environment,Install VSCode Git PHP Node.js,5,Website\n";
    echo "Belajar Git Dasar,Push project pertama ke GitHub,3,Website\n";
    die();
}


// search
$formSearch = _lib('pea', 'interns_tasks');
$formSearch->initSearch();

// KEYWORD UNTUK SEARCH TITLE - LEBIH EFISIEN UNTUK DATA BANYAK
$formSearch->search->addInput('keyword', 'keyword');
$formSearch->search->input->keyword->setTitle('Search Task Title');
$formSearch->search->input->keyword->addSearchField('title', false); // Search di field title

// KEYWORD UNTUK SEARCH TYPE - TEXT INPUT BUKAN DROPDOWN
$formSearch->search->addInput('type_keyword', 'keyword');
$formSearch->search->input->type_keyword->setTitle('Search Type');
$formSearch->search->input->type_keyword->addSearchField('type', false); // Search di field type

$add_sql = $formSearch->search->action();

// --- MULAI PENAMBAHAN GAP ---
echo '<div style="margin-bottom: 20px;">'; // Memberikan jarak bawah 20px
echo $formSearch->search->getForm();
echo '</div>';
// --- SELESAI PENAMBAHAN GAP ---

include 'interns_tasks_edit.php';
// list tasks
$formList = _lib('pea', 'interns_tasks');
$formList->initRoll($add_sql . ' ORDER BY id DESC', 'id');
$formList->roll->setSaveTool(false);
$formList->roll->setDeleteTool(true);
$formList->roll->addInput('id', 'sqlplaintext');
$formList->roll->input->id->setDisplayColumn(false);

$formList->roll->addInput('title', 'sqllinks');
$formList->roll->input->title->setLinks($Bbc->mod['circuit'] . '.interns_tasks_edit');
$formList->roll->input->title->setTitle('Title');

$formList->roll->addInput('description', 'sqlplaintext');
$formList->roll->input->description->setTitle('Description');

$formList->roll->addInput('timeline', 'sqlplaintext');
$formList->roll->input->timeline->setTitle('Timeline (Days)');

$formList->roll->addInput('type', 'sqlplaintext');
$formList->roll->input->type->setTitle('Type');

// ========== CREATED & UPDATED - HIDE BY DEFAULT ==========
$formList->roll->addInput('created', 'sqlplaintext');
$formList->roll->input->created->setTitle('Created');
$formList->roll->input->created->setDateFormat('d M Y, H:i');
$formList->roll->input->created->setDisplayColumn(false); // HIDE BY DEFAULT

$formList->roll->addInput('updated', 'sqlplaintext');
$formList->roll->input->updated->setTitle('Updated');
$formList->roll->input->updated->setDateFormat('d M Y, H:i');
$formList->roll->input->updated->setDisplayColumn(false); // HIDE BY DEFAULT

// === MODIFIKASI: BUTTON LIHAT PENGERJAAN ===
$formList->roll->addInput('task_link', 'sqllinks');
$formList->roll->input->task_link->setLinks($Bbc->mod['circuit'].'.interns_tasks_list');
$formList->roll->input->task_link->setTitle('Tasks');
$formList->roll->input->task_link->setFieldName('id as detail');
$formList->roll->input->task_link->setDisplayFunction(function($row) {
    global $Bbc;
    // Redirect ke interns_tasks_list dengan filter task_id
    $url = $Bbc->mod['circuit'].'.interns_tasks_list&filter_task_id=' . intval($row);
    return '<a href="'.$url.'" class="btn btn-xs btn-primary">Lihat Pengerjaan</a>';
});

$formList->roll->action();
$formList->roll->onDelete(true);
echo '<div class="panel panel-default">';
echo '<div class="panel-heading"><h3 class="panel-title">Daftar Tugas</h3></div>';
echo '<div class="panel-body">';
echo $formList->roll->getForm();
echo '</div>';
echo '</div>';

// FORM ADD / EDIT TASK
echo '<div class="panel panel-default">';
echo '<div class="panel-heading"><h3 class="panel-title">Add Task</h3></div>';
echo '<div class="panel-body">';
echo $formAdd->edit->getForm();
echo '</div>';
echo '</div>';
?>

<style>
.loading-overlay{position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(255,255,255,.95);z-index:9999;display:flex;flex-direction:column;justify-content:center;align-items:center;text-align:center}
.loader-spinner{border:8px solid #f3f3f3;border-top:8px solid #3498db;border-radius:50%;width:60px;height:60px;animation:spin 1s linear infinite;margin-bottom:20px}
@keyframes spin{0%{transform:rotate(0deg)}100%{transform:rotate(360deg)}}
</style>

<!-- IMPORT MASTER TASK - UI PERSIS SAMA DENGAN INTERNS -->
<div class="col-xs-12 no-both">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title" data-toggle="collapse" href="#import_tasks_panel" style="cursor:pointer;">
                <?php echo icon('fa-file-excel-o') ?> klik disini untuk import data intern task dari CSV
            </h4>
        </div>
        <div id="import_tasks_panel" class="panel-collapse collapse">
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="panel-body">
                    <div class="form-group">
                        <label>Upload File CSV</label>
                        <input type="file" name="excel_task" class="form-control" accept=".csv" />
                        <div class="help-block">
                            Urutan kolom: title, description, timeline, type.<br>
                            Download contoh: <a href="?mod=interns.interns_tasks&act=sample_task" style="text-decoration:underline;">di sini</a>
                        </div>
                    </div>
                </div>
                <div class="panel-footer">
                    <button type="submit" name="import_task" value="upload" class="btn btn-primary">
                        <?php echo icon('fa-upload') ?> Upload Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
// === PROSES IMPORT CSV - PERSIS SAMA DENGAN INTERNS ===
if (!empty($_POST['import_task']) && $_POST['import_task'] == 'upload' && !empty($_FILES['excel_task']['tmp_name'])) {
    global $db;
    $file = $_FILES['excel_task']['tmp_name'];
    $handle = fopen($file, "r");
    
    if ($handle === false) {
        echo '<div class="alert alert-danger" id="import-error-alert" style="margin-top:20px;">
                <h4>Gagal Import:</h4>
                <ul><li>Gagal membuka file CSV!</li></ul>
                <button type="button" class="btn btn-danger" onclick="closeErrorAndOpenPanel()">Tutup & Perbaiki</button>
              </div>';
    } else {
        $success = $fail = 0;
        $row = 0;
        $error_logs = [];
        $success_titles = [];
        
        while (($data = fgetcsv($handle, 1000, ",")) !== false) {
            $row++;
            
            // Skip header row
            if ($row == 1) continue;
            
            // Skip empty rows
            if (count($data) < 1) continue;
            
            // Ambil data dari CSV: title, description, timeline, type
            $title = trim($data[0] ?? '');
            $desc = trim($data[1] ?? '');
            $timeline = trim($data[2] ?? '');
            $type = trim($data[3] ?? '');
            
            // Validasi: title tidak boleh kosong
            if (empty($title)) {
                $error_logs[] = "Baris $row: Title kosong";
                $fail++;
                continue;
            }
            
            // Validasi: timeline harus angka (jika ada)
            if (!empty($timeline) && !is_numeric($timeline)) {
                $error_logs[] = "Baris $row ($title): Timeline harus berupa angka!";
                $fail++;
                continue;
            }
            
            // Cek duplikat title
            $is_exist = $db->getOne("SELECT id FROM interns_tasks WHERE title='" . addslashes($title) . "'");
            if ($is_exist) {
                $error_logs[] = "Baris $row ($title): Task sudah ada";
                $fail++;
                continue;
            }
            
            // Insert task dengan field baru: title, description, timeline, type
            $timeline_sql = !empty($timeline) && is_numeric($timeline) ? intval($timeline) : 'NULL';
            $type_sql = !empty($type) ? "'" . addslashes($type) . "'" : 'NULL';
            
            $q = "INSERT INTO interns_tasks (title, description, timeline, type, created) 
                  VALUES ('" . addslashes($title) . "', '" . addslashes($desc) . "', $timeline_sql, $type_sql, NOW())";
            
            if ($db->Execute($q)) {
                $success_titles[] = $title;
                $success++;
            } else {
                $error_logs[] = "Baris $row ($title): Gagal insert - " . $db->ErrorMsg();
                $fail++;
            }
        }
        
        fclose($handle);
        
        // ERROR HANDLING - PERSIS SAMA DENGAN INTERNS
        if ($fail > 0) {
            echo '<div class="alert alert-danger" id="import-error-alert" style="margin-top:20px;">
                    <h4>Gagal Import:</h4><ul>';
            foreach ($error_logs as $log) {
                echo "<li>$log</li>";
            }
            echo '</ul><button type="button" class="btn btn-danger" onclick="closeErrorAndOpenPanel()">Tutup & Perbaiki</button></div>';
            
            // JavaScript - IDENTIK DENGAN INTERNS
            echo '<script>
            function closeErrorAndOpenPanel() {
                // Sembunyikan alert error
                document.getElementById("import-error-alert").style.display = "none";
                
                // Buka panel import CSV
                if(typeof jQuery !== "undefined") {
                    jQuery("#import_tasks_panel").collapse("show");
                }
                
                // Scroll ke panel import
                setTimeout(function() {
                    var panel = document.getElementById("import_tasks_panel");
                    if(panel) {
                        panel.scrollIntoView({ behavior: "smooth", block: "start" });
                    }
                }, 300);
            }
            </script>';
        }
        
        // SUCCESS OVERLAY
        if ($success > 0) {
            echo '<div class="loading-overlay">
                    <div class="loader-spinner"></div>
                    <h3>Import Berhasil!</h3>
                    <p>' . $success . ' data ditambahkan.</p>
                  </div>';
            
            $redirect_url = 'index.php?mod=interns.interns_tasks';
            echo '<script>
                setTimeout(function() {
                    window.location.href = "' . $redirect_url . '";
                }, 2000);
            </script>';
        }
    }
}
?>