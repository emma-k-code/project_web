<?php

class HomeController extends Controller {
    
    function index() {
        $user = $this->model("aboutMember");
        // 取得會員名稱
        $indexData = array($user->getUserName(),$user->getLoginButton());
        $this->view("index",$indexData);
    }
    
}

?>