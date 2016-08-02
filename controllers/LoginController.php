<?php
class LoginController extends Controller {
    
    // 進入登入頁面 如果按下登入按鈕檢查登入資料 並前往指定頁面
    /* $_POST['bLogin']->是否按下登入按鈕 $userName->輸入的名稱 $email->輸入的Email
        $password->輸入的密碼 */
    function index() {
        $user = $this->model("aboutMember");
        // 如果已經登入 則進行登出並前往首頁
        if ($user->checkLogin()) {
            $user->logout();
            header("location: Home");
            return;
        }
        
        // 如果按下登入按鈕
        if (isset($_POST['bLogin'])) {
            // 接收的登入資料
            $email = addslashes($_POST['username']);
            $password = addslashes($_POST["password"]);
            
            // 判斷登入成功與否
            $checkLogin = $user->login($email,$password);
            
            // 登入成功進入首頁
            if ($checkLogin=="OK") {
                header("location: Home");
                return;
            }
            
        }
        
        $this->view("login",$checkLogin);
    }
    
}

?>