<?php
function getAllTransaksi($conn) {
    $query = "SELECT t.*, b.nama_barang, b.satuan, tek.nama as nama_teknisi, u.nama_lengkap 
              FROM transaksi t 
              LEFT JOIN barang b ON t.id_barang = b.id 
              LEFT JOIN teknisi tek ON t.id_teknisi = tek.id 
              LEFT JOIN users u ON t.id_user = u.id 
              ORDER BY t.created_at DESC";
    return mysqli_query($conn, $query);
}

function getTransaksiByTipe($conn, $tipe) {
    $tipe = mysqli_real_escape_string($conn, $tipe);
    $query = "SELECT t.*, b.nama_barang, b.satuan, tek.nama as nama_teknisi, u.nama_lengkap 
              FROM transaksi t 
              LEFT JOIN barang b ON t.id_barang = b.id 
              LEFT JOIN teknisi tek ON t.id_teknisi = tek.id 
              LEFT JOIN users u ON t.id_user = u.id 
              WHERE t.tipe = '$tipe'
              ORDER BY t.created_at DESC";
    return mysqli_query($conn, $query);
}

function getTransaksiById($conn, $id) {
    $id = mysqli_real_escape_string($conn, $id);
    $query = "SELECT t.*, b.nama_barang, b.satuan, tek.nama as nama_teknisi, u.nama_lengkap 
              FROM transaksi t 
              LEFT JOIN barang b ON t.id_barang = b.id 
              LEFT JOIN teknisi tek ON t.id_teknisi = tek.id 
              LEFT JOIN users u ON t.id_user = u.id 
              WHERE t.id = '$id'";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_assoc($result);
}

function addTransaksi($conn, $data) {
    $kode_transaksi = mysqli_real_escape_string($conn, $data['kode_transaksi']);
    $id_barang = mysqli_real_escape_string($conn, $data['id_barang']);
    $id_teknisi = $data['id_teknisi'] ? mysqli_real_escape_string($conn, $data['id_teknisi']) : 'NULL';
    $id_user = mysqli_real_escape_string($conn, $data['id_user']);
    $tipe = mysqli_real_escape_string($conn, $data['tipe']);
    $jumlah = mysqli_real_escape_string($conn, $data['jumlah']);
    $tanggal = mysqli_real_escape_string($conn, $data['tanggal']);
    $keterangan = mysqli_real_escape_string($conn, $data['keterangan']);
    
    $query = "INSERT INTO transaksi (kode_transaksi, id_barang, id_teknisi, id_user, tipe, jumlah, tanggal, keterangan) 
              VALUES ('$kode_transaksi', '$id_barang', $id_teknisi, '$id_user', '$tipe', '$jumlah', '$tanggal', '$keterangan')";
    
    return mysqli_query($conn, $query);
}

function searchTransaksi($conn, $keyword, $tipe = null) {
    $keyword = mysqli_real_escape_string($conn, $keyword);
    $query = "SELECT t.*, b.nama_barang, b.satuan, tek.nama as nama_teknisi, u.nama_lengkap 
              FROM transaksi t 
              LEFT JOIN barang b ON t.id_barang = b.id 
              LEFT JOIN teknisi tek ON t.id_teknisi = tek.id 
              LEFT JOIN users u ON t.id_user = u.id 
              WHERE (t.kode_transaksi LIKE '%$keyword%' 
              OR b.nama_barang LIKE '%$keyword%' 
              OR tek.nama LIKE '%$keyword%' 
              OR t.keterangan LIKE '%$keyword%')";
    
    if ($tipe) {
        $tipe = mysqli_real_escape_string($conn, $tipe);
        $query .= " AND t.tipe = '$tipe'";
    }
    
    $query .= " ORDER BY t.created_at DESC";
    return mysqli_query($conn, $query);
}

function filterTransaksiByDate($conn, $start_date, $end_date, $tipe = null) {
    $start_date = mysqli_real_escape_string($conn, $start_date);
    $end_date = mysqli_real_escape_string($conn, $end_date);
    
    $query = "SELECT t.*, b.nama_barang, b.satuan, tek.nama as nama_teknisi, u.nama_lengkap 
              FROM transaksi t 
              LEFT JOIN barang b ON t.id_barang = b.id 
              LEFT JOIN teknisi tek ON t.id_teknisi = tek.id 
              LEFT JOIN users u ON t.id_user = u.id 
              WHERE t.tanggal BETWEEN '$start_date' AND '$end_date'";
    
    if ($tipe) {
        $tipe = mysqli_real_escape_string($conn, $tipe);
        $query .= " AND t.tipe = '$tipe'";
    }
    
    $query .= " ORDER BY t.created_at DESC";
    return mysqli_query($conn, $query);
}

function checkStockAvailability($conn, $id_barang, $jumlah) {
    $id_barang = mysqli_real_escape_string($conn, $id_barang);
    $jumlah = mysqli_real_escape_string($conn, $jumlah);
    
    $query = "SELECT stok FROM barang WHERE id = '$id_barang'";
    $result = mysqli_query($conn, $query);
    $barang = mysqli_fetch_assoc($result);
    
    return $barang && $barang['stok'] >= $jumlah;
}
?>