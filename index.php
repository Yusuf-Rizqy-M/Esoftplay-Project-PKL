<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
$Bbc = new stdClass();
define( '_VALID_BBC', 1 );
define( '_ADMIN', '' );
include_once 'config.php';
define( 'bbcAuth', 'bbcAuthUser' );
include_once _ROOT.'includes/includes.php';
