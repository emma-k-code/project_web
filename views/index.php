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
    	$("#invoiceDate").change(invoiceDateChange);
    	$("#bCheckInvoiceNumber").click(checkInvoiceNumber);
    	$('#bUploadNumberFile').change(checkFile);
    	invoiceDateChange();
    }
    
    function invoiceDateChange() {
    	var date = $("#invoiceDate option:selected").text();
    	setInvoiceDate(date);
    	setInvoice(date);
    }
    
    function setInvoiceDate(date) {
        $("#dateLabel").text(date);
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
    
    function setInvoice(date) {
        $.get("../setWinNumber.php?date=" + date, function(data){
    		$("#invoiceNumberContent").html(data);
    	});
    	$.get("../setWinPeriod.php?date=" + date, function(data){
    		$("#invoiceContent").html(data);
    	});
    }
    
    function checkInvoiceNumber() {
        var $number = $("#enterNumber").val().replace("\n",",");
        if ($number.replace(",","").length==0){
            return;
        }
        var $url = "../checkNumber.php?number=" + $number + "&date=" + $("#invoiceDate option:selected").text();
        $.get($url, function(data){
            if (data=="") {
                alert("資料錯誤");
                return;
            }
    		$("#memberEnterNumber").prepend(data);
    	});
    	$("#enterNumber").val("");
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
            <span class="nav navbar-brand navbar-right">使用者名稱</span>
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
                    <button type="button" class="btn btn-link">領獎注意事項</button>
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
                            <button type="submit" class="btn btn-default">儲存</button>
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
                    <tbody id="memberEnterNumber">
                    </tbody>
                </table>
                <div class="bs-example">
                    <ul class="pagination" id="">
                        <li class="disabled"><a href="#">&laquo;</a></li>
                        <li class="active"><a href="#">1</a></li>
                        <li><a href="#">2</a></li>
                        <li><a href="#">3</a></li>
                        <li><a href="#">4</a></li>
                        <li><a href="#">5</a></li>
                        <li><a href="#">&raquo;</a></li>
                    </ul>
                </div>
                <div class="col-lg-6">
                    <button type="button" class="btn btn-link" onclick="self.location.href='member.php'">已儲存發票號碼</button>
                </div>
                <div class="form-group col-lg-6 text-right">

                    <form role="form" action="login.php">
                        <button type="submit" class="btn btn-default">登入</button>
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
