<?php
$con = mysql_connect("localhost","root","miniserver");
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

if (mysql_query("CREATE DATABASE my_db",$con))
  {
  echo "Database created";
  }
else
  {
  echo "Error creating database: " . mysql_error();
  } 

mysql_select_db("my_db", $con);
$sql = "CREATE TABLE Users 
(
id int NOT NULL AUTO_INCREMENT,
PRIMARY KEY(id),
username varchar(24),
password varchar(16),
email varchar(24),
unique (username),
unique (email)
)";
mysql_query($sql,$con);

$sql = "CREATE TABLE Actions 
(
id int NOT NULL AUTO_INCREMENT,
PRIMARY KEY(id),
musicname varchar(24),
mid int,
times int,
star int,
user_id int,
foreign key(user_id) references Users(id) on delete cascade on update cascade
)";
mysql_query($sql,$con);

mysql_close($con);
?>