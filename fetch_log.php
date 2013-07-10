<?php
include("function.php");
include("class.php");

$dates = dateRange('2013-07-02','2013-07-08');
foreach ($dates as $date) {
	$logUrl = "http://211.70.149.135:88/log/$date-log.txt";
	//$errlogUrl = "http://211.70.149.135:88/log/$date-ErrorLog.txt";
	$logFile = "log\\$date.txt";
	echo "Downloading $logUrl...\n";
	exec("wget -O $logFile $logUrl");
	echo "Saved to $logFile.\n";
	parseLogAndInsert($logFile);
}

?>