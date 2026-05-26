<?php
include "db_connect.php";


if (isset($_POST['submit'])) {
    $account_name = $_POST['account_name'];
    $username = $_POST['username'];
    $password_val = $_POST['password_val'];

    $sql = "INSERT INTO vault (account_name, username, password_val) VALUES ('$account_name', '$username', '$password_val')";

    mysqli_query($conn, $sql);
}

// Go back to main page
header("Location: index.php");
exit();
?>
