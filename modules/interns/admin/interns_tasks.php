<?php
if (!defined('_VALID_BBC')) exit('No direct script access allowed');

// === DOWNLOAD SAMPLE ===
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

// search
$formSearch = _lib('pea', 'interns_tasks');
$formSearch->initSearch();
$formSearch->search->addInput('title','keyword');
$formSearch->search->input->title->setTitle('Title');
$formSearch->search->input->title->addSearchField('title', false);
$add_sql = $formSearch->search->action();
echo $formSearch->search->getForm();
include 'interns_tasks_edit.php';
// list tasks
$formList = _lib('pea', 'interns_tasks');
$formList->initRoll($add_sql.' ORDER BY id DESC', 'id');
$formList->roll->setSaveTool(false);
$formList->roll->setDeleteTool(true);
$formList->roll->addInput('id','sqlplaintext');
$formList->roll->input->id->setDisplayColumn(false);
$formList->roll->addInput('title','sqllinks');
$formList->roll->input->title->setLinks($Bbc->mod['circuit'].'.interns_tasks_edit');
$formList->roll->input->title->setTitle('Title');
$formList->roll->addInput('description','sqlplaintext');
$formList->roll->input->description->setTitle('Description');
$formList->roll->addInput('created','sqlplaintext');
$formList->roll->input->created->setTitle('Created');
$formList->roll->addInput('updated','sqlplaintext');
$formList->roll->input->updated->setTitle('Updated');
$formList->roll->action();
$formList->roll->onDelete(true);
echo '<div class="panel panel-default">';
echo '<div class="panel-heading"><h3 class="panel-title">Daftar Task</h3></div>';
echo '<div class="panel-body">';
echo $formList->roll->getForm();
echo '</div>';
echo '</div>';

// FORM ADD / EDIT TASK

echo '<div class="panel panel-default">';
echo '<div class="panel-heading"><h3 class="panel-title">Add / Edit Task</h3></div>';
echo '<div class="panel-body">';
echo $formAdd->edit->getForm();
echo '</div>';
echo '</div>';
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
    Upload file daftar task dalam format CSV. Download sample file
    <a href="?mod=interns.interns_tasks&act=sample_task">sini</a>
    (urutan: title,description).
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
        echo '<div class="alert alert-danger">Gagal membuka file CSV!</div>';
        exit;
    }
    $success = $fail = 0;
    $row = 0;
    $messages = [];
    $success_titles = [];
    while (($data = fgetcsv($handle, 1000, ",")) !== false) {
        $row++;
        if ($row == 1) continue;
        if (count($data) < 1) continue;
        $title = trim($data[0] ?? '');
        $desc = trim($data[1] ?? '');
        if (empty($title)) {
            $messages[] = '<li class="text-danger">Baris '.$row.': Skip - title kosong</li>';
            $fail++;
            continue;
        }
        if ($db->getOne("SELECT id FROM interns_tasks WHERE title='".addslashes($title)."'")) {
            $messages[] = '<li class="text-danger">Baris '.$row.': Skip - Task <b>'.htmlspecialchars($title).'</b> sudah ada</li>';
            $fail++;
            continue;
        }
        $q = "INSERT INTO interns_tasks (title, description, created) 
              VALUES ('".addslashes($title)."', '".addslashes($desc)."', NOW())";
        if ($db->Execute($q)) {
            $messages[] = '<li class="text-success">Baris '.$row.': <b>'.htmlspecialchars($title).'</b> berhasil ditambahkan</li>';
            $success_titles[] = $title;
            $success++;
        } else {
            $messages[] = '<li class="text-danger">Baris '.$row.': Gagal insert</li>';
            $fail++;
        }
    }
    fclose($handle);

    // Tampilkan hasil import detail
    echo '<div class="alert alert-info" style="margin-top: 15px;"><h4>Hasil Import Master Task:</h4><ul>';
    foreach ($messages as $msg) {
        echo $msg;
    }
    echo '</ul>';
    echo '<strong>Selesai!</strong> Berhasil: <span class="text-success">'.$success.'</span> | Gagal: <span class="text-danger">'.$fail.'</span></div>';

    // Jika berhasil, tampilkan loading overlay
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
        <strong>Task baru yang berhasil ditambahkan (<?php echo $success; ?>):</strong>
        <?php if (count($success_titles) <= 10): ?>
        <ul>
        <?php foreach ($success_titles as $title): ?>
            <li><?php echo htmlspecialchars($title); ?></li>
        <?php endforeach; ?>
        </ul>
        <?php else: ?>
        <ul>
        <?php for ($i = 0; $i < 10; $i++): ?>
            <li><?php echo htmlspecialchars($success_titles[$i]); ?></li>
        <?php endfor; ?>
        </ul>
        <p style="margin-top: 10px;">+ <?php echo ($success - 10); ?> task lainnya</p>
        <?php endif; ?>
    </div>
</div>
<script type="text/javascript">
setTimeout(function() {
    window.location.href = "<?php echo $redirect_url; ?>";
}, 5000);
</script>
<?php
    }
}
?>