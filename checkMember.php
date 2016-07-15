<?php
header("content-type: text/html; charset=utf-8");
ini_set('session.save_path',realpath(dirname($_SERVER['DOCUMENT_ROOT']) . '/../session'));
session_start();

if (isset($_POST['bLog'])) {
    if (isset($_SESSION['userName'])) {
        // 刪除cookie
        setcookie("userName",$userName,time()-3600);
        setcookie("member",$member,time()-3600);
        // 刪除session
        session_destroy();
        //前往首頁頁面
        header("location:views/index.php");
    }else {
        // 前往登入頁面
        header("location:views/login.php");
    }
    
}else {
    if (isset($_COOKIE['userName']) & isset($_COOKIE['member'])) {
        // 前往會員儲存號碼頁面
        header("location:views/member.php");
    }else {
        // 前往登入頁面
        header("location:views/login.php");
    }
}
?>