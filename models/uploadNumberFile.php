<?php

class uploadNumberFile {
	function processFile($objFile) {
		if ($objFile["error"] != 0) {
			return;
		}
		if($objFile ["tmp_name"] == "") {
			return "上傳失敗";
		}
		if($objFile ["type"] != "text/plain") {
			return "檔案格式錯誤";
		}
		
		$f = fopen($objFile ["tmp_name"], "r"); // 開啟檔案
		while (!feof($f)) // 判斷文件是否已達結尾
		{
			$line = fgets($f); // 讀取一行
			$text .= Trim($line);
		}
		fclose($f); // 關閉檔案
		
		return $text;
	}
}

?>