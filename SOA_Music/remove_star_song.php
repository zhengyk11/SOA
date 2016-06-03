<?php
session_start();
if (isset($_SESSION["uid"]) && isset($_GET["mid"])){
	$playing = json_decode(str_replace("\\", "", $_COOKIE["playing"]), true);
	$mid = $_GET["mid"];
	$uid = $_SESSION["uid"];
	//action表中uid为uid，mid为mid的项目的star置0
	//$f = fopen("c:/users/jie/desktop/log.txt","w");
	//	fwrite($f,var_export($mid,true));
	//	fwrite($f,var_export($uid,true));
	//	fclose($f);
	$con = new mysqli("localhost","root","root","my_db");
    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
    }
	
	$sql = "SELECT * FROM actions WHERE user_id  =  '".$uid."' and music_id = '".$mid."'";
	$res = $con->query($sql);
	if($res != null && $res->fetch_row()){
		$sql = "UPDATE actions SET star = '0' WHERE user_id  =  '".$uid."' and music_id = '".$mid."'";
		    /*$f = fopen("log.txt","w");
		    fwrite($f, $sql);
		    fclose($f);*/
	}
	/*else{
		$sql = "INSERT INTO actions (music_id, star, user_id) VALUES('".$mid."', '".$key."','".$uid."')";
			
		    //$f = fopen("log.txt","w");
		    //fwrite($f, $sql);
		    //fclose($f);
	}*/
	$con->query($sql);
	$con->close();
	if ($playing == $mid){
		echo "1";
	}
	else{
		echo "0";
	}
}
?>