<?php
class DB {

	private static $init = false;

	public static function init() {
		if(self::$init) return;
		$con = @mysql_connect('localhost', 'root', 'alacrity229');
		if(!$con) {
			die('无法连接数据库');
		}else{
			self::$init = true;
		}
		mysql_set_charset('utf8',$con);
		mysql_select_db('jwcad', $con);
	}

	public static function query($sql) {
		if(!self::$init) self::init();
		return mysql_query($sql);
	}


	private static $valueStringBuffer = "";
	private static $valueStringTimeStart = "";
	private static $valueStringTimeEnd = "";
	private static $valueStringCount = 0;
	private static $valueStringMax = 600;
	public static function insertLogRecord($datetime, $valueString) {
		if(self::$valueStringCount == 0) {
			self::$valueStringBuffer .= $valueString;
			self::$valueStringTimeStart = $datetime;
		} else {
			self::$valueStringBuffer .= ",".$valueString;
			self::$valueStringTimeEnd = $datetime;
		}
		self::$valueStringCount++;
		if(self::$valueStringCount >= self::$valueStringMax) {
			self::flushLogRecordBuffer();
		}
	}
	public static function flushLogRecordBuffer() {
		if(self::$valueStringTimeEnd == "") {
			echo "Inserting ".self::$valueStringTimeStart."...\n";
		} else {
			echo "Inserting ".self::$valueStringTimeStart." ~ ".self::$valueStringTimeEnd."...\n";
		}
		self::query("INSERT INTO `log` (`datetime`, `user`, `ip`, `page`, `operation`, `sql`) VALUES ".self::$valueStringBuffer) or die(mysql_error()."\n");
		self::$valueStringBuffer = "";
		self::$valueStringTimeStart = "";
		self::$valueStringTimeEnd = "";
		self::$valueStringCount = 0;
	}
	
}

class LogRecord {

	public $datetime, $user, $ip, $page, $operation, $sql;

	public function insert() {
		DB::insertLogRecord($this->datetime, "('$this->datetime', '$this->user', '$this->ip', '$this->page', '$this->operation', '$this->sql')");
		//$sql = "INSERT INTO `log` (`datetime`, `user`, `ip`, `page`, `operation`, `sql`) VALUES ('$this->datetime', '$this->user', '$this->ip', '$this->page', '$this->operation', '$this->sql')";
		//DB::query($sql) or print(mysql_error()."\n");
	}

}

?>