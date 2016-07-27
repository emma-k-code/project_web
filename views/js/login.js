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
        url: 'Data/signUp', 
        contentType: false,
        processData: false,
        data: formData,                         
        type: 'post',
        success: function(php_script_response){
            if (php_script_response=="exist") {
                alert("此Email已註冊過");
            }else if (php_script_response) {
                alert("註冊成功");
                document.location.href="Login";
            }else {
                alert("註冊失敗");
            }
        }
    });
}