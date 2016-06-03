<?php
/**
 * Created by PhpStorm.
 * User: Zhengyk11
 * Date: 2016/4/2 0025
 * Time: 0:08
 */

//include 'list.php';
error_reporting(E_ERROR);
include 'api.php';

session_start();
if (isset($_GET["weibo"]) && isset($_SESSION['token']) && isset($_SESSION['uid'])){
	include_once( 'config.php' );
	include_once( 'saetv2.ex.class.php' );
	$c = new SaeTClientV2( WB_AKEY , WB_SKEY , $_SESSION['token']['access_token'] );
	$uid = $_SESSION['uid'];
	$ms  = $c->user_timeline_by_id($uid);							
	$player_list=array();
	foreach( $ms['statuses'] as $item ){
		
		$context = $item['text'];
		//fwrite($f, $context);
		$res = split_word($context, 0.7, 0);
		//fwrite($f, $context.' ');
		$keywords = explode('0d0a', bin2hex($res).'');
		//fwrite($f, $keywords.' ');
		//fwrite($f, pack("H*", bin2hex($res)).' ');
		foreach( $keywords as $item ){
			$key = pack("H*", $item);
			if($key && $key != 'error'){
					//fwrite($f, var_export($key, true));
					$temp_list = get_music_list($key,5);
								$player_list=array_merge($player_list, $temp_list);
			}
		}
	}
	
	//$res = split_word($_GET['search']);
	$player_list=array_unique($player_list);
	//fwrite($f, var_export($p_list, true));
	setcookie("playlist", json_encode($player_list), time() + 3600);    
	//fclose($f); 
}
else if (isset($_GET["search"])){
	    //setcookie("playlist", "", time()-3600);
		$res = split_word($_GET['search']);
		/* $f = fopen("c:/users/jie/desktop/log.txt","w");
		fwrite($f,var_export($res,true));
		fclose($f); */
        //$jt_record = json_encode($res);
        //echo $jt_record;
		//global $player_list;
		$player_list=array();
		if($res && strstr($res, 'error') == false)
			$player_list = get_music_list($res, 20);
        //$jt_record = json_encode($player_list);
        //echo $jt_record;
	    //$player_list = array();
	    //$player_list[] = "40147552";
	    setcookie("playlist", json_encode($player_list), time()+3600);
}
else if (isset($_GET["mid"])){
	$player_list = (array)json_decode(str_replace("\\", "", $_COOKIE["playlist"]));
	$player_list = array_values($player_list);
	$player_list[] = $_GET["mid"];
	setcookie("playlist", json_encode($player_list), time()+3600);
}
else{
		if (isset($_COOKIE["playlist"])){
			 $player_list = (array)json_decode(str_replace("\\", "", $_COOKIE["playlist"]));
			 $player_list = array_values($player_list);
			 /* $f = fopen("log1.txt","w");
			 fwrite($f,var_export($player_list,true));
		     fclose($f); */ 
		}
		else{
				foreach ($playlist_list as $key) {
					$json = get_playlist_info($key);
					$arr = json_decode($json, true);
					foreach ($arr["result"]["tracks"] as $key2) {
							$id = $key2["id"];
							if (!in_array($id, $player_list)) {
									$player_list[] = strval($id);
							}
					}
				}
				setcookie("playlist", json_encode($player_list), time() + 3600);
		}
}

//获取数据
if (isset($_GET["mid"])){
	$id = $_GET["mid"];
}
else{
	$id = get_music_id($player_list);
}
$music_info = json_decode(get_music_info($id), true);
$lrc_info = json_decode(get_music_lyric($id), true);

//数据库处理
#mysql_query("INSERT INTO Persons (FirstName, LastName, Age) VALUES ('Peter', 'Griffin', '35')");

/* $result = mysql_query("SELECT * FROM Persons");
while($row = mysql_fetch_array($result)){
  echo $row['FirstName'] . " " . $row['LastName'];
  echo "<br />";
} */

#mysql_query("UPDATE Persons SET Age = '36' WHERE FirstName = 'Peter' AND LastName = 'Griffin'");

//处理音乐信息
$play_info["cover"] = $music_info["songs"][0]["album"]["picUrl"];
$play_info["mp3"] = $music_info["songs"][0]["mp3Url"];
$play_info["mp3"] = str_replace("http://m", "http://p", $play_info["mp3"]);
$play_info["music_name"] = $music_info["songs"][0]["name"];
foreach ($music_info["songs"][0]["artists"] as $key) {
		if (!isset($play_info["artists"])) {
				$play_info["artists"] = $key["name"];
		} else {
				$play_info["artists"] .= "," . $key["name"];
		}
}

//处理歌词
if (isset($lrc_info["lrc"]["lyric"])) {
		$lrc = explode("\n", $lrc_info["lrc"]["lyric"]);
		array_pop($lrc);
		foreach ($lrc as $rows) {
				$row = explode("]", $rows);
				if (count($row) == 1) {
						$play_info["lrc"] = "no";
						break;
				} else {
						$lyric = array();
						$col_text = end($row);
						array_pop($row);
						foreach ($row as $key) {
								$time = explode(":", substr($key, 1));
								if(array_key_exists(0, $time) && array_key_exists(1, $time)){
									$time = $time[0] * 60 + $time[1];
									$play_info["lrc"][$time] = $col_text;
								}
						}
				}
		}
} else {
		$play_info["lrc"] = "no";
}
$play_info['star'] = 0;


if (isset($_SESSION['uid'])){

    $con = new mysqli("localhost","root","","my_db");
    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
    }

	$uid = $_SESSION['uid'];
	$sql = "SELECT * FROM actions WHERE user_id  =  '".$uid."' and music_id = '".$id."'";
    $res = $con->query($sql);
    if($res!= null && ($row = $res->fetch_assoc())){
	    if($row['times'] == null){
		    $times = 1;
	    }
	    else{
		    $times = $row['times'] + 1;
	    }
	    $sql = "UPDATE actions SET times = ".$times." WHERE user_id  =  '".$uid."' and music_id = '".$id."'";
			if ($row['star'] == '1'){
				$play_info['star'] = 1;
			}
    }
    else{
	    $sql = "INSERT INTO actions (music_name, music_id, artist, times, star, user_id) VALUES('".$play_info['music_name']."', '".$id."','".$play_info["artists"]."', 1, 0, '".$uid."')";    
    }
    //$f = fopen("log.txt","w");
    //fwrite($f, $sql);
    
	
	$con->query($sql);
	//$sql = "SELECT * FROM actions WHERE user_id  =  '".$uid."' and music_id = '".$id."'";
	//$res = $con->query($sql);
	//$row = $res->fetch_assoc();
	//fwrite($f, var_export($row, true));
	//fclose($f);
	$con->close();
}

setcookie("playing", json_encode($id), time() + 3600);
echo json_encode($play_info);
?>
