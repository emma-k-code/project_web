<?php
ini_set('session.save_path',realpath(dirname($_SERVER['DOCUMENT_ROOT']) . '/../session'));
session_start();

class checkMember {
    function Login() {
        // 前往登入頁面
        header("location:Member");
    }
    function Logout() {
        // 刪除cookie
        setcookie("userName",$userName,time()-3600);
        setcookie("member",$member,time()-3600);
        // 刪除session
        session_destroy();
        //前往首頁頁面
        header("location:Home");
    }
}
?>