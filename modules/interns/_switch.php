<?php  if (!defined('_VALID_BBC')) exit('No direct script access allowed');


switch( $Bbc->mod['task'] )
{
	case 'main' :

	case 'intern':
    include 'intern.php';
    break;

	case 'home':
    break;

	case 'print_sertif':
	echo'print_sertif';
    break;

	case 'laporan_harian':
	echo'laporan_harian';
    break;

	case 'laporan_pkl':
	echo'laporan_pkl';
    break;

	case 'task_pkl':
	echo'task_pkl';
    break;
		
	case 'login':
	echo'login';
    break;

	case 'print_sertif':
	echo'print_sertif';
    break;
	
	case 'laporanharian2':
	echo'laporanharian2';
    break;


	default:
	echo 'Invalid action <b>'.$Bbc->mod['task'].'</b> has been received...';
	break;
}