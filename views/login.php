<!DOCTYPE html>
<!--[if lt IE 7 ]> <html lang="en" class="no-js ie6 lt8"> <![endif]-->
<!--[if IE 7 ]>    <html lang="en" class="no-js ie7 lt8"> <![endif]-->
<!--[if IE 8 ]>    <html lang="en" class="no-js ie8 lt8"> <![endif]-->
<!--[if IE 9 ]>    <html lang="en" class="no-js ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html lang="en" class="no-js">
<!--<![endif]-->

<head>
    <meta charset="UTF-8" />
    <!-- <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">  -->
    <title>發票對獎網站</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Login and Registration Form with HTML5 and CSS3" />
    <meta name="keywords" content="html5, css3, form, switch, animation, :target, pseudo-class" />
    <meta name="author" content="Codrops" />
    <link rel="shortcut icon" href="../favicon.ico">
    <link rel="stylesheet" type="text/css" href="css/login_css/demo.css" />
    <link rel="stylesheet" type="text/css" href="css/login_css/style.css" />
    <link rel="stylesheet" type="text/css" href="css/login_css/animate-custom.css" />

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" type="text/css" href="css/custom.css" />
    
    <!-- jQuery Version 1.11.1 -->
    <script src="js/jquery.js"></script>
    
    <script>
        $(document).ready(init);
        
        function init() {
    	    $("#passwordsignup_confirm").change(confirmPassword);
    	    $("#bSignUp").click(signUpUserData);
    	    $("#bLogin").click(loginUserData);
        }
        
        function confirmPassword(){
            if ($("#passwordsignup_confirm").val()!=$("#passwordsignup").val()) {
                $("#confirmMessage").show();
            }else {
                $("#confirmMessage").hide();
            }
        }
        
        function signUpUserData() {
            if ($("#passwordsignup_confirm").val()!=$("#passwordsignup").val()) {
                return;
            }
            var formData = new FormData();                  
            formData.append('userName', $("#usernamesignup").val()); 
            formData.append('email', $("#emailsignup").val());
            formData.append('password', $("#passwordsignup").val());
            $.ajax({
                url: '../signUp.php', 
                contentType: false,
                processData: false,
                data: formData,                         
                type: 'post',
                success: function(php_script_response){
                    if (php_script_response=="exist") {
                        alert("此Email已註冊過");
                    }else {
                        document.location.href="login.php";
                    }
                }
            });
        }
        
        function loginUserData() {
            var formData = new FormData();                  
            formData.append('email', $("#email").val()); 
            formData.append('password', $("#password").val());
            $.ajax({
                url: '../signIn.php', 
                contentType: false,
                processData: false,
                data: formData,                         
                type: 'post',
                success: function(php_script_response){
                    if (php_script_response=="notFound") {
                        alert("資料錯誤");
                    }else {
                        document.location.href="index.php";
                    }
                }
            });
        }
        
    </script>

</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-dark bg-success navbar-fixed-top" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                <a class="navbar-brand" href="index.php">發票對獎網站</a>
            </div>
        </div>
        <!-- /.container -->
    </nav>

    <div class="container">
        <div class="row">
            <section>
                <div id="container_demo">
                    <!-- hidden anchor to stop jump http://www.css3create.com/Astuce-Empecher-le-scroll-avec-l-utilisation-de-target#wrap4  -->
                    <a class="hiddenanchor" id="toregister"></a>
                    <a class="hiddenanchor" id="tologin"></a>
                    <div id="wrapper">
                        <div id="login" class="animate form">
                            <form id="loginForm" autocomplete="on">
                                <h1>Log in</h1>
                                <p>
                                    <label for="username" class="uname" data-icon="u"> Your email </label>
                                    <input id="email" name="username" required="required" type="text" placeholder="mymail@mail.com" />
                                </p>
                                <p>
                                    <label for="password" class="youpasswd" data-icon="p"> Your password </label>
                                    <input id="password" name="password" required="required" type="password" placeholder="eg. X8df!90EO" />
                                </p>
                                <p class="keeplogin">
                                    <input type="checkbox" name="loginkeeping" id="loginkeeping" value="loginkeeping" />
                                    <label for="loginkeeping">Keep me logged in</label>
                                </p>
                                <p class="login button">
                                    <input id="bLogin" type="button" value="Login" />
                                </p>
                                <p class="change_link">
                                    Not a member yet ?
                                    <a href="#toregister" class="to_register">Join us</a>
                                </p>
                            </form>
                        </div>

                        <div id="register" class="animate form">
                            <form id="signUpForm" autocomplete="on">
                                <h1> Sign up </h1>
                                <p>
                                    <label for="usernamesignup" class="uname" data-icon="u">Your username</label>
                                    <input id="usernamesignup" name="usernamesignup" required="required" type="text" placeholder="mysuperusername690" />
                                </p>
                                <p>
                                    <label for="emailsignup" class="youmail" data-icon="e"> Your email</label>
                                    <input id="emailsignup" name="emailsignup" required="required" type="email" placeholder="mysupermail@mail.com" val="<?php echo $_SESSION['userName']; ?>" />
                                </p>
                                <p>
                                    <label for="passwordsignup" class="youpasswd" data-icon="p">Your password </label>
                                    <input id="passwordsignup" name="passwordsignup" required="required" type="password" placeholder="eg. X8df!90EO" />
                                </p>
                                <p>
                                    <label for="passwordsignup_confirm" class="youpasswd" data-icon="p">Please confirm your password </label>
                                    <input id="passwordsignup_confirm" name="passwordsignup_confirm" required="required" type="password" placeholder="eg. X8df!90EO" />
                                    <span id="confirmMessage">Passwords must match</span>
                                </p>
                                <p class="signin button">
                                    <input id="bSignUp" type="button" value="Sign up" />
                                </p>
                                <p class="change_link">
                                    Already a member ?
                                    <a href="#tologin" class="to_register"> Go and log in </a>
                                </p>
                            </form>
                        </div>

                    </div>
                </div>
            </section>
        </div>
    </div>
</body>

</html>