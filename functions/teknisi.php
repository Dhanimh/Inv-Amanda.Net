<?php
function getAllTeknisi($conn) {
    $query = "SELECT * FROM teknisi ORDER BY created_at DESC";
    return mysqli_query($conn, $query);
}

function getTeknisiById($conn, $id) {
    $id = mysqli_real_escape_string($conn, $id);
    $query = "SELECT * FROM teknisi WHERE id = '$id'";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_assoc($result);
}

function getActiveTeknisi($conn) {
    $query = "SELECT * FROM teknisi WHERE status = 'aktif' ORDER BY nama ASC";
    return mysqli_query($conn, $query);
}

function addTeknisi($conn, $data) {
    $nama = mysqli_real_escape_string($conn, $data['nama']);
    $no_hp = mysqli_real_escape_string($conn, $data['no_hp']);
    $alamat = mysqli_real_escape_string($conn, $data['alamat']);
    $status = mysqli_real_escape_string($conn, $data['status']);
    
    $query = "INSERT INTO teknisi (nama, no_hp, alamat, status) 
              VALUES ('$nama', '$no_hp', '$alamat', '$status')";
    
    return mysqli_query($conn, $query);
}

function updateTeknisi($conn, $id, $data) {
    $id = mysqli_real_escape_string($conn, $id);
    $nama = mysqli_real_escape_string($conn, $data['nama']);
    $no_hp = mysqli_real_escape_string($conn, $data['no_hp']);
    $alamat = mysqli_real_escape_string($conn, $data['alamat']);
    $status = mysqli_real_escape_string($conn, $data['status']);
    
    $query = "UPDATE teknisi SET 
              nama = '$nama',
              no_hp = '$no_hp',
              alamat = '$alamat',
              status = '$status'
              WHERE id = '$id'";
    
    return mysqli_query($conn, $query);
}

function deleteTeknisi($conn, $id) {
    $id = mysqli_real_escape_string($conn, $id);
    $query = "DELETE FROM teknisi WHERE id = '$id'";
    return mysqli_query($conn, $query);
}

function searchTeknisi($conn, $keyword) {
    $keyword = mysqli_real_escape_string($conn, $keyword);
    $query = "SELECT * FROM teknisi 
              WHERE nama LIKE '%$keyword%' 
              OR no_hp LIKE '%$keyword%' 
              OR alamat LIKE '%$keyword%'
              ORDER BY created_at DESC";
    return mysqli_query($conn, $query);
}

function getTeknisiStats($conn, $id_teknisi) {
    $id_teknisi = mysqli_real_escape_string($conn, $id_teknisi);
    
    $query = "SELECT 
              COUNT(*) as total_transaksi,
              SUM(CASE WHEN tipe = 'keluar' THEN jumlah ELSE 0 END) as total_barang
              FROM transaksi 
              WHERE id_teknisi = '$id_teknisi'";
    
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_assoc($result);
}
?>