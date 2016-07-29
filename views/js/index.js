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
	// 選擇頁次
	$("#checkNumberPage").on("click","li",changePage);
	// 關閉自動對獎結果
	$("#autoCheckMessage").on("click","a",closeAutoCheckMessage);
	
	// 取得下拉式選單中的期別
	getInvoiceDate();
	// 自動對獎
	autoCheck();
	
	// 載入時先執行一次選擇期別
	invoiceDateChange();
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
    $("#invoiceNumberContent").html("Loading...");
    $.get("Data/setWinNumber/" + date, function(data){
		$("#invoiceNumberContent").html(data);
	});
	$.get("Data/setWinPeriod/" + date, function(data){
		$("#invoiceContent").html(data);
		changeButton();
	});
}

function checkFile() {
    $("#enterNumber").val("Loading...");
    var fileData = $('#bUploadNumberFile').prop('files')[0];
    var formData = new FormData();                  
    formData.append('file', fileData);        
    $.ajax({
        url: 'Data/uploadNumberFile', 
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
    }
    
    // 比對發票號碼 或儲存 如果有登入會員會自動儲存
    if ($("#bCheckInvoiceNumber").text()=="儲存") {
        if ($("#sUserName").text()=="guset") {
            alert("請先登入會員");
            return;
        }
        saveNumber(number);
    }else {
        toCheck(number,$("#invoiceDate option:selected").text());
    }
    
    // 清空文字方塊內容
	$("#enterNumber").val("");
}

function toCheck(number,date) {
    $("#loading").show();
    
    var formData = new FormData();
    formData.append('date', date);
    formData.append('number', number);
    
    $.ajax({
        url: 'Data/checkNumber',
        contentType: false,
        processData: false,
        data: formData,                         
        type: 'post',
        success: function(php_script_response){
            if (php_script_response=="") {
                alert("資料錯誤");
            }
            
            // 儲存結果
            saveCheckedNumber(php_script_response);
            
            // 繪出結果
            printCheckNumberTable(php_script_response);
            
        }
    });
    
}

function printCheckNumberTable(data) {
    var tableData = JSON.parse(data);
    
        $.ajax(this.href, {
            success:function() {
                
                money = "";
                
                for (var i = 0; i < tableData.length; i++ ) {
                    var row = $("<tr>");
                    row.append("<th>" + tableData[i].numDate + "</th>");
                    row.append("<td>" + tableData[i].number + "</td>");
                    row.append("<td>" + tableData[i].prize + "</td>");
                    row.append("<td>" + tableData[i].money + "</td>");
                    row.append("</tr>");
                    
                    $("#checkedNumber").prepend(row);
                    
                    if ($("#checkedNumber tr").length > 10) {
                        $("#checkedNumber tr").eq(10).hide();
                    }
                    
                    money = money + "-" + $("#checkedNumber tr td").eq(2).text();
                }
                
                // 計算總金額
                getALLMoney(money);
                pringPage();
                
                $("#loading").hide();
            }
        });
    
}

function saveNumber(number) {
    var addNumbers = number.split(",");
    for (var i = 0; i < addNumbers.length; i++) {
        if ((!(isNaN(addNumbers[i]))) & (addNumbers[i].length >=3) & (addNumbers[i].length <=8)) {
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
        url: 'Data/addMemberNumber', 
        dataType: 'text', 
        contentType: false,
        processData: false,
        data: formData,                         
        type: 'post',
        success: function(php_script_response){
            if (php_script_response) {
                var showMessage = "已儲存發票號碼";
            }else {
                var showMessage = "儲存發票號碼失敗";
            }
            saveSuccessShow(showMessage);
        }
    });
}

function saveSuccessShow(showMessage) {
    $("#saveMessage strong").text(showMessage);
    $("#saveMessage").fadeTo(1000, 500).slideUp(500, function(){
        $("#saveMessage").hide();
    });
}

function winningInfo() {
    $("#pWinning").toggle();
}

function pringPage() {
    $("#checkNumberPage").html("");
    
    page = Math.ceil($("#checkedNumber tr").length / 10);
    
    $("#checkNumberPage").append("<li class='active'><a style='cursor:pointer'>1</a></li>");
    for (var i = 2; i <= page; i++) {
        $("#checkNumberPage").append("<li><a style='cursor:pointer'>" +i+ "</a></li>");
    }
    
}

function changePage() {
    $("#checkNumberPage li").removeClass("active");
    var start = ($(this).text()*10) - 10;
    
    $(this).addClass("active");
    $("#checkedNumber tr").hide();
    
    for (var i = start; i< (start+10); i++) {
        $("#checkedNumber tr").eq(i).show();
    }
}

function autoCheck() {
    $.get("Data/autoCheckNumber", function(data){
		$("#autoCheckMessage span").html(data);
		if (data!="") {
		    $("#autoCheckMessage").show();
		}
	});
}

function closeAutoCheckMessage() {
    $("#autoCheckMessage").hide();
}

function getALLMoney(money) {
    var formData = new FormData();
    formData.append('money', money);
    formData.append('passMoney', $("#showMoney").text());
    
    $.ajax({
        url: 'Data/getAllMoney',
        contentType: false,
        processData: false,
        data: formData,                         
        type: 'post',
        success: function(php_script_response){
            setALLMoney(php_script_response);
        }
    });
}

function setALLMoney(allMoney) {
    $("#showMoney").text("總金額："+allMoney);
}