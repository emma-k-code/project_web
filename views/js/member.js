$(document).ready(init);

function init() {
	// 領獎注意事項
	$("#bWinningInfo").click(winningInfo);
	// 選擇期別
	$("#dateList").on("click","a",setInvoice);
	// 選擇頁次
	$("#checkNumberPage").on("click","li",changePage);
	$("#memberNumber").on("click","button",deleteNumber);
	
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
    
    $("#dateList").append("<a style='cursor:pointer' class='list-group-item active'> 全部 </a>");
    for (var i = 0; i < selectData.length; i++ ) {
        $("#dateList").append("<a style='cursor:pointer' class='list-group-item'>" + selectData[i].dateYM + "</a>");
    }
    $("#dateList").append("<a style='cursor:pointer' class='list-group-item'> 中獎發票 </a>");
}

function winningInfo() {
    $("#pWinning").toggle();
}

function setInvoice() {
    removeActive();
    var date = $(this).text();
    $(this).addClass("active");
    sendData(date,"1");
    getPage(date);
}

function removeActive() {
    $("#dateList a").removeClass("active");
    $("#checkNumberPage li").removeClass("active");
}

function sendData(date,page) {
    
    $("#memberNumber").html("Loading...");

    // 取得會員發票號碼
    $.get("Data/setMemberNumber?date=" + date + "&page=" + page , function(data){
		setMemberNumber(data);
	});
	
	// 領獎期間
	$.get("Data/setWinPeriod?date=" + date, function(data){
		$("#invoiceContent").html(data);
	});
	
	// 總計金額
	$.get("Data/getMemberMoney?date=" + date , function(data){
		setALLMoney(data);
	});
}

function setMemberNumber(data) {
    var tableData = JSON.parse(data);
    
    if (tableData == "") {
        $("#memberNumber").html("尚無資料");
        return;
    }
    
    money = "";
    
    // 清空表格
    $("#memberNumber").html("");
    for (var i = tableData.length-1; i >= 0; i-- ) {
        var row = $("<tr>");
        row.append("<th>" + tableData[i].mDate + "</th>");
        row.append("<td>" + tableData[i].mNumber + "</td>");
        row.append("<td>" + tableData[i].mResult + "</td>");
        row.append("<td>" + tableData[i].money + "</td>");
        row.append("<td><button type='button' value='"+tableData[i].id+"' class='btn btn-default'>刪除</button></td>");
        row.append("</tr>");
        
        $("#memberNumber").prepend(row);
        
    }
    
}

function getPage(date) {
	$.get("Data/getMemberNumberCount?date=" + date, function(data){
	    if ($("#checkNumberPage .active").text()==""){
	        page = 1;
	    }else {
	        page = $("#checkNumberPage .active").text();
	    }
		pringPage(Math.ceil(data/10),page);
	});
}

function pringPage(data,page) {
    $("#checkNumberPage").html("");
    if (page > data) 
        page = 1;
    
    for (var i = 1; i <= data; i++) {
        if (i==page) {
            $("#checkNumberPage").append("<li class='active'><a style='cursor:pointer'>" +i+ "</a></li>");
        }else {
            $("#checkNumberPage").append("<li><a style='cursor:pointer'>" +i+ "</a></li>");
        }
    }
    
}

function changePage() {
    $("#checkNumberPage li").removeClass("active");
    var page = $(this).text();
    $(this).addClass("active");
    
    sendData($("#dateList .active").text(),page)
}

function setALLMoney(allMoney) {
    $("#showMoney").html("總金額："+allMoney);
}

function deleteNumber(){
    id = $(this).val();
    $(this).text("Loading");
    $.get("Data/deleteMemberNumber?id=" + $(this).val(), function(data){
        if (data){
            getPage($("#dateList .active").text());
            sendData($("#dateList .active").text(),	$("#checkNumberPage .active").text());
        }else {
            alert("刪除失敗");
        }
	});
	$(this).val("刪除");
}