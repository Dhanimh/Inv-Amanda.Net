<?php
session_start();

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'];
$doc_root = str_replace('\\', '/', rtrim($_SERVER['DOCUMENT_ROOT'], '/'));
$dir = str_replace('\\', '/', dirname(__DIR__));
$base_path = str_replace($doc_root, '', $dir);
$base_url = $protocol . $host . $base_path . "/public/";

if (!isset($_SESSION['user_id'])) {
    header("Location: " . $base_url . "login");
    exit();
}

require_once __DIR__ . '/../config/database.php';

$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

if (!$user) {
    session_destroy();
    header("Location: " . $base_url . "login");
    exit();
}

function isAdmin() {
    global $user;
    return $user['role'] === 'admin';
}

function logActivity($id_user, $aktivitas, $keterangan = '') {
    global $conn;
    
    $query = "INSERT INTO log_aktivitas (id_user, aktivitas, keterangan)
              VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "iss", $id_user, $aktivitas, $keterangan);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}
?>