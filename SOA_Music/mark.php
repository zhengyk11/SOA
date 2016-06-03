<?php
session_start();
if (isset($_COOKIE["playing"]) && isset($_GET["key"]) && isset($_SESSION["uid"])){
	$mid = json_decode(str_replace("\\", "", $_COOKIE["playing"]), true);
	$key = $_GET["key"];
	$uid = $_SESSION["uid"];
	$con = new mysqli("localhost","root","root","my_db");
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
	if($res!= null && ($row = $res->fetch_assoc())){
		$sql = "UPDATE actions SET star = '".$key."' WHERE user_id  =  '".$uid."' and music_id = '".$mid."'";
		$con->query($sql);
		/*if($key == '1'){
		$_SESSION['emulate_data'][] = array(    "id" => $row['music_id'],
									"name" => $row['music_name'],
									"artist" => $row['artist'],
									"times" => $row['times'],
								);
	    }
		else if($key == '0'){
			$i=0;
		    foreach($_SESSION['emulate_data'] as $item){
			    if($item['id'] == $mid){
					unset($_SESSION['emulate_data'][$i]);
					break;
				}
				$i=$i+1;
		    }			
		}*/
		    /*$f = fopen("log.txt","w");
		    fwrite($f, $sql);
		    fclose($f);*/
	}
	
	
	
	//else{
	//	$sql = "INSERT INTO actions (music_name, music_id, artisstar, user_id) VALUES('".$mid."', '".$key."','".$uid."')";
			
		    /*$f = fopen("log.txt","w");
		    fwrite($f, $sql);
		    fclose($f);*/
	//}
	
	$con->close();
}
?>