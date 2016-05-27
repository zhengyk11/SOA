<?php
session_start();
if (isset($_COOKIE["playing"]) && isset($_GET["key"]) && isset($_SESSION["uid"])){
	$id = json_decode(str_replace("\\", "", $_COOKIE["playing"]), true);
	$key = $_GET["key"];
	$con = new mysqli("localhost","root","miniserver","my_db");
	if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
  }
	if ($key == '1'){ //将action表中uid为uid，mid为id的项目置1.
		
	}
	else{ //将action表中uid为uid，mid为id的项目置0.
		
	}
	$con->query($sql);
	$con->close();
}
?>