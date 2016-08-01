<?php
$showData = $data;
if (!isset($showData)){
    echo "尚無資料";
    return;
}

foreach ($showData as $key=>$content) {
    echo "<tr>";
    echo "<th>" . $key . "</th>";
    echo "<td>";
    foreach ($content as $cKey=>$value) {
        if ($cKey == "number")  {
            foreach ($value as $num) {
                echo $num . "<br>";
            }
        }else {
            echo "</td>";
            echo "<td>" .$value. "</td>";
        }
    }
    echo "</tr>";
}
?>