<?php
if ($_FILES ["file"]["error"] == 0) {
	processFile ( $_FILES ["file"] );
}
function processFile($objFile) {
	
	$newFileName = rand(1,1000) . ".txt";
	
	$test = move_uploaded_file ( $objFile ["tmp_name"], "./uploadFile/" . $newFileName );
	if (! $test) {
		die ( "move_uploaded_file() faile" );
	}
	
	$lines = file ( './uploadFile/'.$newFileName ); // 將檔案內容寫入一個陣列
	unlink('./uploadFile/'.$newFileName); // 刪除檔案
	
	foreach ($lines as $value) {
        echo $value;
	}
	exit ();
}

?>