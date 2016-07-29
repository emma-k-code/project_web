<?php
$showData = $data[0];
$aPrizeMoney = $data[1];
if (!isset($showData)){
    echo "尚無資料";
    return;
}

foreach ($showData as $key=>$value) {
    echo "<tr>";
    echo "<th>" . $key . "</th>";
    echo "<td>";
foreach ($value as $num) {
    echo $num . "<br>";
}
    echo "</td>";
    echo "<td>" . $aPrizeMoney[$key] . "</td>";
    echo "</tr>";
}
?>