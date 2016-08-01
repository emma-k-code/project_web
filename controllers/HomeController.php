<?php

class HomeController extends Controller {
    
    function index() {
        $user = $this->model("aboutMember");
        $indexData = array($user->getUserName(),$user->getLoginButton());
        $this->view("index",$indexData);
    }
    
}

?>