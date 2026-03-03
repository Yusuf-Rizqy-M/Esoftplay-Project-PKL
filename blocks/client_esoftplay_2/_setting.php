<?php  if (!defined('_VALID_BBC')) exit('No direct script access allowed');

$_setting = [];

for ($i = 1; $i <= 6; $i++) {
    $_setting['item'.$i.'_logo'] = [
        'text' => 'Logo Item '.$i,
        'type' => 'text',
        'default' => 'https://via.placeholder.com/50x50/FFD700/000000?text=bbo'
    ];
    $_setting['item'.$i.'_image'] = [
        'text' => 'Image Item '.$i,
        'type' => 'text',
        'default' => 'https://via.placeholder.com/400x250/333333/FFFFFF?text=Preview+'.$i
    ];
    $_setting['item'.$i.'_title'] = [
        'text' => 'Title Item '.$i,
        'type' => 'text',
        'default' => 'BBO Web App Ticketing '.$i
    ];
    // Variabel baru untuk link
    $_setting['item'.$i.'_link'] = [
        'text' => 'Link Item '.$i,
        'type' => 'text',
        'default' => '#'
    ];
}