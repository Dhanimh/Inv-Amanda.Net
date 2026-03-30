<?php
require_once '../../includes/auth_check.php';
require_once '../../functions/helpers.php';
require_once '../../functions/teknisi.php';

$id = $_GET['id'] ?? 0;
$teknisi = getTeknisiById($conn, $id);

if (!$teknisi) {
    setAlert('error', 'Teknisi tidak ditemukan!');
    header("Location: index");
    exit();
}

// Check if teknisi has transactions
$query = "SELECT COUNT(*) as total FROM transaksi WHERE id_teknisi = '$id'";
$result = mysqli_query($conn, $query);
$has_transactions = mysqli_fetch_assoc($result)['total'] > 0;

if ($has_transactions) {
    setAlert('error', 'Teknisi tidak dapat dihapus karena memiliki riwayat transaksi!');
} else {
    if (deleteTeknisi($conn, $id)) {
        logActivity($user['id'], 'Menghapus teknisi', "Teknisi: {$teknisi['nama']}");
        setAlert('success', 'Teknisi berhasil dihapus!');
    } else {
        setAlert('error', 'Gagal menghapus teknisi!');
    }
}

header("Location: index");
exit();
?>