<?php
require_once('config.php');
error_reporting(E_ERROR);
session_start();

/* Error Handler */

function onError()  
{  
    echo "System Fatal Error. Please contact admin.";
}

if(!DEBUG){
	error_reporting(0);
	register_shutdown_function('onError');
}

/* Simple Url Route */

function route_without_rewrite(){
	return explode("/",$_SERVER["QUERY_STRING"]);
}

/* Timer */

function runtime($mode = 0) {
    static $t;
    if(!$mode) {
        $t = microtime();
        return;
    }
    $t1 = microtime();
    list($m0,$s0) = split(" ",$t);
    list($m1,$s1) = split(" ",$t1);
    return sprintf("%.3f 毫秒",($s1+$m1-$s0-$m0)*1000);
}


/* App */
class App
{
	static function install() {
        $DB = new DB();
		$DB->init();
		$DB->install();
    }
	static function run() {
        $url = route_without_rewrite();
		if(empty($url[1])){ header('location:/?/index'); break;}
		require_once('pages.php');
		Page::LoadContent($url[1].".phtml");
    }
}

/* Database */
class DB
{
	private $conn;
	public function init(){
		if(!$this->conn){
			$this->conn=mysql_connect(DBIP,DBUSER,DBPW)or die('Database connect error! Please check the config!');            
			mysql_select_db(DBNAME,$this->conn);
			mysql_query("set names 'utf8'");
		}
	}
	public function install(){
		mysql_query("source install.sql");	
	}
	public function Query($table,$field,$value){
		$sql = "select * from ".mysql_real_escape_string($table)." where ".mysql_real_escape_string($field)." = '".mysql_real_escape_string($value)."'";
		$result = mysql_fetch_array(mysql_query($sql,$this->conn));
		return $result;
	}
	public function Register($un,$pw,$email){
		$time = time();
		$ip = $_SERVER['REMOTE_ADDR'];
		$sql = "INSERT INTO tl_user (uname,upass,email,lastlogin,lastip) VALUES ('$un','$pw','$email','$time','$ip')";
		$result = mysql_query($sql,$this->conn);
		$uid = mysql_insert_id();
		return $uid;
	}
	public function Savesid($uid,$qq,$sid){
		$sql = "delete from tl_sid where uid = '$uid'";
		$result = mysql_query($sql,$this->conn);

		$sql = "INSERT INTO tl_sid (uid,qq,sid) VALUES ('$uid','$qq','$sid')";
		$result = mysql_query($sql,$this->conn);
		return $result;
	}
	public function Login($uid){
		$time = time();
		$ip = $_SERVER['REMOTE_ADDR'];
		$sql = "UPDATE tl_user SET lastlogin = '$time' , lastip = '$ip' where id = '$uid'";
		$result = mysql_query($sql,$this->conn);
		return $result;
	}
	public function Delete($table,$field,$value){
		$sql = "delete * from ".mysql_real_escape_string($table)." where ".mysql_real_escape_string($field)." = '".mysql_real_escape_string($value)."'";
		$result = mysql_query($sql,$this->conn);
		return $result;
	}
	public function Cron($type,$value){
		if($type=="cur"){
			$sql = "UPDATE tl_cron SET cur = '$value'  where id = '1'";
			$result = mysql_query($sql,$this->conn);
		}elseif($type=="sum"){
			$sql = "UPDATE tl_cron SET sum = '$value' where id = '1'";
			$result = mysql_query($sql,$this->conn);
		}else{
			return "unknow";	
		}		
	}
}

function url_fetch($url){
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (compatible; MSIE 10.0; Windows Phone 8.0; Trident/6.0; IEMobile/10.0; ARM; Touch; NOKIA; Lumia 820)");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HEADER, TRUE);    //表示需要response header
    curl_setopt($ch, CURLOPT_NOBODY, FALSE); //表示需要response body 
	$page = curl_exec($ch);
	curl_close($ch);
	return $page;
}
?>