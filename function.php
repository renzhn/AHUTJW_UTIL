<?php
function dateRange($first, $last = '') {
	$dates = array();
	$current = strtotime($first);
	if($last == '') $last = date('Y-m-d');
	$last = strtotime($last);

	while($current <= $last) {
		$dates[] = date('Y-m-d', $current);
		$current = strtotime('+1 day', $current);
	}

	return $dates;
}
function parseLogAndInsert($logFile) {
	$handle = @fopen($logFile, "r");
	if ($handle) {
	    $record = null;
	    while (($line = fgets($handle, 4096)) !== false) {
	        if(preg_match_all("/^(\d{4}-\d{1,2}-\d{1,2}\s*\d{1,2}:\d{1,2}:\d{1,2})\s*用户:(\w*)\s*ip:(.*)/", $line, $matches)) {
	        	//print_r($matches);
	        	if($record != null) $record->insert();
	        	$record = new LogRecord();
	        	$record->datetime = $matches[1][0];
	        	$record->user = $matches[2][0];
	        	$record->ip = trim($matches[3][0]);
	        } else if(preg_match_all("/执行页面：(.*)/", $line, $matches)) {
	        	//print_r($matches);
	        	$record->page = trim($matches[1][0]);

	        } else if(preg_match_all("/执行模块内容：(.*)/", $line, $matches)) {
	        	//print_r($matches);
	        	$record->operation = trim($matches[1][0]);

	        } else if(preg_match_all("/执行sql：(.*)/", $line, $matches)) {
	        	//print_r($matches);
	        	$record->sql = mysql_real_escape_string(trim($matches[1][0]));
	        }

	    }
	    if($record != null) $record->insert();

	    if (!feof($handle)) {
	        echo "Error: unexpected fgets() fail\n";
	    }
	    fclose($handle);
	}
}
?>