<?php  if (!defined('_VALID_BBC')) exit('No direct script access allowed');

switch( $Bbc->mod['task'] )
{
	case 'main' : 

    case 'interns':
    include 'interns.php';
    break;

    case 'interns_tasks':
    include 'interns_tasks.php';
    break;

    case 'interns_tasks_list':
    include 'interns_tasks_list.php';
    break;
        
    
    case 'interns_report':
    include 'interns_report.php';
    break;
	default:
		echo 'Invalid action <b>'.$Bbc->mod['task'].'</b> has been received...';
		break;
}