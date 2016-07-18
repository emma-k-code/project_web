<?php
ini_set('session.save_path',realpath(dirname($_SERVER['DOCUMENT_ROOT']) . '/../session'));
session_start();

$userName = (isset($_SESSION['userName']))? $_SESSION['userName']:"guset";

if ($_SESSION['login']==1) {
    unset($_SESSION['login']);
    
    // 自動對獎
    require "../autoCheckNumber.php";
    
    foreach ($showData as $value) {
        foreach ($value as $data) {
            $showText = $showText . $data['numDate']."-".$data['number']."-".$data['prize']."<br>";
        }
    }
    
}

?>

<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>發票對獎網站</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" type="text/css" href="css/custom.css" />

    <!-- table CSS-->
    <link rel="stylesheet" type="text/css" href="css/normalize.css" />
    <link rel="stylesheet" type="text/css" href="css/demo.css" />
    <link rel="stylesheet" type="text/css" href="css/component.css" />

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    
    <!-- jQuery Version 1.11.1 -->
    <script src="js/jquery.js"></script>
    
    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
    
    <script>
    $(document).ready(init);
    
    function init() {
        // 選擇期別
    	$("#invoiceDate").change(invoiceDateChange);
    	// 領獎注意事項
    	$("#bWinningInfo").click(winningInfo);
    	// 上傳檔案
    	$('#bUploadNumberFile').change(checkFile);
    	// 檢查按鈕 判斷是否送出比對或直接儲存
    	$("#bCheckInvoiceNumber").click(checkButton);
    	
    	// 取得下拉式選單中的期別
    	getInvoiceDate();
    	// 設置登入或登出按鈕
    	setLoginButton();
    	
    	// 載入時先執行一次選擇期別
    	invoiceDateChange();
    	
    	if ($("#autoCheckMessage span").text()=="") $("#autoCheckMessage").hide();
    	
    	$("#checkNumberPage").hide();
    	
    }
    
    function setLoginButton() {
        if ($("#sUserName").text()=="guset") {
            $("#bLog").text("登入");
            return;
        }
        $("#bLog").text("登出");
    }
    
    function getInvoiceDate() {
        $.ajax({
            url: '../setDate.php', 
            async: false,
            contentType: false,
            processData: false,                   
            type: 'get',
            success: function(php_script_response){
                printInvoiceDate(php_script_response);
            }
         });
    }
    
    function printInvoiceDate(data){
        var selectData = JSON.parse(data);
        
        for (var i = 0; i < selectData.length; i++ ) {
            // 預設選擇最近的期別
            if (i < (selectData.length-1)) {
                $("#invoiceDate").append("<option>" + selectData[i].dateYM + "</option>");
            }else {
                $("#invoiceDate").append("<option selected>" + selectData[i].dateYM + "</option>");
            }
        }
    }
    
    function invoiceDateChange() {
    	var date = $("#invoiceDate option:selected").text();
    	setInvoiceDate(date);
    	setInvoice(date);
    }
    
    function setInvoiceDate(date) {
        $("#dateLabel").text(date);
    }
    
    function setInvoice(date) {
        $("#dateLabel").text(date);
        $.get("../setWinNumber.php?date=" + date, function(data){
    		$("#invoiceNumberContent").html(data);
    	});
    	$.get("../setWinPeriod.php?date=" + date, function(data){
    		$("#invoiceContent").html(data);
    		changeButton();
    	});
    }
    
    function checkFile() {
        var fileData = $('#bUploadNumberFile').prop('files')[0];
        var formData = new FormData();                  
        formData.append('file', fileData);        
        $.ajax({
            url: '../uploadNumberFile.php', 
            dataType: 'text', 
            contentType: false,
            processData: false,
            data: formData,                         
            type: 'post',
            success: function(php_script_response){
                $("#enterNumber").val(php_script_response);
            }
         });
         $('#bUploadNumberFile').val("");
    }
    
    function changeButton() {
        if ($("#invoiceContent").text()=="") {
            $("#bCheckInvoiceNumber").text("儲存");
        }else {
            $("#bCheckInvoiceNumber").text("送出");
        }
    }
    
    function checkButton() {
        
        // 格式統一
        var number = $("#enterNumber").val().replace(/\n/g,",");
        if (number.replace(",","").length==0){
            $("#enterNumber").focus();
            return;
        }
        
        // 比對發票號碼 或儲存 如果有登入會員會自動儲存
        if ($("#bCheckInvoiceNumber").text()=="儲存") {
            if ($("#sUserName").text()=="guset") {
                alert("請先登入會員");
                return;
            }
            saveNumber(number);
        }else {
            checkInvoiceNumber(number);
        }
        
        // 清空文字方塊內容
    	$("#enterNumber").val("");
    }
    
    function checkInvoiceNumber(number) {
        // 比對發票
        toCheck(number,$("#invoiceDate option:selected").text());
    }
    
    function toCheck(number,date) {
        var $url = "../setCheckNumber.php?number=" + number + "&date=" + date;
        $.get($url, function(data){
            if (data=="") {
                alert("資料錯誤");
                return;
            }
            
            // 繪出結果
            printCheckNumberTable(data);
            // 儲存結果
            saveCheckedNumber(data);
            
    	});
    }
    
    function printCheckNumberTable(data) {
        var tableData = JSON.parse(data);
        
        for (var i = 0; i < tableData.length; i++ ) {
            var row = $("<tr>");
            row.append("<th>" + tableData[i].numDate + "</th>");
            row.append("<td>" + tableData[i].number + "</td>");
            row.append("<td>" + tableData[i].prize + "</td>");
            row.append("<td>" + tableData[i].money + "</td>");
            row.append("</tr>");
            
            $("#checkedNumber").prepend(row);
            
        }
        
        printNumberPage();
    }
    
    function printNumberPage() {
        $("#checkNumberPage").hide();
        if ($("#checkedNumber tr").length>10) {
            $("#checkNumberPage").show();
        }
    }
    
    function saveNumber(number) {
        var addNumbers = number.split(",");
        for (var i = 0; i < addNumbers.length; i++) {
            if ((!(isNaN(addNumbers[i]))) & (addNumbers[i].length <=3) & (addNumbers[i].length >=8)) {
                var formData = new FormData();
                formData.append('numDate', $("#invoiceDate option:selected").text());
                formData.append('number', addNumbers[i]);
                formData.append('prize', "未開獎");
                
                sendAddDate(formData);
            }
        }
    }
    
    function saveCheckedNumber(data) {
        var addData = JSON.parse(data);
        
        for (var i = 0; i < addData.length; i++ ) {
            var formData = new FormData();
            formData.append('numDate', addData[i].numDate);
            formData.append('number', addData[i].number);
            formData.append('prize', addData[i].prize);
            
            sendAddDate(formData);
            
        }
    }
    
    function sendAddDate(formData) {
        $.ajax({
            url: '../addMemberNumber.php', 
            dataType: 'text', 
            contentType: false,
            processData: false,
            data: formData,                         
            type: 'post',
            success: function(php_script_response){
                saveSuccessShow();
            }
        });
    }
    
    function saveSuccessShow() {
        $("#saveMessage").fadeTo(1000, 500).slideUp(500, function(){
            $("#saveMessage").alert('close');
        });
    }
    
    function winningInfo() {
        $("#pWinning").toggle();
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
            <span id="sUserName" class="nav navbar-brand navbar-right"><?php echo $userName; ?></span>
        </div>
        <!-- /.container -->
    </nav>

    <!-- Page Content -->
    <div class="container">
        <div id="autoCheckMessage" class="alert alert-info">
          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
          <strong>自動對獎結果：</strong> <br>
          <span><?php echo $showText; ?></span>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <h3>
                    <span class="label label-primary" id="dateLabel">
                    </span>
                </h3>

            </div>

            <form role="form">
                <div class="date-select">
                    <div class="form-group date-form ">
                        <div class="col-lg-2">
                            <select class="form-control" id="invoiceDate">
                            </select>
                        </div>
                    </div>
                </div>
            </form>

        </div>

        <div class="row">
            <div class="col-lg-6">
                <table class="invoiceNumber col-lg-12">
                    <thead>
                        <tr>
                            <th>獎別</th>
                            <th>中獎號碼</th>
                            <th>獎金</th>
                        </tr>
                    </thead>
                    <tbody id="invoiceNumberContent">
                        
                    </tbody>
                </table>
                <div class="text-center">
                    <h4 id="invoiceContent"></h4>
                </div>
                <div class="col-lg-12 text-right">
                    <span class="btn btn-link" id="bWinningInfo">領獎注意事項</span>
                </div>
                <div class="panel panel-info col-lg-12" id="pWinning">
                  <div class="panel-body">
                   <p>
                    1.領獎期間自105年06月06日起至105年09月05日止，請於郵局公告之兌獎營業時間內辦理，中獎人填妥領獎收據並在收據上粘貼0.4%印花稅票【中五獎(含)以上者】，攜帶國民身分證（非本國國籍人士得以護照、居留證等文件替代）及中獎統一發票收執聯兌領獎金。中特別獎、特獎、頭獎者請向各直轄市及各縣、市經指定之郵局領取獎金；中二獎、三獎、四獎、五獎、六獎者請向各地郵局兌獎。（各地郵局延時營業窗口及夜間郵局均不辦理兌獎業務。）<br>
        			<br>2.統一發票收執聯未依規定載明金額者，不得領獎。<br>
        			<br>3.統一發票買受人為政府機關、公營事業、公立學校、部隊及營業人者，不得領獎。<br>
        			<br>4.中四獎(含)以上者，依規定應由發獎單位扣繳20%所得稅款。<br>
        			<br>5.中獎之統一發票，每張按其最高中獎獎別限領1個獎金。<br>
        			<br>6.其他有關領獎事項均依「統一發票給獎辦法」規定辦理。<br>
        			<br>7.若有任何兌獎疑義，請洽詢服務專線電話：(02)2396-1651<br>
        			<br>8.本期無實體電子發票中獎號碼，公告於財政部稅務入口網站：<a href="http://invoice.etax.nat.gov.tw/">http://invoice.etax.nat.gov.tw/</a></p>
                  </div>
                </div>
                <h5>資料來源：<a href="http://invoice.etax.nat.gov.tw/" target="_blank">財政部</a></h5>

            </div>

            <div class="col-lg-6">
                <form role="form">
                    <div class="form-group col-lg-12">
                        <label for="comment">輸入發票:</label>
                        <textarea class="form-control" rows="5" id="enterNumber" placeholder="可在號碼間加入,或直接換行進行批次對獎"></textarea>
                        <div class="form-group col-lg-10">
                            <input type="file" id="bUploadNumberFile">
                        </div>
                        <div class="form-group col-lg-2">
                            <button type="button" id="bCheckInvoiceNumber" class="btn btn-default">送出</button>
                        </div>
                    </div>
                </form>
                <div id="saveMessage" class="alert alert-success fade in col-lg-12">
                    <strong>已儲存發票號碼</strong>
                </div>
                <table class="invoiceNumber col-lg-12">
                    <caption><b>結果：</b></caption>
                    <thead>
                        <tr>
                            <th>期別</th>
                            <th>發票號碼</th>
                            <th>結果</th>
                            <th>金額</th>
                        </tr>
                    </thead>
                    <tbody id="checkedNumber">
                    </tbody>
                </table>
                <div class="col-lg-12">
                    <div class="bs-example">
                        <ul class="pagination" id="checkNumberPage">
                            <li class="disabled"><a href="#">&laquo;</a></li>
                            <li class="active"><a href="#">1</a></li>
                            <li><a href="#">2</a></li>
                            <li><a href="#">3</a></li>
                            <li><a href="#">4</a></li>
                            <li><a href="#">5</a></li>
                            <li><a href="#">&raquo;</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-6">
                    <button type="button" class="btn btn-link" onclick="self.location.href='member.php'">已儲存發票號碼</button>
                </div>
                <div class="form-group col-lg-6 text-right">

                    <form role="form" method="POST" action="../checkMember.php">
                        <button id="bLog" name="bLog" type="submit" class="btn btn-default">
                        </button>
                    </form>
                </div>

            </div>

        </div>
        <!-- /.row -->

        <div>

        </div>
        <!-- left table-->

    </div>
    <!-- /.container -->
</body>

</html>
