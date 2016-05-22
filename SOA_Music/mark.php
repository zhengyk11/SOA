<?php
if (isset($_COOKIE["playing"]) && isset($_GET["key"])){
	$id = json_decode(str_replace("\\", "", $_COOKIE["playing"]), true);
	$key = $_GET["key"];
	$con = new mysqli("localhost","root","miniserver","my_db");
	if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
  }
	if ($key == '1'){ //mark 1
		
	}
	else{
		
	}
	$con->query($sql);
	$con->close();
}
?>