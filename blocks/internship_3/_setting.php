<?php if (!defined('_VALID_BBC')) exit('No direct script access allowed');

$_setting = [
    'heading' => [
        'text' => 'Heading',
        'type' => 'text'
    ],
];

for ($i = 1; $i <= 12; $i++) {
    $_setting['image' . $i] = [
        'text' => 'URL Icon ' . $i,
        'type' => 'text',
        'attr' => 'id="txtUrl' . $i . '"',
    ];
}