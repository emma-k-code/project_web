<?php
header("content-type: text/html; charset=utf-8");

$enterNumber = explode(",",$_POST['number']); 
$dateSelect = $_POST['date'];

// 比對發票號碼
require 'checkNumber.php';

$checkNumber = new cCheckNumber();
echo $show = $checkNumber->check($enterNumber,$dateSelect);

// foreach ($showDate as $key=>$value) {
//     echo "<tr>";
//     echo "<th>" . $value['date'] . "</th>";
//     echo "<td>" . $key . "</td>";
//     echo "<td>" . $value['prize'] . "</td>";
//     echo "<td>" . $value['money'] . "</td>";
//     echo "</tr>";
// }
?>