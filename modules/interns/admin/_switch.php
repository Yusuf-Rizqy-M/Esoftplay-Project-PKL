<?php if (!defined('_VALID_BBC')) exit('No direct script access allowed');

switch ($Bbc->mod['task']) {
  case 'main':

  case 'interns':
    include 'interns.php';
    break;

  case 'interns_edit':
    include 'interns_edit.php';
    break;

  case 'interns_edit_autocomplete':
    include 'interns_edit_autocomplete.php';
    break;


  case 'interns_tasks':
    include 'interns_tasks.php';
    break;

  case 'interns_tasks_edit':
    include 'interns_tasks_edit.php';
    break;

  case 'interns_tasks_list':
    include 'interns_tasks_list.php';
    break;

  case 'interns_tasks_list_edit':
    include 'interns_tasks_list_edit.php';
    break;

  case 'interns_tasks_list_history':
    include 'interns_tasks_list_history.php';
    break;

  case 'interns_tasks_detail':
    include 'interns_tasks_detail.php';
    break;

  case 'interns_tasks_assigned':
    include 'interns_tasks_assigned.php';
    break;

    case 'interns_tasks_list_status':
    include 'interns_tasks_list_status.php';
    break;

    case 'interns_tasks_list_info':
    include 'interns_tasks_list_info.php';
    break;

    
  default:
    echo 'Invalid action <b>' . $Bbc->mod['task'] . '</b> has been received...';
    break;
}
