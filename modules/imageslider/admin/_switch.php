<?php  if (!defined('_VALID_BBC')) exit('No direct script access allowed');

// Module untuk mengatur gambar sliding, dimana gambar ini harus ditampilkan melalui block di 'Control Panel / Block Manager' untuk bisa dilihat oleh pngujung situs
switch( $Bbc->mod['task'] )
{
    case 'main':
    case 'list':
        include 'list.php';
        break;

    case 'list_edit':
        include 'list_edit.php';
        break;

    case 'category':
        include 'category.php';
        break;

    case 'new_desc':
        include 'new_desc.php';
        break;

    case 'config':
        include 'config.php';
        break;

    default:
        echo 'Invalid action <b>'.$Bbc->mod['task'].'</b> has been received...';
        break;
}
