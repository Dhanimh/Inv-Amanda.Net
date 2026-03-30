<?php
// Get all barang
function getAllBarang($conn) {
    $query = "SELECT * FROM barang ORDER BY created_at DESC";
    return mysqli_query($conn, $query);
}

// Get barang by id
function getBarangById($conn, $id) {
    $id = mysqli_real_escape_string($conn, $id);
    $query = "SELECT * FROM barang WHERE id = '$id'";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_assoc($result);
}

// Check if kode_barang exists
function isKodeBarangExists($conn, $kode_barang, $exclude_id = null) {
    $kode_barang = mysqli_real_escape_string($conn, $kode_barang);
    $query = "SELECT id FROM barang WHERE kode_barang = '$kode_barang'";
    
    if ($exclude_id) {
        $exclude_id = mysqli_real_escape_string($conn, $exclude_id);
        $query .= " AND id != '$exclude_id'";
    }
    
    $result = mysqli_query($conn, $query);
    return mysqli_num_rows($result) > 0;
}

// Add barang
function addBarang($conn, $data) {
    $kode_barang = mysqli_real_escape_string($conn, $data['kode_barang']);
    $nama_barang = mysqli_real_escape_string($conn, $data['nama_barang']);
    $kategori = mysqli_real_escape_string($conn, $data['kategori']);
    $merk = mysqli_real_escape_string($conn, $data['merk']);
    $satuan = mysqli_real_escape_string($conn, $data['satuan']);
    $stok = mysqli_real_escape_string($conn, $data['stok']);
    $harga = mysqli_real_escape_string($conn, str_replace('.', '', $data['harga']));
    $deskripsi = mysqli_real_escape_string($conn, $data['deskripsi']);
    
    $query = "INSERT INTO barang (kode_barang, nama_barang, kategori, merk, satuan, stok, harga, deskripsi) 
              VALUES ('$kode_barang', '$nama_barang', '$kategori', '$merk', '$satuan', '$stok', '$harga', '$deskripsi')";
    
    return mysqli_query($conn, $query);
}

// Update barang
function updateBarang($conn, $id, $data) {
    $id = mysqli_real_escape_string($conn, $id);
    $kode_barang = mysqli_real_escape_string($conn, $data['kode_barang']);
    $nama_barang = mysqli_real_escape_string($conn, $data['nama_barang']);
    $kategori = mysqli_real_escape_string($conn, $data['kategori']);
    $merk = mysqli_real_escape_string($conn, $data['merk']);
    $satuan = mysqli_real_escape_string($conn, $data['satuan']);
    $stok = mysqli_real_escape_string($conn, $data['stok']);
    $harga = mysqli_real_escape_string($conn, str_replace('.', '', $data['harga']));
    $deskripsi = mysqli_real_escape_string($conn, $data['deskripsi']);
    
    $query = "UPDATE barang SET 
              kode_barang = '$kode_barang',
              nama_barang = '$nama_barang',
              kategori = '$kategori',
              merk = '$merk',
              satuan = '$satuan',
              stok = '$stok',
              harga = '$harga',
              deskripsi = '$deskripsi'
              WHERE id = '$id'";
    
    return mysqli_query($conn, $query);
}

// Delete barang
function deleteBarang($conn, $id) {
    $id = mysqli_real_escape_string($conn, $id);
    
    // Check if barang has transactions first
    $check_query = "SELECT COUNT(*) as total FROM transaksi WHERE id_barang = '$id'";
    $check_result = mysqli_query($conn, $check_query);
    $check_row = mysqli_fetch_assoc($check_result);
    
    if ($check_row['total'] > 0) {
        return false; // Cannot delete if has transactions
    }
    
    $query = "DELETE FROM barang WHERE id = '$id'";
    return mysqli_query($conn, $query);
}

// Update stock
function updateStok($conn, $id_barang, $jumlah, $tipe) {
    $id_barang = mysqli_real_escape_string($conn, $id_barang);
    $jumlah = mysqli_real_escape_string($conn, $jumlah);
    
    if ($tipe === 'masuk') {
        $query = "UPDATE barang SET stok = stok + $jumlah WHERE id = '$id_barang'";
    } else {
        $query = "UPDATE barang SET stok = stok - $jumlah WHERE id = '$id_barang'";
    }
    
    return mysqli_query($conn, $query);
}

// Search barang
function searchBarang($conn, $keyword) {
    $keyword = mysqli_real_escape_string($conn, $keyword);
    $query = "SELECT * FROM barang 
              WHERE kode_barang LIKE '%$keyword%' 
              OR nama_barang LIKE '%$keyword%' 
              OR kategori LIKE '%$keyword%' 
              OR merk LIKE '%$keyword%'
              ORDER BY created_at DESC";
    return mysqli_query($conn, $query);
}
?>