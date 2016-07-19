<?php

class uploadNumberFile {
	function processFile($objFile) {
		if ($objFile["error"] != 0) {
			return;
		}
		
		$newFileName = rand(1,1000) . ".txt";
		
		$test = move_uploaded_file ( $objFile ["tmp_name"], "./uploadFile/" . $newFileName );
		if (! $test) {
			die ( "move_uploaded_file() faile" );
		}
		
		$lines = file ( './uploadFile/'.$newFileName ); // 將檔案內容寫入一個陣列
		unlink('./uploadFile/'.$newFileName); // 刪除檔案
		
		foreach ($lines as $value) {
	        $text .= $value;
		}
		
		return $text;
	}
}

?>