<?php
if (!defined('_VALID_BBC')) exit('No direct script access allowed');
?>
<style>
.loading-overlay{position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(255,255,255,.95);z-index:9999;display:flex;flex-direction:column;justify-content:center;align-items:center;text-align:center}
.loader-spinner{border:8px solid #f3f3f3;border-top:8px solid #3498db;border-radius:50%;width:60px;height:60px;animation:spin 1s linear infinite;margin-bottom:20px}
@keyframes spin{0%{transform:rotate(0deg)}100%{transform:rotate(360deg)}}
</style>
<?php
if (!empty($_GET['act']) && $_GET['act'] == 'sample_tasklist') {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment;filename="sample_import_tasklist.csv"');
    header('Cache-Control: no-cache, no-store, must-revalidate');
    header('Pragma: no-cache');
    header('Expires: 0');
    echo "task_title,intern_email,notes,status\n";
    echo "Install Linux,jojo@gmail.com,kerjakan dalam 1 minggu,To Do\n";
    echo "Create Project Framework Esoftplay,yusufhabib290@gmail.com,install VSCode dan Git,To Do\n";
    die();
}

if (!empty($_POST['transfer']) && $_POST['transfer']=='upload' && !empty($_FILES['excel']['tmp_name'])) {
    global $db;
    $file=$_FILES['excel']['tmp_name'];
    $handle=fopen($file,"r");
    $success=0;$fail=0;$row=0;$error_logs=[];
    
    if ($handle === false) {
        $_SESSION['import_result'] = [
            'type' => 'error',
            'message' => 'Gagal membuka file CSV!',
            'errors' => ['Gagal membuka file CSV!']
        ];
        header('Location: index.php?mod=interns.interns_tasks_list');
        exit;
    } else {
        while(($data=fgetcsv($handle,1000,","))!==false){
            $row++;
            if($row==1) continue;
            if (empty($data[0]) && empty($data[1])) continue;
            
            $task_title = trim($data[0]??'');
            $intern_email = strtolower(trim($data[1]??''));
            $notes = trim($data[2]??'');
            $status_text = trim($data[3]??'To Do');
            
            $status_map = [
                'To Do' => 1,
                'In Progress' => 2,
                'Submit' => 3,
                'Revised' => 4,
                'Done' => 5,
                'Cancel' => 6
            ];
            $status = isset($status_map[$status_text]) ? $status_map[$status_text] : 1;
            
            if(empty($task_title)){ 
                $error_logs[]="Baris $row: Task title kosong"; 
                $fail++; 
                continue; 
            }
            if(empty($intern_email)){ 
                $error_logs[]="Baris $row: Intern email kosong"; 
                $fail++; 
                continue; 
            }
            if(!filter_var($intern_email, FILTER_VALIDATE_EMAIL)){
                $error_logs[]="Baris $row ($intern_email): Format email tidak valid";
                $fail++;
                continue;
            }
            
            $task = $db->getRow("SELECT id FROM interns_tasks WHERE title='".addslashes($task_title)."'");
            if(!$task){ 
                $error_logs[]="Baris $row ($task_title): Task tidak ditemukan"; 
                $fail++; 
                continue; 
            }
            
            $intern = $db->getRow("SELECT id, name FROM interns WHERE email='".addslashes($intern_email)."'");
            if(!$intern){ 
                $error_logs[]="Baris $row ($intern_email): Intern tidak ditemukan"; 
                $fail++; 
                continue; 
            }
            
            $is_exist = $db->getOne("SELECT id FROM interns_tasks_list WHERE interns_id={$intern['id']} AND interns_tasks_id={$task['id']}");
            if($is_exist){ 
                $error_logs[]="Baris $row ({$intern['name']}): Tugas '$task_title' sudah diberikan"; 
                $fail++; 
                continue; 
            }
            
            $q = "INSERT INTO interns_tasks_list (interns_id, interns_tasks_id, notes, status, created, updated) 
                  VALUES ({$intern['id']}, {$task['id']}, '".addslashes($notes)."', {$status}, NOW(), NOW())";
            
            if($db->Execute($q)){
                $success++;
            } else {
                $error_logs[]="Baris $row: Gagal insert - " . $db->ErrorMsg();
                $fail++;
            }
        }
        fclose($handle);
        
        if($fail>0){
            $_SESSION['import_result'] = [
                'type' => 'error',
                'message' => 'Gagal Import:',
                'errors' => $error_logs,
                'success_count' => $success,
                'fail_count' => $fail
            ];
        } else if($success>0){
            $_SESSION['import_result'] = [
                'type' => 'success',
                'message' => 'Import Berhasil!',
                'success_count' => $success
            ];
        }
        
        header('Location: index.php?mod=interns.interns_tasks_list');
        exit;
    }
}

$import_message = '';
if (!empty($_SESSION['import_result'])) {
    $result = $_SESSION['import_result'];
    if ($result['type'] == 'error') {
        $import_message = '<div class="alert alert-danger alert-dismissible" role="alert" style="margin-bottom:15px;">
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                <h4><i class="fa fa-times-circle"></i> ' . $result['message'] . '</h4>
                <ul style="margin-bottom:10px;">';
        foreach($result['errors'] as $log) {
            $import_message .= "<li>$log</li>";
        }
        $import_message .= '</ul>';
        if (!empty($result['success_count'])) {
            $import_message .= '<p><strong>Berhasil:</strong> ' . $result['success_count'] . ' | <strong>Gagal:</strong> ' . $result['fail_count'] . '</p>';
        }
        $import_message .= '<button type="button" class="btn btn-danger" onclick="openImportPanel()"><i class="fa fa-edit"></i> Perbaiki</button></div>
            <script>
            function openImportPanel() {
                if(typeof jQuery !== "undefined") {
                    jQuery("#import_tasklist_panel").collapse("show");
                    setTimeout(function() {
                        var panel = document.getElementById("import_tasklist_panel");
                        if(panel) { panel.scrollIntoView({ behavior: "smooth", block: "start" }); }
                    }, 300);
                }
            }
            </script>';
    } else if ($result['type'] == 'success') {
        $import_message = '
            <div class="loading-overlay">
                <div class="loader-spinner"></div>
                <h3>Import Berhasil!</h3>
                <p>' . $result['success_count'] . ' data ditambahkan.</p>
            </div>
            <script>
                setTimeout(function() {
                    if(typeof jQuery !== "undefined") {
                        jQuery(".loading-overlay").fadeOut();
                    } else {
                        document.querySelector(".loading-overlay").style.display = "none";
                    }
                }, 2000);
            </script>';
    }
    unset($_SESSION['import_result']);
}

// ========== HANDLER FORCE_INTERN_ID - DENGAN PERIOD FILTER! ==========
if (!empty($_GET['force_intern_id']) && is_numeric($_GET['force_intern_id'])) {
    $force_intern_id = intval($_GET['force_intern_id']);
    
    // RESET SEMUA FILTER
    unset($_SESSION['search']['interns_tasks_list']);
    
    // SET HANYA filter intern_id
    $_SESSION['search']['interns_tasks_list']['search_intern_name'] = '';
    
    // SET period ke 'active' (default)
    $_SESSION['search']['interns_tasks_list']['search_period_filter'] = 'active';
    $_SESSION['period_filter_interns_tasks_list'] = 'active';
    
    // Ambil nama intern untuk ditampilkan di filter
    global $db;
    $intern_name = $db->getOne("SELECT name FROM interns WHERE id = {$force_intern_id}");
    if ($intern_name) {
        $_SESSION['search']['interns_tasks_list']['search_intern_name'] = $intern_name;
    }
    
    // REDIRECT ke URL bersih dengan JavaScript
    ?>
    <script type="text/javascript">
        window.location.href = 'index.php?mod=interns.interns_tasks_list';
    </script>
    <?php
    exit;
}

// ========== PERIOD FILTER LOGIC ==========
// Ambil period_filter dari GET atau SESSION (default: 'active')
if (isset($_GET['period_filter'])) {
    $period_filter = $_GET['period_filter'];
    $_SESSION['period_filter_interns_tasks_list'] = $period_filter;
} else {
    $period_filter = isset($_SESSION['period_filter_interns_tasks_list']) ? $_SESSION['period_filter_interns_tasks_list'] : 'active';
}

$formSearch = _lib('pea', 'interns_tasks_list');
$formSearch->initSearch();

// ========== UBAH FILTER MENJADI INPUT TEXT (KEYWORD) ==========
// Filter Intern Name - MENGGUNAKAN KEYWORD (INPUT TEXT)
$formSearch->search->addInput('intern_name', 'keyword');
$formSearch->search->input->intern_name->setTitle('Filter by Intern Name');

// Filter Task - MENGGUNAKAN KEYWORD (INPUT TEXT)
$formSearch->search->addInput('task_title', 'keyword');
$formSearch->search->input->task_title->setTitle('Filter by Task');

if (!empty($_GET['filter_task_id']) && is_numeric($_GET['filter_task_id'])) {
    $task_id = intval($_GET['filter_task_id']);
    global $db;
    $task_title = $db->getOne("SELECT title FROM interns_tasks WHERE id = {$task_id}");
    if ($task_title) {
        $_SESSION['search']['interns_tasks_list']['search_task_title'] = $task_title;
    }
}

$formSearch->search->addInput('status', 'select');
$formSearch->search->input->status->setTitle(lang('Status'));
$formSearch->search->input->status->addOption(lang('---- Filter by Status ----'), '');
$formSearch->search->input->status->addOption('To Do', '1');
$formSearch->search->input->status->addOption('In Progress', '2');
$formSearch->search->input->status->addOption('Submit', '3');
$formSearch->search->input->status->addOption('Revised', '4');
$formSearch->search->input->status->addOption('Done', '5');
$formSearch->search->input->status->addOption('Cancel', '6');

$formSearch->search->addInput('notes', 'keyword');
$formSearch->search->input->notes->setTitle('Notes');
$formSearch->search->input->notes->addSearchField('notes', false);

$keyword = $formSearch->search->keyword();
$add_sql = $formSearch->search->action();

// ========== MANUAL FILTER UNTUK INTERN NAME DAN TASK TITLE ==========
global $db;

if (!empty($keyword['intern_name'])) {
	$intern_name = addslashes(trim($keyword['intern_name']));
    $intern_ids   = $db->getCol("SELECT id FROM interns WHERE name = '{$intern_name}'");
    
    if (empty($intern_ids)) {
		$intern_ids = [0];
	}

	$ids_string = implode(',', $intern_ids);
	$add_sql   .= " AND interns_id IN ({$ids_string})";
}

if (!empty($keyword['task_title'])) {
	$task_title = addslashes(trim($keyword['task_title']));
    $task_ids   = $db->getCol("SELECT id FROM interns_tasks WHERE title = '{$task_title}'");
    
    if (empty($task_ids)) {
		$task_ids = [0];
    }

	$ids_string = implode(',', $task_ids);
	$add_sql   .= " AND interns_tasks_id IN ({$ids_string})";
}

// ========== APPLY PERIOD FILTER ==========
if ($period_filter === 'active') {
    global $db;
    // Ambil ID intern yang ACTIVE (CURDATE() BETWEEN start_date AND end_date)
    $active_ids = $db->getCol("SELECT id FROM interns WHERE CURDATE() BETWEEN start_date AND end_date");
    
    if (!empty($active_ids)) {
        $ids_string = implode(',', $active_ids);
        // Tambahkan ke SQL condition
        if (stripos($add_sql, 'WHERE') !== false) {
            $add_sql .= " AND interns_tasks_list.interns_id IN ($ids_string)";
        } else {
            $add_sql .= " WHERE interns_tasks_list.interns_id IN ($ids_string)";
        }
    } else {
        // Jika tidak ada intern aktif, tampilkan hasil kosong
        if (stripos($add_sql, 'WHERE') !== false) {
            $add_sql .= " AND 1=0";
        } else {
            $add_sql .= " WHERE 1=0";
        }
    }
}

// TAMPILKAN FORM SEARCH
echo '<div style="margin-bottom: 20px;">';
echo $formSearch->search->getForm();
echo '</div>';

// INJECT PERIOD FILTER TANPA LABEL KE FORM SEARCH (POSISI PALING KIRI)
echo '<script>
(function() {
    // Buat Period Filter dropdown (TANPA LABEL)
    var periodSelect = document.createElement("select");
    periodSelect.className = "form-control";
    periodSelect.id = "period_filter_custom";
    periodSelect.style.display = "inline-block";
    periodSelect.style.width = "auto";
    periodSelect.style.marginRight = "10px";
    periodSelect.onchange = function() { changePeriodFilter(this.value); };
    
    var option1 = document.createElement("option");
    option1.value = "active";
    option1.text = "Active Only";
    '.($period_filter === 'active' ? 'option1.selected = true;' : '').'
    periodSelect.appendChild(option1);
    
    var option2 = document.createElement("option");
    option2.value = "all";
    option2.text = "Show All";
    '.($period_filter === 'all' ? 'option2.selected = true;' : '').'
    periodSelect.appendChild(option2);
    
    // Cari form search
    var searchForms = document.querySelectorAll("form");
    var targetForm = null;
    
    for (var i = 0; i < searchForms.length; i++) {
        if (searchForms[i].querySelector("input[name*=\'search\']") || 
            searchForms[i].querySelector("select[name*=\'search\']")) {
            targetForm = searchForms[i];
            break;
        }
    }
    
    if (targetForm) {
        // Insert Period Filter sebagai element pertama dalam form
        targetForm.insertBefore(periodSelect, targetForm.firstChild);
    }
})();

function changePeriodFilter(value) {
    var currentUrl = window.location.href.split("?")[0];
    window.location.href = currentUrl + "?mod=interns.interns_tasks_list&period_filter=" + value;
}
</script>';


include 'interns_tasks_list_edit.php';

// ========== INISIALISASI ROLL TANPA JOIN (KEMBALI KE ORIGINAL) ==========
$formList = _lib('pea', 'interns_tasks_list');

$formList->initRoll($add_sql . ' ORDER BY id DESC', 'id');
$formList->roll->setDeleteTool(false);
$formList->roll->setSaveTool(false);

$formList->roll->addInput('id', 'sqlplaintext');
$formList->roll->input->id->setDisplayColumn(false);

$formList->roll->addInput('interns_tasks_id', 'sqllinks');
$formList->roll->input->interns_tasks_id->setTitle('Task');
$formList->roll->input->interns_tasks_id->setLinks('#');
$formList->roll->input->interns_tasks_id->setFieldName('interns_tasks_id as task_detail');
$formList->roll->input->interns_tasks_id->setDisplayFunction(function($task_id) {
    global $db;
    $task = $db->getRow("SELECT title, description, timeline, type FROM interns_tasks WHERE id = " . intval($task_id));
    if ($task) {
        $title = htmlspecialchars($task['title']);
        $description = nl2br(htmlspecialchars($task['description']));
        $timeline = htmlspecialchars($task['timeline']);
        $type = htmlspecialchars($task['type']);
        $modal_id = 'taskDetailModal_' . $task_id;
        return '<a href="#" data-toggle="modal" data-target="#'.$modal_id.'" style="color: #a50010c4;">'.$title.'</a>
        <div class="modal fade" id="'.$modal_id.'" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #f5f5f5; border-bottom: 1px solid #ddd;">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title" style="color: #333;">Detail Task</h4>
                    </div>
                    <div class="modal-body" style="padding: 20px;">
                        <div style="margin-bottom: 15px;">
                            <strong style="color: #333;">Task</strong><br>
                            <span style="color: #555;">'.$title.'</span>
                        </div>
                        <div style="margin-bottom: 15px;">
                            <strong style="color: #333;">Notes</strong><br>
                            <span style="color: #555;">'.$description.'</span>
                        </div>
                        <div style="margin-bottom: 15px;">
                            <strong style="color: #333;">Timeline (Days)</strong><br>
                            <span style="color: #555;">'.$timeline.'</span>
                        </div>
                        <div style="margin-bottom: 15px;">
                            <strong style="color: #333;">Type</strong><br>
                            <span style="color: #555;">'.$type.'</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>';
    }
    return '-';
});

$formList->roll->addInput('interns_id', 'selecttable');
$formList->roll->input->interns_id->setTitle('Intern Name');
$formList->roll->input->interns_id->setPlaintext(true);
$formList->roll->input->interns_id->setReferenceTable('interns');
$formList->roll->input->interns_id->setReferenceField('name', 'id');

// Kolom Notes
$formList->roll->addInput('notes', 'sqlplaintext');
$formList->roll->input->notes->setTitle('Notes');

$formList->roll->addInput('status', 'sqlplaintext');
$formList->roll->input->status->setDisplayFunction(function ($value) {
    $colors = [1=>'#6c757d', 2=>'#007bff', 3=>'#ffc107', 4=>'#fd7e14', 5=>'#28a745', 6=>'#dc3545'];
    $texts = [1=>'To Do', 2=>'In Progress', 3=>'Submit', 4=>'Revised', 5=>'Done', 6=>'Cancel'];
    $color = $colors[$value] ?? '#6c757d';
    $text = $texts[$value] ?? 'Unknown';
    $tcolor = ($value == 3) ? 'black' : 'white';
    return '<span class="label" style="background-color: '.$color.'; color: '.$tcolor.'; padding: 5px 10px; border-radius: 12px;">'.$text.'</span>';
});

// ========== ✅ KOLOM TIMELINE (DAYS) - PERSIS SEPERTI INTERNS_TASKS ==========
$formList->roll->addInput('timeline_display', 'sqlplaintext');
$formList->roll->input->timeline_display->setTitle('Timeline (Days)');
$formList->roll->input->timeline_display->setFieldName('id as timeline_display');
$formList->roll->input->timeline_display->setDisplayFunction(function ($id) {
    global $db;
    
    if (!$id) {
        return '-';
    }
    
    // Query untuk ambil status dan task_id dari interns_tasks_list
    $row = $db->getRow("SELECT status, interns_tasks_id FROM interns_tasks_list WHERE id = " . intval($id));
    
    if (!$row) {
        return '-';
    }
    
    $status = intval($row['status']);
    $task_id = intval($row['interns_tasks_id']);
    
    // ✅ Hanya tampilkan timeline jika status >= 2 (In Progress ke atas)
    if ($status >= 2 && $task_id) {
        // Query untuk ambil timeline dari interns_tasks
        $timeline = $db->getOne("SELECT timeline FROM interns_tasks WHERE id = " . $task_id);
        
        if ($timeline) {
            // ✅ TAMPILAN SAMA PERSIS DENGAN INTERNS_TASKS (HANYA ANGKA)
            return htmlspecialchars($timeline);
        }
    }
    
    // ✅ Jika status To Do atau timeline tidak ada, tampilkan '-'
    return '-';
});

$formList->roll->addInput('status_intern', 'sqlplaintext');
$formList->roll->input->status_intern->setTitle('Status Intern');
$formList->roll->input->status_intern->setFieldName('(SELECT CASE WHEN CURDATE() < start_date THEN "Coming Soon" WHEN CURDATE() BETWEEN start_date AND end_date THEN "Active" ELSE "Ended" END FROM interns WHERE id = interns_id) as status_intern');
$formList->roll->input->status_intern->setDisplayFunction(function ($value) {
    $colors = [
        'Coming Soon' => '#007bff',
        'Active' => '#28a745',
        'Ended' => '#dc3545'
    ];
    $color = $colors[$value] ?? '#6c757d';
    return '<span class="label" style="background-color: '.$color.'; color: white; padding: 5px 12px; border-radius: 12px; font-size: 11px; font-weight: 600; display: inline-block;">'.$value.'</span>';
});

// ========== CREATED & UPDATED - HIDE BY DEFAULT ==========
$formList->roll->addInput('created', 'sqlplaintext');
$formList->roll->input->created->setTitle('Created');
$formList->roll->input->created->setDateFormat('d M Y, H:i');
$formList->roll->input->created->setDisplayColumn(false);

$formList->roll->addInput('updated', 'sqlplaintext');
$formList->roll->input->updated->setTitle('Updated');
$formList->roll->input->updated->setDateFormat('d M Y, H:i');
$formList->roll->input->updated->setDisplayColumn(false);

// Kolom Action - DI PALING KANAN (SETELAH STATUS INTERN)
$formList->roll->addInput('action', 'sqllinks');
$formList->roll->input->action->setTitle('Action');
$formList->roll->input->action->setLinks($Bbc->mod['circuit'] . '.interns_tasks_list_edit');
$formList->roll->input->action->setModal(true);
$formList->roll->input->action->setFieldName('id as action');
$formList->roll->input->action->setDisplayFunction(function($id) {
    return '<button class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> Edit</button>';
});

$formList->roll->action();

if (!empty($_POST['roll_submit_update'])) {
    if (!empty($_POST['roll_status']) && is_array($_POST['roll_status'])) {
        foreach ($_POST['roll_id'] as $index => $id) {
            $id = (int)$id;
            $new_status = (int)($_POST['roll_status'][$index] ?? 1);
            $interns_id = $db->getOne("SELECT interns_id FROM interns_tasks_list WHERE id = {$id}");
            $db->Execute("INSERT INTO interns_tasks_list_history (interns_id, interns_tasks_list_id, status, created) VALUES (".intval($interns_id).", {$id}, {$new_status}, NOW())");
        }
    }
}

$output = $formList->roll->getForm();
if (preg_match_all('/<option value="(\d+)"[^>]*>(\d+)<\/option>/', $output, $matches, PREG_SET_ORDER)) {
    foreach ($matches as $match) {
        $id = $match[1];
        $latest = $db->getOne("SELECT status FROM interns_tasks_list_history WHERE interns_tasks_list_id = ".intval($id)." ORDER BY created DESC LIMIT 1");
        if ($latest !== null) {
            $output = str_replace($match[0], str_replace('selected', '', $match[0]), $output);
            if ($match[1] == $latest) { $output = str_replace("value=\"{$latest}\"", "value=\"{$latest}\" selected", $output); }
        }
    }
}

echo '<div class="panel panel-default"><div class="panel-heading"><h3 class="panel-title">Daftar Tugas Intern</h3></div><div class="panel-body">'.$output.'</div></div>';
// ========== TAMBAHKAN FORM ADD TASK DI SINI ==========
echo '<div class="panel panel-default">';
echo '<div class="panel-heading"><h3 class="panel-title">Add Intern Task</h3></div>';
echo '<div class="panel-body">';
echo $formAdd->edit->getForm();
echo '</div>';
echo '</div>';

if (!empty($import_message)) { echo '<div class="col-xs-12 no-both">'.$import_message.'</div>'; }
?>

<div class="col-xs-12 no-both">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title" data-toggle="collapse" href="#import_tasklist_panel" style="cursor:pointer;">
                <?php echo icon('fa-file-excel-o') ?> klik disini untuk import data intern task list dari CSV
            </h4>
        </div>
        <div id="import_tasklist_panel" class="panel-collapse collapse">
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="panel-body">
                    <div class="form-group">
                        <label>Upload File CSV</label>
                        <input type="file" name="excel" class="form-control" accept=".csv" />
                        <div class="help-block">
                            Urutan kolom: <code>task_title, intern_email, notes, status</code><br>
                            Download contoh: <a href="?mod=interns.interns_tasks_list&act=sample_tasklist" style="text-decoration:underline;">di sini</a>
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