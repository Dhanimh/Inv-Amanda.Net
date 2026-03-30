<?php
require_once '../../includes/auth_check.php';
require_once '../../functions/helpers.php';
require_once '../../functions/barang.php';

// Get ID from URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Validate ID
if ($id <= 0) {
    setAlert('error', 'ID barang tidak valid!');
    header("Location: index");
    exit();
}

// Get barang data
$barang = getBarangById($conn, $id);

if (!$barang) {
    setAlert('error', 'Barang tidak ditemukan!');
    header("Location: index");
    exit();
}

// Check if barang has transactions
$id_escaped = mysqli_real_escape_string($conn, $id);
$query = "SELECT COUNT(*) as total FROM transaksi WHERE id_barang = '$id_escaped'";
$result = mysqli_query($conn, $query);

if (!$result) {
    setAlert('error', 'Error: ' . mysqli_error($conn));
    header("Location: index");
    exit();
}

$row = mysqli_fetch_assoc($result);
$has_transactions = $row['total'] > 0;

if ($has_transactions) {
    setAlert('error', 'Barang tidak dapat dihapus karena memiliki riwayat transaksi!');
    header("Location: index");
    exit();
}

// Try to delete barang
if (deleteBarang($conn, $id)) {
    logActivity($user['id'], 'Menghapus barang', "Barang: {$barang['nama_barang']} ({$barang['kode_barang']})");
    setAlert('success', 'Barang berhasil dihapus!');
} else {
    setAlert('error', 'Gagal menghapus barang! Error: ' . mysqli_error($conn));
}

header("Location: index");
exit();
?>