<?php
ini_set('session.save_path',realpath(dirname($_SERVER['DOCUMENT_ROOT']) . '/../session'));
session_start();

class checkMember {
    function login() {
    }
    function logout() {
        // 刪除session
        session_destroy();
        
        return "登出成功";
    }
}
?>