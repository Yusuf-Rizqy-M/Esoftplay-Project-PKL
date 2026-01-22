<?php if (! defined('_VALID_BBC')) exit('No direct script access allowed');

/*=====================================
 * Menu Position..
/*===================================*/
$q = "SELECT id, name FROM bbc_menu_cat ORDER BY orderby ASC";
$_setting = array(
	'cat_id' => array(
		'text'   => 'Category',
		'type'   => 'select',
		'option' => $db->getAll($q)
	),
	'logo' => [
		'text' => 'Logo Image',
		'type' => 'text',
		'attr' => 'id="txtUrl"',
	],
);
