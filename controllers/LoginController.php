<?php

class LoginController extends Controller {
    
    function index() {
        $this->view("login", $user);
    }
    
}

?>