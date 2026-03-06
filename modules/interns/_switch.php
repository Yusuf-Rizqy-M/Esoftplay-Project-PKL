<?php if (!defined('_VALID_BBC')) exit('No direct script access allowed');


switch ($Bbc->mod['task']) {
	case 'main':
	case 'beranda':
	include 'beranda.php';
	break;
		
	case 'our_client':
	include 'our_client.php';
	break;

	case 'internshipp':
	include 'internship.php';
	break;

	case 'contact_us':
	include 'contact_us.php';
	break;
		
	case 'about_us':
	include 'about_us.php';
	break;

	case 'laporan_harian':
	$sys->set_layout('dashboard');
	include 'internship.php';
	break;

	case 'laporan_pkl':
	$sys->set_layout('dashboard');
	include 'report.php';
	break;

	case 'task_pkl':
	$sys->set_layout('dashboard');
	include 'task_pkl.php';
	break;

	case 'print_sertif':
	$sys->set_layout('dashboard');
	break;
	default:
	
	
	case 'dashboard':
	$sys->set_layout('dashboard');
	// include 'dashboard.php';
	break;

	echo 'Invalid action <b>' . $Bbc->mod['task'] . '</b> has been received...';
	break;
}
