<?php

class Controller {
    
    public function model($model) {
        require_once "../InvoiceWeb/models/$model.php";
        return new $model ();
    }
    
    public function view($view,$data=array()) {
        require_once "../InvoiceWeb/views/$view.php";
    }
}

?>