<?php

class Controller {
    
    public function model($model) {
        require_once "../webMVC/models/$model.php";
        return new $model ();
    }
    
    public function view($view) {
        require_once "../webMVC/views/$view.php";
    }
}

?>