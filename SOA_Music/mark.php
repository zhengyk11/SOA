<?php
session_start();
if (isset($_COOKIE["playing"]) && isset($_GET["key"]) && isset($_SESSION["uid"])){
	$mid = json_decode(str_replace("\\", "", $_COOKIE["playing"]), true);
	$key = $_GET["key"];
	$uid = $_SESSION["uid"];
	$con = new mysqli("localhost","root","","my_db");
	// 检测连接
    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
    }
	/*if ($key == '1'){ //将action表中uid为uid，mid为id的项目置1.
        
        
	}
	else{ //将action表中uid为uid，mid为id的项目置0.
		
	}
	$con->query($sql);*/
	$sql = "SELECT * FROM actions WHERE user_id  =  '".$uid."' and music_id = '".$mid."'";
	$res = $con->query($sql);
	if($res!= null && $res->fetch_row()){
		$sql = "UPDATE actions SET star = '".$key."' WHERE user_id  =  '".$uid."' and music_id = '".$mid."'";
			
		    /*$f = fopen("log.txt","w");
		    fwrite($f, $sql);
		    fclose($f);*/
	}
	else{
		$sql = "INSERT INTO actions (music_id, star, user_id) VALUES('".$mid."', '".$key."','".$uid."')";
			
		    /*$f = fopen("log.txt","w");
		    fwrite($f, $sql);
		    fclose($f);*/
	}
	$con->query($sql);
	$con->close();
}
?>