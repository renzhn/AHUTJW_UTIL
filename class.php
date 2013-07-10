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
	
}

class LogRecord {

	public $datetime, $user, $ip, $page, $operation, $sql;

	public function insert() {
		$sql = "INSERT INTO `log` VALUES ('$this->datetime', '$this->user', '$this->ip', '$this->page', '$this->operation', '$this->sql')";
		echo "Inserting $this->datetime $this->user $this->ip $this->page...\n";
		DB::query($sql) or print(mysql_error()."\n");
	}

}

?>