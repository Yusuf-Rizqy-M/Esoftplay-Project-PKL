<?php  if (!defined('_VALID_BBC')) exit('No direct script access allowed');

$_setting = [
    'heading' => [
        'text' => 'Heading',
        'type' => 'text',
        'default' => 'How it All Began'
    ],
    'tagline' => [
        'text' => 'Tag-Line',
        'type' => 'text',
        'tips' => 'leave it blank to default link by selected feature',
        'default' => 'Our Story'
    ],
    'hero' => [
        'text' => 'Hero Image',
        'type' => 'text',
        'attr' => 'id="txtUrl"',
    ],
    'description' => [
        'text' => 'Description',
        'type' => 'textarea',
        'attr' => 'rows="5"',
    ],
];