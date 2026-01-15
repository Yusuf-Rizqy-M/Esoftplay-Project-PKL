<?php  if (!defined('_VALID_BBC')) exit('No direct script access allowed');

$_setting = [];

// Membuat konfigurasi untuk 6 item secara dinamis
for ($i = 1; $i <= 6; $i++) {
    $_setting['text'.$i] = [
        'text' => 'Judul Poin '.$i,
        'type' => 'text'
    ];
    $_setting['desc'.$i] = [
        'text' => 'Deskripsi Poin '.$i,
        'type' => 'text'
    ];
    $_setting['icon'.$i] = [
        'text' => 'URL Icon '.$i,
        'type' => 'text',
        'attr' => 'id="txtUrl'.$i.'"',
    ];
}