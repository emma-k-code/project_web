<?php
    require '../setDate.php';
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
    	// 比對發票號碼
    	$("#bCheckInvoiceNumber").click(checkInvoiceNumber);
    	// 上傳檔案
    	$('#bUploadNumberFile').change(checkFile);
    	// 領獎注意事項
    	$("#bWinningInfo").click(winningInfo);
    	// 儲存發票號碼
    	$("#bSaveNumber").click(saveNumber);
    	
    	invoiceDateChange();
    	
    	// 如果有登入會員 顯示儲存按鈕
    	if ($("#sUserName").text()!="") {
    	    $("#bSaveNumber").show();
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
        $.get("../setWinNumber.php?date=" + date, function(data){
    		$("#invoiceNumberContent").html(data);
    	});
    	$.get("../setWinPeriod.php?date=" + date, function(data){
    		$("#invoiceContent").html(data);
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
    
    function checkInvoiceNumber() {
        // 格式統一
        var number = $("#enterNumber").val().replace("\n",",");
        if (number.replace(",","").length==0){
            return;
        }
        
        if ($("#invoiceNumberContent").text()=="尚無資料") {
            alert("尚未開獎");
            return;
        }
        
        // 比對發票
        toCheck(number,$("#invoiceDate option:selected").text());
        // 清空文字方塊內容
    	$("#enterNumber").val("");
    }
    
    function  toCheck(number,date) {
        var $url = "../setCheckNumber.php?number=" + number + "&date=" + date;
        $.get($url, function(data){
            if (data=="") {
                alert("資料錯誤");
                return;
            }
            
            // 繪出結果
            setCheckNumber(data);
            // 儲存結果
            saveNumber(data);
            
    	});
    }
    
    function  setCheckNumber(data) {
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
        
        
    }
    
    function saveNumber(data) {
        var sendAddDate = JSON.parse(data);
        
        var formData = new FormData();
        
        for (var i = 0; i < sendAddDate.length; i++ ) {
            var row = $("<tr>");
            row.append("<th>" + sendAddDate[i].numDate + "</th>");
            row.append("<td>" + sendAddDate[i].number + "</td>");
            row.append("<td>" + sendAddDate[i].prize + "</td>");
            row.append("<td>" + sendAddDate[i].money + "</td>");
            row.append("</tr>");
            
        }
        
                          
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
    }
    
    function saveCheckedNumber() {
        var checkedRow = $("#checkedNumber").find("tr");
        for (var i =0; i < checkedRow.length; i++) {
            var td = $(checkedRow[i]).find("td:eq(0)").text();
             $("#enterNumber").val(td);
        }
       
    //     $.get("../addMemberNumber.php?data=" + data, function(data){
    // 		$("#enterNumber").val(data);
    // 	});
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
            <span id="sUserName" class="nav navbar-brand navbar-right"><?php echo $_COOKIE['userName']; ?></span>
        </div>
        <!-- /.container -->
    </nav>

    <!-- Page Content -->
    <div class="container">
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
                            <?php foreach ($setDate as $value) :?>
                                <option selected><?php echo $value;?></option>
                            <?php endforeach?>
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
                        <div class="form-group col-lg-8">
                            <input type="file" id="bUploadNumberFile">
                        </div>
                        <div class="form-group col-lg-2">
                            <button type="button" id="bSaveNumber" class="btn btn-default">儲存</button>
                        </div>
                        <div class="form-group col-lg-2">
                            <button type="button" id="bCheckInvoiceNumber" class="btn btn-default">送出</button>
                        </div>
                    </div>
                </form>
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
                    <button type="button" class="btn btn-link" onclick="self.location.href='../checkMember.php'">已儲存發票號碼</button>
                </div>
                <div class="form-group col-lg-6 text-right">

                    <form role="form" method="POST" action="../checkMember.php">
                        <button name="bLog" type="submit" class="btn btn-default">
                            <?php 
                                if (isset($_COOKIE['member'])) {
                                    echo "登出";
                                }else {
                                    echo "登入";
                                }
                            ?>
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
