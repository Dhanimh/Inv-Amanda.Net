<?php
session_start();

if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
    header("Location: dashboard");
    exit();
} else {
    header("Location: login");
    exit();
}
?>