<?php
error_reporting(E_ERROR);
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
	
	$uid_get = $c->get_uid();
    $uid = $uid_get['uid'];
	$user_message = $c->show_user_by_id($uid);//根据ID获取用户等基本信息
	$uname = $user_message['screen_name'];
	$_SESSION['uid'] = $uid;
	$_SESSION['uname'] = $uname;
	$_SESSION['uphoto'] = $user_message['profile_image_url'];
	

	$con = new mysqli("localhost","root","","my_db");
	// 检测连接
    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
    }
	
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
	$con->query($sql);
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
		
		$c = new SaeTClientV2( WB_AKEY , WB_SKEY , $_SESSION['token']['access_token'] );
							$uid = $_SESSION['uid'];
                            $ms  = $c->user_timeline_by_id($uid);							
							//$f = fopen("log0.txt","w");
							
							$p_list=array();
							include 'api.php';
							//globe $p_list;
							foreach( $ms['statuses'] as $item ){
								
								$context = $item['text'];
								//fwrite($f, $context);
								$res = split_word($context, 0.8, 0);
								//fwrite($f, $context.' ');
								$keywords = explode('0d0a', bin2hex($res).'');
								//fwrite($f, $keywords.' ');
								//fwrite($f, pack("H*", bin2hex($res)).' ');
								foreach( $keywords as $item ){
									$key = pack("H*", $item);
									if($key && $key != 'error'){
									    //fwrite($f, var_export($key, true));
									    $temp_list = get_music_list($key, 5);
								            $p_list=array_merge($p_list, $temp_list);
									}
						        }
						    }
							
							//$res = split_word($_GET['search']);
							$p_list=array_unique($p_list);
							//fwrite($f, var_export($p_list, true));
							setcookie("playlist", json_encode($p_list), time() + 3600);    
							//fclose($f); 
	
	header("location: http://127.0.0.1/soa/soa_music/index.php");
    	exit;
} else {
    	echo "授权失败。";
}
?>
