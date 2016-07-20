$(document).ready(init);

function init() {
	// 領獎注意事項
	$("#bWinningInfo").click(winningInfo);
	// 選擇期別
	$("#dateList").on("click","a",setInvoice);
	// 選擇頁次
	$("#checkNumberPage").on("click","li",changePage);
	
	// 取得期別
	getInvoiceDate();
	
	sendData("全部","1");
	
	getPage("全部");
}

function getInvoiceDate() {
    $.ajax({
        url: 'Data/getDate', 
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
    
    $("#dateList").append("<a href='#' class='list-group-item active'> 全部 </a>");
    for (var i = 0; i < selectData.length; i++ ) {
        $("#dateList").append("<a href='#' class='list-group-item'>" + selectData[i].dateYM + "</a>");
    }
    $("#dateList").append("<a href='#' class='list-group-item'> 中獎發票 </a>");
}

function winningInfo() {
    $("#pWinning").toggle();
}

function setInvoice() {
    removeListActive();
    var date = $(this).text();
    $(this).addClass("active");
    sendData(date,"1");
    getPage(date);
}

function removeListActive() {
    $("#dateList a").removeClass("active");
}

function sendData(date,page) {
    
    $("#memberNumber").html("Loading...");
    
    $.get("Data/setMemberNumber?date=" + date + "&page=" + page , function(data){
		setMemberNumber(data);
	});
	$.get("Data/setWinPeriod?date=" + date, function(data){
		$("#invoiceContent").html(data);
	});
}

function setMemberNumber(data) {
    if (data == "尚無資料") {
        $("#memberNumber").html("尚無資料");
        return;
    }
    
    var tableData = JSON.parse(data);
    
    // 清空表格
    $("#memberNumber").html("");
    for (var i = 0; i < tableData.length; i++ ) {
        var row = $("<tr>");
        row.append("<th>" + tableData[i].mDate + "</th>");
        row.append("<td>" + tableData[i].mNumber + "</td>");
        row.append("<td>" + tableData[i].mResult + "</td>");
        row.append("<td>" + tableData[i].money + "</td>");
        row.append("</tr>");
        
        $("#memberNumber").prepend(row);
    }
}

function getPage(date) {
	$.get("Data/getMemberNumberCount?date=" + date, function(data){
		pringPage(Math.ceil(data/10));
	});
}

function pringPage(data) {
    $("#checkNumberPage").html("");
    
    $("#checkNumberPage").append("<li class='active'><a href='#'>1</a></li>");
    for (var i = 2; i <= data; i++) {
        $("#checkNumberPage").append("<li><a href='#'>" +i+ "</a></li>");
    }
    
}

function changePage() {
    $("#checkNumberPage li").removeClass("active");
    var page = $(this).text();
    $(this).addClass("active");
    
    sendData($("#dateList .active").text(),page)
}