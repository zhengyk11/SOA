<?php
$con = new mysqli("localhost","root","","my_db");
$con->query("INSERT INTO Users (username, password, weibo_id) VALUES ('Peter', '123456', '111111')");
$con->close();
?>