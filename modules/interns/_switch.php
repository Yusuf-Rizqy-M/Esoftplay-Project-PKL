<?php if (!defined('_VALID_BBC')) exit('No direct script access allowed');


switch ($Bbc->mod['task']) {
	case 'main':

	case 'intern':
		include 'intern.php';
		break;

	case 'home':
		break;

	case 'laporan_harian':
		$sys->set_layout('dashboard');
		echo 'laporan_harian';
		break;

	case 'laporan_pkl':
		$sys->set_layout('dashboard');
		break;

	case 'task_pkl':
		$sys->set_layout('dashboard');
		echo 'task_pkl';
		break;

	case 'dashboard':
		$sys->set_layout('dashboard');
		echo 'hi dashboard';
		break;


	case 'print_sertif':
		$sys->set_layout('dashboard');
		break;

	case 'client_esoftplay':
		break;

	case 'internship':
		break;

	case 'tentang_kami':
		break;

	case 'login':
		echo 'login';
		break;

	case 'kontak':
		break;
	default:

		echo 'Invalid action <b>' . $Bbc->mod['task'] . '</b> has been received...';
		break;
}
