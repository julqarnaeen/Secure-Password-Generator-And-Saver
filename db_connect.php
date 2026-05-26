<?php


$servername = "localhost";
$username = "root";
$db_pass = "";
$dbname = "password_db";

$conn = mysqli_connect($servername, $username, $db_pass);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}


$sql_db = "CREATE DATABASE IF NOT EXISTS $dbname";
mysqli_query($conn, $sql_db);


mysqli_select_db($conn, $dbname);


$sql_table = "CREATE TABLE IF NOT EXISTS vault (
    id INT AUTO_INCREMENT PRIMARY KEY,
    account_name VARCHAR(100),
    username VARCHAR(150),
    password_val VARCHAR(255)
)";
mysqli_query($conn, $sql_table);
?>
