<?php
header("content-type: text/html; charset=utf-8");
ini_set('session.save_path',realpath(dirname($_SERVER['DOCUMENT_ROOT']) . '/../session'));
session_start();

require_once 'core/App.php';
require_once 'core/Controller.php';

$app = new App();

?>
