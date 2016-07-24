<?php
ini_set('session.save_path',realpath(dirname($_SERVER['DOCUMENT_ROOT']) . '/../session'));
session_start();

class LoginController extends Controller {
    
    // 進入登入頁面 如果按下登入按鈕檢查登入資料 並往指定頁面
    /* $_POST['bLogin']->是否按下登入按鈕 $userName->輸入的名稱 $email->輸入的Email
        $password->輸入的密碼 $db->資料庫連線 */
    function index() {
        // 如果已經登入 則前往首頁
        if (isset($_SESSION['userName'])) {
            header("location: Home");
            return;
        }
        
        // 如果按下登入按鈕
        if (isset($_POST['bLogin'])) {
            // 接收的登入資料
            $email = $_POST['username'];
            $password = $_POST["password"];
            
            // 資料庫連線
            $db = $this->getDatabaseConfig();
            
            // 比對會員資料
            $signIn = $this->model("signIn");
            // 判斷登入成功與否
            $checkLogin = $signIn->check($db,$email,$password);
            
            // 登入成功進入首頁
            if ($checkLogin=="OK") {
                header("location: Home");
                return;
            }
            
        }
        
        $this->view("login",$checkLogin);
    }
    
    // 取得資料庫連線 (PDO)
    function getDatabaseConfig() {
        // 資料庫設定
        $config = $this->model("config");
        // 回傳資料庫連線
        return $config->getDB();
    }
    
}

?>