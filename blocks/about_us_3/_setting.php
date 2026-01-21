<?php  if (!defined('_VALID_BBC')) exit('No direct script access allowed');

$_setting = [
	'heading' => [
		'text'    => 'Heading',
		'type'    => 'text',
		'default' => 'Our Team'
	]
];

$total_anggota = 6;

for ($i = 1; $i <= $total_anggota; $i++) {
	$group = "Anggota #$i";
	$_setting['m_name' . $i] = [
		'text'    => "Nama #$i",
		'type'    => 'text',
		'group'   => $group,
		'default' => 'Ahmad Syafiq'
	];
	$_setting['m_role' . $i] = [
		'text'    => "Jabatan #$i",
		'type'    => 'text',
		'group'   => $group,
		'default' => 'Staff Operator | Teacher PKL'
	];
	$_setting['m_desc' . $i] = [
		'text'    => "Deskripsi #$i",
		'type'    => 'textarea',
		'group'   => $group,
		'default' => 'Designed to simplify project management, delight clients, and increase profitability. Book your free demo or sign up today and get 30% off.'
	];
	$_setting['m_img' . $i] = [
		'text'    => "Foto URL #$i",
		'type'    => 'text',
		'group'   => $group,
		'default' => 'https://via.placeholder.com/400x300'
	];
	$_setting['m_ig' . $i] = [
		'text'    => "Instagram #$i",
		'type'    => 'text',
		'group'   => $group,
		'default' => 'https://instagram.com'
	];
	$_setting['m_gh' . $i] = [
		'text'    => "Github #$i",
		'type'    => 'text',
		'group'   => $group,
		'default' => 'https://github.com'
	];
}