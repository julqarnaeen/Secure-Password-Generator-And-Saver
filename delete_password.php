<?php
include "db_connect.php";

// Delete by id from URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM vault WHERE id = '$id'";
    mysqli_query($conn, $sql);
}


header("Location: index.php");
exit();
?>
