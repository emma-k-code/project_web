<?php

class setAutoCheck {
    function printResult($showData){
        
        foreach ($showData as $value) {
            foreach ($value as $data) {
                $showText .= $data['numDate']."-".$data['number']."-".$data['prize']."<br>";
            }
        }
        
        return $showText;
    }
}

?>