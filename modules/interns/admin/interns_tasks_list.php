<?php
if (!defined('_VALID_BBC')) exit('No direct script access allowed');

_func('download');

if (!empty($_POST['transfer'])) {
    if ($_POST['transfer'] == 'download') {
        $q = "SELECT t.title AS task_title, i.email AS intern_email, tl.notes, 
              CASE tl.status 
                WHEN 1 THEN 'To Do' WHEN 2 THEN 'In Progress' WHEN 3 THEN 'Submit' 
                WHEN 4 THEN 'Revised' WHEN 5 THEN 'Done' WHEN 6 THEN 'Cancel' 
              END AS status_text
              FROM interns_tasks_list AS tl
              LEFT JOIN interns AS i ON tl.interns_id=i.id
              LEFT JOIN interns_tasks AS t ON tl.interns_tasks_id=t.id
              ORDER BY tl.id DESC LIMIT 10";
        $r = $db->getAll($q);

        if (empty($r)) {
            $r = [
                ['task_title' => 'Install Linux', 'intern_email' => 'choirulanam@gmail.com', 'notes' => 'Gunakan Ubuntu 22.04', 'status_text' => 'To Do'],
                ['task_title' => 'Setup Dev Env', 'intern_email' => 'jojo@gmail.com', 'notes' => 'Install VSCode dan PHP', 'status_text' => 'In Progress']
            ];
        }

        foreach ($r as $k => &$val) {
            if ($k == 0) $val['Keterangan'] = 'Task Title & Email harus sudah terdaftar di database.';
            if ($k == 1) $val['Keterangan'] = 'Pilihan Status: To Do, In Progress, Submit, Revised, Done, Cancel.';
        }
        download_excel('Sample_Intern_Task_List_' . date('Y-m-d'), $r);
        die();
    }

    if ($_POST['transfer'] == 'upload') {
        $msg = '';
        if (!empty($_FILES['excel']['tmp_name']) && is_uploaded_file($_FILES['excel']['tmp_name'])) {
            $mimes = ['application/vnd.ms-excel', 'text/xls', 'text/xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
            if (in_array($_FILES['excel']['type'], $mimes)) {
                $excel_lib = _lib('excel')->read($_FILES['excel']['tmp_name']);
                $output = $excel_lib->sheet(1)->fetch();

                if (!empty($output) && is_array($output)) {
                    $headers = ['A' => 'Task Title', 'B' => 'Intern Email', 'C' => 'Notes', 'D' => 'Status'];
                    $is_valid = true;
                    foreach ($headers as $col => $title) {
                        if (trim($output[1][$col]) != $title) {
                            $is_valid = false;
                            break;
                        }
                    }

                    if ($is_valid) {
                        $success = 0;
                        $status_map = ['To Do' => 1, 'In Progress' => 2, 'Submit' => 3, 'Revised' => 4, 'Done' => 5, 'Cancel' => 6];
                        foreach ($output as $i => $cells) {
                            if ($i == 1 || empty($cells['A']) || empty($cells['B'])) continue;

                            $task_title = trim($cells['A']);
                            $intern_email = strtolower(trim($cells['B']));
                            $notes = trim($cells['C']);
                            $st_text = trim($cells['D']);
                            $status = isset($status_map[$st_text]) ? $status_map[$st_text] : 1;

                            $task_id = $db->getOne("SELECT id FROM interns_tasks WHERE title='" . addslashes($task_title) . "'");
                            $intern_id = $db->getOne("SELECT id FROM interns WHERE email='" . addslashes($intern_email) . "'");

                            if ($task_id && $intern_id) {
                                $is_exist = $db->getOne("SELECT 1 FROM interns_tasks_list WHERE interns_id=$intern_id AND interns_tasks_id=$task_id");
                                if (!$is_exist) {
                                    $q = "INSERT INTO interns_tasks_list SET interns_id=$intern_id, interns_tasks_id=$task_id, notes='" . addslashes($notes) . "', status=$status, created=NOW(), updated=NOW()";
                                    if ($db->Execute($q)) $success++;
                                }
                            }
                        }
                        $msg = msg("Upload berhasil. $success data pengerjaan baru ditambahkan.", 'success');
                    } else {
                        $msg = msg('Format kolom salah. Gunakan: Task Title, Intern Email, Notes, Status.', 'danger');
                    }
                }
            } else {
                $msg = msg('Format file harus Excel (.xlsx)', 'danger');
            }
        }
        if ($msg) echo $msg;
    }
}
?>
<style>
  .loading-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255, 255, 255, .95); z-index: 9999; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center }
  .loader-spinner { border: 8px solid #f3f3f3; border-top: 8px solid #3498db; border-radius: 50%; width: 60px; height: 60px; animation: spin 1s linear infinite; margin-bottom: 20px }
  @keyframes spin { 0% { transform: rotate(0deg) } 100% { transform: rotate(360deg) } }
</style>
<?php
if (!empty($_GET['force_intern_id']) && is_numeric($_GET['force_intern_id'])) {
    $force_intern_id = intval($_GET['force_intern_id']);
    unset($_SESSION['search']['interns_tasks_list']);
    $intern_data = $db->getRow("SELECT name, CASE WHEN CURDATE() < start_date THEN 'Coming Soon' WHEN CURDATE() BETWEEN start_date AND end_date THEN 'Active' ELSE 'Ended' END as status_intern FROM interns WHERE id = {$force_intern_id}");
    if ($intern_data) {
        $_SESSION['search']['interns_tasks_list']['search_intern_name'] = $intern_data['name'];
        $period_filter = ($intern_data['status_intern'] === 'Active') ? 'active' : 'all';
        $_SESSION['search']['interns_tasks_list']['search_period_filter'] = $period_filter;
        $_SESSION['period_filter_interns_tasks_list'] = $period_filter;
    }
    echo '<script>window.location.href = "index.php?mod=interns.interns_tasks_list";</script>';
    exit;
}

if (!empty($_GET['task_title'])) {
    $task_title_param = trim($_GET['task_title']);
    unset($_SESSION['search']['interns_tasks_list']['search_intern_name']);
    $_SESSION['search']['interns_tasks_list']['search_task_title'] = $task_title_param;
    $_SESSION['search']['interns_tasks_list']['search_period_filter'] = 'active';
    $_SESSION['period_filter_interns_tasks_list'] = 'active';
    echo '<script>window.location.href = "index.php?mod=interns.interns_tasks_list";</script>';
    exit;
}

$period_filter = isset($_GET['period_filter']) ? $_GET['period_filter'] : (isset($_SESSION['period_filter_interns_tasks_list']) ? $_SESSION['period_filter_interns_tasks_list'] : 'active');
$_SESSION['period_filter_interns_tasks_list'] = $period_filter;

$formSearch = _lib('pea', 'interns_tasks_list');
$formSearch->initSearch();
$formSearch->search->addInput('intern_name', 'keyword');
$formSearch->search->input->intern_name->setTitle('Filter by Intern Name');
$formSearch->search->addInput('task_title', 'keyword');
$formSearch->search->input->task_title->setTitle('Filter by Task');
$formSearch->search->addInput('status', 'select');
$formSearch->search->input->status->setTitle('Status');
$formSearch->search->input->status->addOption('---- Filter by Status ----', '');
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

if (!empty($keyword['intern_name'])) {
    $in_ids = $db->getCol("SELECT id FROM interns WHERE name LIKE '%" . addslashes(trim($keyword['intern_name'])) . "%'");
    $add_sql .= " AND interns_id IN (" . (empty($in_ids) ? '0' : implode(',', $in_ids)) . ")";
}
if (!empty($keyword['task_title'])) {
    $tk_ids = $db->getCol("SELECT id FROM interns_tasks WHERE title LIKE '%" . addslashes(trim($keyword['task_title'])) . "%'");
    $add_sql .= " AND interns_tasks_id IN (" . (empty($tk_ids) ? '0' : implode(',', $tk_ids)) . ")";
}
if ($period_filter === 'active') {
    $active_ids = $db->getCol("SELECT id FROM interns WHERE CURDATE() BETWEEN start_date AND end_date");
    $add_sql .= " AND interns_id IN (" . (empty($active_ids) ? '0' : implode(',', $active_ids)) . ")";
}

echo '<div style="margin-bottom: 20px;">' . $formSearch->search->getForm() . '</div>';
echo '<script>
(function() {
    var periodSelect = document.createElement("select");
    periodSelect.className = "form-control";
    periodSelect.style.display = "inline-block";
    periodSelect.style.width = "auto";
    periodSelect.style.marginRight = "10px";
    periodSelect.onchange = function() { window.location.href = "index.php?mod=interns.interns_tasks_list&period_filter=" + this.value; };
    var opt1 = new Option("Active Only", "active", ' . ($period_filter === "active" ? "true, true" : "") . ');
    var opt2 = new Option("Show All", "all", ' . ($period_filter === "all" ? "true, true" : "") . ');
    periodSelect.add(opt1); periodSelect.add(opt2);
    var targetForm = document.querySelector("form");
    if (targetForm) targetForm.insertBefore(periodSelect, targetForm.firstChild);
})();
</script>';

include 'interns_tasks_list_edit.php';

$formList = _lib('pea', 'interns_tasks_list');
$formList->initRoll($add_sql . ' ORDER BY id DESC', 'id');
$formList->roll->setDeleteTool(false);
$formList->roll->setSaveTool(false);

$formList->roll->addInput('interns_tasks_id', 'sqllinks');
$formList->roll->input->interns_tasks_id->setTitle('Task');
$formList->roll->input->interns_tasks_id->setLinks('#');
$formList->roll->input->interns_tasks_id->setDisplayFunction(function ($task_id) {
    global $db;
    $task = $db->getRow("SELECT title, description, timeline, type FROM interns_tasks WHERE id = " . intval($task_id));
    if (!$task) return '-';
    $modal_id = 'taskDetailModal_' . $task_id;
    return '<a href="#" data-toggle="modal" data-target="#' . $modal_id . '" style="color: #a50010c4;">' . htmlspecialchars($task['title']) . '</a>
    <div class="modal fade" id="' . $modal_id . '" tabindex="-1"><div class="modal-dialog"><div class="modal-content">
    <div class="modal-header"><h4>Detail Task</h4></div>
    <div class="modal-body"><strong>Task:</strong> ' . htmlspecialchars($task['title']) . '<br><br><strong>Notes:</strong> ' . nl2br(htmlspecialchars($task['description'])) . '<br><br><strong>Timeline:</strong> ' . $task['timeline'] . ' Days<br><br><strong>Type:</strong> ' . $task['type'] . '</div>
    </div></div></div>';
});

$formList->roll->addInput('interns_id', 'selecttable');
$formList->roll->input->interns_id->setTitle('Intern Name');
$formList->roll->input->interns_id->setPlaintext(true);
$formList->roll->input->interns_id->setReferenceTable('interns');
$formList->roll->input->interns_id->setReferenceField('name', 'id');

$formList->roll->addInput('notes', 'sqlplaintext');
$formList->roll->input->notes->setTitle('Notes');

$formList->roll->addInput('status', 'sqlplaintext');
$formList->roll->input->status->setDisplayFunction(function ($value) {
    $colors = [1 => '#6c757d', 2 => '#007bff', 3 => '#ffc107', 4 => '#fd7e14', 5 => '#28a745', 6 => '#dc3545'];
    $texts = [1 => 'To Do', 2 => 'In Progress', 3 => 'Submit', 4 => 'Revised', 5 => 'Done', 6 => 'Cancel'];
    $tcolor = ($value == 3) ? 'black' : 'white';
    return '<span class="label" style="background-color: ' . ($colors[$value] ?? '#6c757d') . '; color: ' . $tcolor . '; padding: 5px 10px; border-radius: 12px;">' . ($texts[$value] ?? 'Unknown') . '</span>';
});

$formList->roll->addInput('status_intern', 'sqlplaintext');
$formList->roll->input->status_intern->setTitle('Status Intern');
$formList->roll->input->status_intern->setFieldName('(SELECT CASE WHEN CURDATE() < start_date THEN "Coming Soon" WHEN CURDATE() BETWEEN start_date AND end_date THEN "Active" ELSE "Ended" END FROM interns WHERE id = interns_id) as status_intern');
$formList->roll->input->status_intern->setDisplayFunction(function ($value) {
    $colors = ['Coming Soon' => '#007bff', 'Active' => '#28a745', 'Ended' => '#dc3545'];
    return '<span class="label" style="background-color: ' . ($colors[$value] ?? '#6c757d') . '; color: white; padding: 5px 12px; border-radius: 12px;">' . $value . '</span>';
});

$formList->roll->addInput('action', 'sqllinks');
$formList->roll->input->action->setTitle('Action');
$formList->roll->input->action->setLinks($Bbc->mod['circuit'] . '.interns_tasks_list_edit');
$formList->roll->input->action->setModal(true);
$formList->roll->input->action->setFieldName('id as action');
$formList->roll->input->action->setDisplayFunction(function ($id) { return '<button class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> Edit</button>'; });

$formList->roll->action();

echo '<div class="panel panel-default"><div class="panel-heading"><h3 class="panel-title">Daftar Tugas Intern</h3></div><div class="panel-body">' . $formList->roll->getForm() . '</div></div>';
echo '<div class="panel panel-default"><div class="panel-heading"><h3 class="panel-title">Add Intern Task</h3></div><div class="panel-body">' . $formAdd->edit->getForm() . '</div></div>';
?>
<div class="col-xs-12 no-both">
  <div class="panel panel-default">
    <div class="panel-heading"><h4 class="panel-title" data-toggle="collapse" href="#import_tasklist_panel" style="cursor:pointer;"><?php echo icon('fa-file-excel-o') ?> Klik disini untuk Manage Task List (Import/Download)</h4></div>
    <div id="import_tasklist_panel" class="panel-collapse collapse">
      <form action="" method="POST" enctype="multipart/form-data">
        <div class="panel-body">
          <div class="form-group">
            <label>Upload File Excel (.xlsx)</label>
            <input type="file" name="excel" class="form-control" accept=".xlsx" />
            <div class="help-block">Kolom: Task Title, Intern Email, Notes, Status</div>
          </div>
        </div>
        <div class="panel-footer">
          <button type="submit" name="transfer" value="upload" class="btn btn-primary"><?php echo icon('fa-upload') ?> Upload Sekarang</button>
          <button type="submit" name="transfer" value="download" class="btn btn-default pull-right"><?php echo icon('fa-download') ?> Download Sample</button>
        </div>
      </form>
    </div>
  </div>
</div>