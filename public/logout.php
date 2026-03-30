<?php
session_start();
require_once '../config/database.php';

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    mysqli_query($conn, "INSERT INTO log_aktivitas (id_user, aktivitas) VALUES ('$user_id', 'Logout dari sistem')");
}

session_destroy();
header("Location: login");
exit();
?>