<?php
require_once('./Application/core.php');
$DB = new DB();
$DB->init();

if($_SESSION['uid']){
	if($_GET['m']=="sid"){
		if($_GET['u'] || $_GET['q']){
		
			if(!ereg("^[0-9]*$",$_GET['q'])){ rtnmsg(3,"QQ号只允许数字");}
		
			if(strlen($_GET['q']) < 5){	rtnmsg(3,"QQ号太短"); }

			$re = "/sid=([a-zA-Z0-9]*)/"; 
			
			preg_match($re, $_GET['u'], $matches);
			
			if(empty($matches[1])){
				rtnmsg(7,"您的URL中没有SID，请检查后重试。");
			}
			
			if(testsid($_GET['q'],$matches[1])==false){
				rtnmsg(7,"您的SID或QQ号错误。");
			}

			$DB->Savesid($_SESSION['uid'],$_GET['q'],$matches[1]);
			rtnmsg(0,"保存成功");
		}else{
			rtnmsg(1,"信息不完整");
		}	
	}
	
}elseif($_GET['m']=="login"){

	//登陆 START
	if($_GET['u'] || $_GET['p']){
		
		if(!ereg("^[0-9a-zA-Z\_]*$",$_GET['u'])){ rtnmsg(3,"用户名只允许字母，数字，下划线");}
		
		$user = $DB->Query("tl_user","uname",$_GET['u']);
		
		if(strlen($_GET['u']) < 4){	rtnmsg(3,"用户名太短"); }
		
		if(!$user['id']){  rtnmsg(5,"用户不存在"); }
		
		$pw = md5(md5(md5($_GET['p'])));
		
		if($user['upass']!=$pw){  rtnmsg(5,"密码错误"); }
		
		$_SESSION['uid'] = $user['id'];
		$DB->Login($user['id']);
		rtnmsg(0,"登陆成功");
		
	}else{
		rtnmsg(1,"信息不完整");
	}
	//登陆 END
	
	
}elseif($_GET['m']=="reg"){

	//注册 START
	if($_GET['u'] || $_GET['p'] || $_GET['v'] || $_GET['e'] ){
		
		if(!ereg("^[0-9a-zA-Z\_]*$",$_GET['u'])){ rtnmsg(3,"用户名只允许字母，数字，下划线");}
		
		$user = $DB->Query("tl_user","uname",$_GET['u']);
		
		if (!filter_var($_GET['e'], FILTER_VALIDATE_EMAIL)) { rtnmsg(1,"邮箱错误"); }
		
		if($_GET['v']!=$_SESSION['vcode']){ rtnmsg(2,"验证码错误"); }
		
		if(strlen($_GET['u']) < 4){	rtnmsg(3,"用户名太短"); }
		
		if($user['id']){  rtnmsg(5,"用户名已被使用"); }
		
		
		$pw = md5(md5(md5($_GET['p'])));
		$uid = $DB->Register($_GET['u'],$pw,$_GET['e']);
		
		
		
		if($uid > 0 ){
			$_SESSION['uid'] = $uid;
			rtnmsg(0,"注册成功");
		}else{
			rtnmsg(6,"注册失败，未知错误。");
		}		
	}else{
		rtnmsg(1,"信息不完整");
	}
	//注册 END
	
}else{
	rtnmsg(-1,"Access Denied");
}

function rtnmsg($code,$msg){
	$_SESSION['vcode'] = "";
	$rtn = array();
	$rtn['code'] = $code;
	$rtn['msg'] = $msg;
	die(json_encode($rtn));
}

function testsid($qq,$sid){
	$url = "http://ish.z.qq.com/infocenter_v2.jsp?B_UID=".$qq."&sid=".$sid."&g_ut=1";
	$page = url_fetch($url);
	if( strpos($page ,"赞")){
		return true;
	}else{
		return false;	
	}
}
?>