<?php
$con = mysql_connect("localhost","root","miniserver");
mysql_select_db("my_db", $con);
mysql_query("INSERT INTO Users (username, password, email) VALUES ('Peter', '123456', '1@qq.com')");
mysql_close($con);