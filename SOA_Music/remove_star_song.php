<?php
session_start();
if (isset($_SESSION["uid"]) && isset($_GET["mid"])){
	$mid = $_GET["mid"];
	$uid = $_SESSION["uid"];
	//action表中uid为uid，mid为mid的项目的star置0
	$f = fopen("c:/users/jie/desktop/log.txt","w");
		fwrite($f,var_export($mid,true));
		fwrite($f,var_export($uid,true));
		fclose($f);
}
?>