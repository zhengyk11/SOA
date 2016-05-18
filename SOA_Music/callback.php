<?php
session_start();

include_once( 'config.php' );
include_once( 'saetv2.ex.class.php' );

$o = new SaeTOAuthV2( WB_AKEY , WB_SKEY );

date_default_timezone_set('Asia/Shanghai');

if (isset($_REQUEST['code'])) {
	$keys = array();
	$keys['code'] = $_REQUEST['code'];
	$keys['redirect_uri'] = WB_CALLBACK_URL;
	try {
		$token = $o->getAccessToken( 'code', $keys ) ;
	} catch (OAuthException $e) {
	}
}

if ($token) {
	$_SESSION['token'] = $token;
	setcookie( 'weibojs_'.$o->client_id, http_build_query($token) );
	$c = new SaeTClientV2( WB_AKEY , WB_SKEY , $_SESSION['token']['access_token'] );
	$con = new mysqli("localhost","root","","my_db");
	// 检测连接
    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
    }
	/*mysql_select_db("my_db", $con);
$sql = "CREATE TABLE Users 
(
id int NOT NULL AUTO_INCREMENT,
PRIMARY KEY(id),
username varchar(24),
password varchar(16),
weibo_id varchar(24),
last_time datetime,
unique (username),
unique (weibo_id)
)";
mysql_query($sql,$con);*/
	$uid_get = $c->get_uid();
    $uid = $uid_get['uid'];
	$user_message = $c->show_user_by_id( $uid);//根据ID获取用户等基本信息
	$uname = $user_message['screen_name'];
	//$current_time = '20'.date('y-m-d h:i:s', time());
	$sql = "insert into users (weibo_id,username,last_time) values('".$uid."','".$uname."',now()) 
	on duplicate key update username = '".$uname."',last_time = now()";
	//mysql_query($sql,$con);
	//$sql = "insert into users (last_time) values(".$current_time.")";

	$con->query($sql);
	/*$f = fopen("log.txt","w");
	//fwrite($f, $sql.' ');
	fwrite($f,var_export($uname,true));
	fwrite($f,var_export($uid,true));
	fwrite($f,var_export('20'.date('y-m-d h:i:s', time()),true));
	fclose($f);*/
    $con->close();
	
	header("location: http://127.0.0.1/soa-master/soa_music/index.php");
    exit;
} else {
    echo "授权失败。";
}
?>
