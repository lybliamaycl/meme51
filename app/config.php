<?php
session_start();
ob_start();
error_reporting(E_ALL ^ E_NOTICE | E_WARNING);
ini_set('display_errors', 'ON');
mb_internal_encoding('UTF-8');
date_default_timezone_set("Europe/Paris");
header('Content-type: text/html; charset=utf-8');

define('DB_DSN', 'mysql:dbname=db769501816;host=db769501816.hosting-data.io');
define('DB_USER', 'dbo769501816');
define('DB_PASSWORD', 'MkXI!110694');

require_once 'functions.common.php';
