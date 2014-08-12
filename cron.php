<?php
require_once('./Application/core.php');
error_reporting(0);
set_time_limit(0);
$DB = new DB();
$DB->init();

updatesum($DB);
runlike($DB);
updatecur($DB);

function testsid($qq,$sid){
	$url = "http://ish.z.qq.com/infocenter_v2.jsp?B_UID=".$qq."&sid=".$sid."&g_ut=1";
	$page = url_fetch($url);
	if( strpos($page ,"赞")){
		return true;
	}else{
		return false;	
	}
}

function updatecur($DB){
	$status = getStatus($DB);
	$current = $status['cur'] + 1;
	if($current > $status['sum']){
		$DB->Cron("cur",0);
	}else{
		$DB->Cron("cur",$current);
	}
	echo "ok";
}
function getStatus($DB){
	$cronstatus = $DB->Query("tl_cron","id","1");
	return $cronstatus;
}
function updatesum($DB){
	$qShowStatus = "SHOW TABLE STATUS LIKE 'tl_user'";
	$result =  mysql_fetch_assoc(mysql_query($qShowStatus));
	$sum =  $result['Auto_increment'];
	$DB->Cron("sum",$sum);
}

function runlike($DB){
	$cookie = "";
	$status = getStatus($DB);
	$uid = $status['cur'];
	$user = $DB->Query("tl_sid","uid",$uid);
	$qq= $user['qq'];
	$sid = $user['sid'];
	$re = '/href="(http:\\/\\/blog[0-9]*?.z.qq.com\\/like.*?)">赞/'; 
	$url = "http://ish.z.qq.com/infocenter_v2.jsp?B_UID=".$qq."&sid=".$sid."&g_ut=1";
	$page = url_fetch($url);
	preg_match_all($re, $page, $matches);
	$ia = count($matches[1]);
	for($i = 0 ; $i < $ia ; $i++){
		sleep(5);
		$cookie2 = like_click($matches[1][$i],$url,$cookie);
		if(!empty($cookie2)){ $cookie = $cookie2 ;}
	}
	echo "ok";
}
function like_click($url,$referer=NULL,$cookie){
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL,html_entity_decode($url));
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (compatible; MSIE 10.0; Windows Phone 8.0; Trident/6.0; IEMobile/10.0; ARM; Touch; NOKIA; Lumia 820)");
		curl_setopt($ch, CURLOPT_HEADER, TRUE);    //表示需要response header
    	curl_setopt($ch, CURLOPT_NOBODY, FALSE); //表示需要response body
		$header = array ();
		$header [] = 'Accept-Language: zh-cn'; 
		$header [] = 'Pragma: no-cache';
		$header [] = 'Referer: '.$referer;
		curl_setopt($ch, CURLOPT_HTTPHEADER,$header);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_COOKIE,$cookie);
		$page = curl_exec($ch);
		curl_close($ch);
		$re = "/Set-Cookie: (.*?);/"; 
		preg_match($re, $page, $matches);
		echo $page;
		return $matches[1];
}
?>