<?php
ini_set('session.save_path',realpath(dirname($_SERVER['DOCUMENT_ROOT']) . '/../session'));
session_start();

class checkMember {
    function Login() {
        return "Login";
    }
    function Logout() {
        // 刪除cookie
        setcookie("userName",$userName,time()-3600);
        setcookie("member",$member,time()-3600);
        // 刪除session
        session_destroy();
        
        return "Home";
    }
}
?>