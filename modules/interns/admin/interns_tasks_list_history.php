<?php if (!defined('_VALID_BBC')) exit('No direct script access allowed');

$form = _lib('pea', 'interns_tasks_list_history');

/* SEARCH */
$form->initSearch();

// ========== UBAH MENJADI INPUT TEXT UNTUK SEARCH NAME OR EMAIL ==========
$form->search->addInput('intern_search', 'keyword');
$form->search->input->intern_search->setTitle('Search Name or Email');

$add_sql = $form->search->action();

// ========== MANUAL FILTER UNTUK NAME OR EMAIL ==========
global $db;

// Filter berdasarkan Name OR Email
if (!empty($_SESSION['search']['interns_tasks_list_history']['search_intern_search'])) {
    $search_keyword = addslashes($_SESSION['search']['interns_tasks_list_history']['search_intern_search']);
    
    // Cari di tabel interns berdasarkan name OR email
    $intern_ids = $db->getCol("SELECT id FROM interns WHERE name LIKE '%{$search_keyword}%' OR email LIKE '%{$search_keyword}%'");
    
    if (!empty($intern_ids)) {
        $ids_string = implode(',', $intern_ids);
        if (stripos($add_sql, 'WHERE') !== false) {
            $add_sql .= " AND interns_id IN ($ids_string)";
        } else {
            $add_sql .= " WHERE interns_id IN ($ids_string)";
        }
    } else {
        // Jika tidak ada hasil, tampilkan hasil kosong
        if (stripos($add_sql, 'WHERE') !== false) {
            $add_sql .= " AND 1=0";
        } else {
            $add_sql .= " WHERE 1=0";
        }
    }
}

echo '<div style="margin-bottom: 20px;">'; // Gap antara Search dan List
echo $form->search->getForm();
echo '</div>';

/* LIST HISTORY */
$form->initRoll($add_sql . ' ORDER BY created DESC, id DESC', 'id');

/* No Save/Delete */
$form->roll->setDeleteTool(false);
$form->roll->setSaveTool(false);

/* ID (Hidden) */
$form->roll->addInput('id', 'sqlplaintext');
$form->roll->input->id->setDisplayColumn(false);

/* ========== URUTAN: NAME, EMAIL, TASKS, NOTES, STATUS ========== */

/* KOLOM 1: NAME */
$form->roll->addInput('interns_id','selecttable');
$form->roll->input->interns_id->setTitle('Name');
$form->roll->input->interns_id->setPlaintext(true);
$form->roll->input->interns_id->setReferenceTable('interns');
$form->roll->input->interns_id->setReferenceField('name','id');

/* KOLOM 2: EMAIL */
$form->roll->addInput('intern_email', 'sqlplaintext');
$form->roll->input->intern_email->setTitle('Email');
$form->roll->input->intern_email->setFieldName('(SELECT email FROM interns WHERE id = interns_id) as intern_email');

/* KOLOM 3: TASK NAME */
$form->roll->addInput('interns_tasks_list_id', 'selecttable');
$form->roll->input->interns_tasks_list_id->setTitle('Tasks');
$form->roll->input->interns_tasks_list_id->setPlaintext(true);
// JOIN antara interns_tasks_list (l) dan interns_tasks (t)
$form->roll->input->interns_tasks_list_id->setReferenceTable('interns_tasks_list AS l LEFT JOIN interns_tasks AS t ON (l.interns_tasks_id=t.id)');
// Ambil field 'title' dari alias tabel 't'
$form->roll->input->interns_tasks_list_id->setReferenceField('t.title', 'l.id');

/* KOLOM 4: NOTES */
$form->roll->addInput('task_notes', 'selecttable'); 
$form->roll->input->task_notes->setTitle('Notes');
$form->roll->input->task_notes->setFieldName('interns_tasks_list_id'); // Merujuk ke field ID yang sama di DB
$form->roll->input->task_notes->setPlaintext(true);
$form->roll->input->task_notes->setReferenceTable('interns_tasks_list');
$form->roll->input->task_notes->setReferenceField('notes','id');

/* KOLOM 5: STATUS */
$form->roll->addInput('status', 'sqlplaintext');
$form->roll->input->status->setTitle('Status');
$form->roll->input->status->setDisplayFunction(function ($value) {
    $colors = [
        1 => ['bg' => '#6c757d', 'text' => 'white', 'label' => 'To Do'],
        2 => ['bg' => '#007bff', 'text' => 'white', 'label' => 'In Progress'],
        3 => ['bg' => '#ffc107', 'text' => 'black', 'label' => 'Submit'],
        4 => ['bg' => '#fd7e14', 'text' => 'white', 'label' => 'Revised'],
        5 => ['bg' => '#28a745', 'text' => 'white', 'label' => 'Done'],
        6 => ['bg' => '#dc3545', 'text' => 'white', 'label' => 'Cancel']
    ];
    
    $status = $colors[$value] ?? ['bg' => '#6c757d', 'text' => 'white', 'label' => 'Unknown'];
    
    return '<span class="label" style="background-color: '.$status['bg'].'; color: '.$status['text'].'; padding: 5px 10px; border-radius: 12px;">'.$status['label'].'</span>';
});

/* KOLOM 6: CREATED */
$form->roll->addInput('created', 'sqlplaintext');
$form->roll->input->created->setTitle('Created');
$form->roll->input->created->setDateFormat('d M Y, H:i');

/* OUTPUT */
$output = $form->roll->getForm();

// Wrap dengan Panel Bootstrap
echo '<div class="panel panel-default">';
echo '<div class="panel-heading"><h3 class="panel-title">Daftar Tugas List History</h3></div>';
echo '<div class="panel-body">' . $output . '</div>';
echo '</div>';