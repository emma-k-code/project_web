<?php

class MemberController extends Controller {
    
    function index() {
        $user = $this->model("aboutMember");
        if (!$user->checkLogin()) {
            header("location: Home");
            return;
        }
        
        $userName = $user->getUserName();
        $this->view("member",$userName);
    }
    
}

?>