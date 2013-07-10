<?php
include("function.php");
include("class.php");

$dates = dateRange('2013-07-04','2013-07-09');
foreach ($dates as $date) {
	$logUrl = "http://211.70.149.135:88/log/$date-log.txt";
	//$errlogUrl = "http://211.70.149.135:88/log/$date-ErrorLog.txt";
	$logFile = "log\\$date.txt";
	if(!file_exists($logFile)) {
		echo "Downloading $logUrl...\n";
		exec("wget -O $logFile $logUrl");
		echo "Saved to $logFile.\n";
	}
	echo "Parsing $logFile...\n";
	parseLogAndInsert($logFile);
}

?>