<?php
header("content-type: text/html; charset=utf-8");

$enterNumber = explode(",",$_GET['number']); 
$dateSelect = $_GET['date'];

// 比對發票號碼
require 'checkNumber.php';

$checkNumber = new cCheckNumber();
$showDate = $checkNumber->check($enterNumber,$dateSelect);

echo json_encode($showDate);
?>