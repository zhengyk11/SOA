<?php
error_reporting(E_ERROR);
session_start();

include_once( 'config.php' );
include_once( 'saetv2.ex.class.php' );

$f = fopen("log.txt","w");
fwrite($f, "get in callback.php\n");
		

$o = new SaeTOAuthV2( WB_AKEY , WB_SKEY );

date_default_timezone_set('Asia/Shanghai');

if (isset($_REQUEST['code'])) {
	fwrite($f, "$_REQUEST['code']\n");
	$keys = array();
	$keys['code'] = $_REQUEST['code'];
	$keys['redirect_uri'] = WB_CALLBACK_URL;
	try {
		$token = $o->getAccessToken( 'code', $keys ) ;
	} catch (OAuthException $e) {
	}
}
fwrite($f, "$_REQUEST['code'] done\n");

if ($token) {
	fwrite($f, "$token\n");
	$_SESSION['token'] = $token;
	setcookie( 'weibojs_'.$o->client_id, http_build_query($token) );
	$c = new SaeTClientV2( WB_AKEY , WB_SKEY , $_SESSION['token']['access_token'] );
	
	$uid_get = $c->get_uid();
    $uid = $uid_get['uid'];
    fwrite($f, $uid.'\n');
	$user_message = $c->show_user_by_id($uid);//根据ID获取用户等基本信息
	$uname = $user_message['screen_name'];
	$_SESSION['uid'] = $uid;
	$_SESSION['uname'] = $uname;
	$_SESSION['uphoto'] = $user_message['profile_image_url'];
	

	$con = new mysqli("localhost","root","root","my_db");
	// 检测连接
    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
    }
	fwrite($f, '$con create successfully\n');
	$sql = "SELECT * FROM users  WHERE weibo_id  =  '".$uid."'";
	if($con->query($sql)!= null && $con->query($sql)->fetch_assoc()){
		$sql = "UPDATE users SET username = '".$uname."', last_time = now() WHERE weibo_id = '".$uid."'";
		/*$f = fopen("log.txt","w");
		fwrite($f, $sql);
		fclose($f);*/
	}else{
		$sql = "INSERT INTO users (weibo_id, username, last_time) VALUES('".$uid."','".$uname."', now())";
		/*$f = fopen("log.txt","w");
		fwrite($f, $sql);
		fclose($f);*/
	}
	fwrite($f, '$con query start\n');
	$con->query($sql);
	fwrite($f, '$con query done\n');
	/*$dataArray=array();
	while($row=$result->fetch_assoc()){
		$dataArray[]=$row;
	} 
	print_r($dataArray);
	
	$sql = " SELECT * FROM users  WHERE username  =  '1'";
	$result = $con->query($sql);
	$dataArray=array();
	while($row=$result->fetch_row()){
		$dataArray[]=$row;
	} 
	print_r($dataArray);*/
	/*$f = fopen("log.txt","w");
	//fwrite($f, $sql.' ');
	fwrite($f,var_export($uname,true));
	fwrite($f,var_export($uid,true));
	fwrite($f,var_export('20'.date('y-m-d h:i:s', time()),true));
	fclose($f);*/
    $con->close();
	header("location: http://thu02.chinacloudapp.cn/SOA_Music/index.php");
	fclose($f);
    exit;
} else {
	fclose($f);
    	echo "授权失败。";
}
?>
