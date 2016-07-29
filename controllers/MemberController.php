<?php

class MemberController extends Controller {
    
    function index() {
        if (!(isset($_SESSION['userName']) & isset($_SESSION['member']))) {
            header("location: Login");
        }
        $this->view("member");
    }
    
}

?>