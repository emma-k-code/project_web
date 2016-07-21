<?php
header("content-type: text/html; charset=utf-8");
ini_set('session.save_path',realpath(dirname($_SERVER['DOCUMENT_ROOT']) . '/../session'));
session_start();

if (!(isset($_SESSION['userName']) & isset($_SESSION['member']))) {
    header("location: Login");
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
    <link href="views/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" type="text/css" href="views/css/custom.css" />

    <!-- table CSS-->
    <link rel="stylesheet" type="text/css" href="views/css/normalize.css" />
    <link rel="stylesheet" type="text/css" href="views/css/demo.css" />
    <link rel="stylesheet" type="text/css" href="views/css/component.css" />

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- jQuery Version 1.11.1 -->
    <script src="views/js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="views/js/bootstrap.min.js"></script>
    
    <script src="views/js/member.js"></script>
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
                <a class="navbar-brand" href="Home">發票對獎網站</a>
            </div>
            <span id="sUserName" class="nav navbar-brand navbar-right"><?php echo $_SESSION['userName']; ?></span>
        </div>
        <!-- /.container -->
    </nav>

    <div class="container">
        <div class="row">
            <div class="col-lg-3 text-center">
                <div id="dateList" class="list-group">
                </div>
                <div class="col-lg-12">
                    <span class="label label-success" id="showMoney">總金額：0</span>
                </div>
                <div class="col-lg-12">
                    <span class="btn btn-link" id="bWinningInfo">領獎注意事項</span>
                </div>
                
                <div class="panel panel-info col-lg-12" id="pWinning">
                  <div class="panel-body">
                   <p>
                    1.請於郵局公告之兌獎營業時間內辦理，中獎人填妥領獎收據並在收據上粘貼0.4%印花稅票【中五獎(含)以上者】，攜帶國民身分證（非本國國籍人士得以護照、居留證等文件替代）及中獎統一發票收執聯兌領獎金。中特別獎、特獎、頭獎者請向各直轄市及各縣、市經指定之郵局領取獎金；中二獎、三獎、四獎、五獎、六獎者請向各地郵局兌獎。（各地郵局延時營業窗口及夜間郵局均不辦理兌獎業務。）<br>
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

            <div class="col-lg-8">
                <table class="invoiceNumber col-lg-12">
                    <thead>
                        <tr>
                            <th>期別</th>
                            <th>發票號碼</th>
                            <th>結果</th>
                            <th>金額</th>
                        </tr>
                    </thead>
                    <tbody id="memberNumber">
                    </tbody>
                </table>
                <div class="text-center">
                    <h4 id="invoiceContent"></h4>
                    <div class="bs-example col-lg-12 text-center">
                        <ul class="pagination text-center" id="checkNumberPage">
                        </ul>
                    </div>
                </div>
            </div>
            
        </div>
    </div>


</body>

</html>
