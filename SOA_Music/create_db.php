<?php
// 创建连接
$con = new mysqli("localhost","root","");
// 检测连接
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Create database
$sql = "CREATE DATABASE my_db";
if ($con->query($sql) === TRUE) {
    echo "Database created successfully";
} else {
    echo "Error creating database: " . $con->error;
}

$con = new mysqli("localhost","root","","my_db");
$sql = "CREATE TABLE Users 
(
id int NOT NULL AUTO_INCREMENT,
PRIMARY KEY(id),
username varchar(24),
password varchar(16),
weibo_id varchar(24),
last_time datetime,
unique (username),
unique (weibo_id)
)";
$con->query($sql);

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
$con->query($sql);

$con->close();
?>