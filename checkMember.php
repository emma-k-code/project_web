<?php
header("content-type: text/html; charset=utf-8");

if (isset($_POST['bLog'])) {
    if (isset($_COOKIE['userName'])) {
        // 刪除cookie 前往首頁頁面
        setcookie("userName",$userName,time()-3600);
        setcookie("member",$member,time()-3600);
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