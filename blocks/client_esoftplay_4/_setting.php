<?php  if (!defined('_VALID_BBC')) exit('No direct script access allowed');

$_setting = [];

for ($i = 1; $i <= 10; $i++) {
    $_setting['item'.$i.'_title1'] = [
        'text' => 'Title Baris 1 Item '.$i,
        'type' => 'text',
        'default' => 'Belajar dari 0 sampai'
    ];
    $_setting['item'.$i.'_title2'] = [
        'text' => 'Title Baris 2 (Biru) Item '.$i,
        'type' => 'text',
        'default' => 'Cuan Multibagger'
    ];
    $_setting['item'.$i.'_image'] = [
        'text' => 'Image Item '.$i,
        'type' => 'text',
        'default' => 'http://localhost/pkl_project_esoftplay/images/uploads/asset/assetkantor.png?KeepThis=true&TB_iframe=true&height=430&width=700'
    ];
    $_setting['item'.$i.'_tagline'] = [
        'text' => 'Tagline Item '.$i,
        'type' => 'textarea',
        'default' => 'Kalian akan dibimbing dan belajar mengenai pasar saham, finansial, hingga cara investasi multibagger dari Founder Stockwise; Andry Hakim & Douglas Goh'
    ];
}