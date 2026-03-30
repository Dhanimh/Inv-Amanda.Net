<?php
function formatRupiah($angka) {
    return 'Rp ' . number_format($angka, 0, ',', '.');
}

function formatTanggal($tanggal) {
    $bulan = array(
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    );
    
    $pecahkan = explode('-', $tanggal);
    return $pecahkan[2] . ' ' . $bulan[(int)$pecahkan[1]] . ' ' . $pecahkan[0];
}

function formatWaktuRelatif($waktu) {
    $sekarang = time();
    $waktu_timestamp = strtotime($waktu);
    $selisih = $sekarang - $waktu_timestamp;
    
    if ($selisih < 60) {
        return 'Baru saja';
    } elseif ($selisih < 3600) {
        $menit = floor($selisih / 60);
        return $menit . ' menit yang lalu';
    } elseif ($selisih < 86400) {
        $jam = floor($selisih / 3600);
        return $jam . ' jam yang lalu';
    } elseif ($selisih < 604800) {
        $hari = floor($selisih / 86400);
        return $hari . ' hari yang lalu';
    } else {
        return date('d/m/Y H:i', $waktu_timestamp);
    }
}

function generateKode($prefix, $conn, $table, $field) {
    $today = date('Ymd');
    $query = "SELECT $field FROM $table WHERE $field LIKE '$prefix$today%' ORDER BY $field DESC LIMIT 1";
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $last_kode = $row[$field];
        $last_number = (int)substr($last_kode, -4);
        $new_number = $last_number + 1;
    } else {
        $new_number = 1;
    }
    
    return $prefix . $today . str_pad($new_number, 4, '0', STR_PAD_LEFT);
}

function sanitize($data) {
    global $conn;
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return mysqli_real_escape_string($conn, $data);
}

function setAlert($type, $message) {
    $_SESSION['alert_type'] = $type;
    $_SESSION['alert_message'] = $message;
}

function showAlert() {
    if (isset($_SESSION['alert_type']) && isset($_SESSION['alert_message'])) {
        $type = $_SESSION['alert_type'];
        $message = $_SESSION['alert_message'];
        
        $colors = [
            'success' => 'green',
            'error' => 'red',
            'warning' => 'yellow',
            'info' => 'blue'
        ];
        
        $color = $colors[$type] ?? 'blue';
        
        echo "<div class='mb-4 p-4 rounded-lg bg-{$color}-50 border border-{$color}-200'>
                <p class='text-{$color}-800'>{$message}</p>
              </div>";
        
        unset($_SESSION['alert_type']);
        unset($_SESSION['alert_message']);
    }
}

function toWIB($datetime) {
    return date('Y-m-d H:i:s', strtotime($datetime . ' +7 hours'));
}

// GANTI BASE URL MENYESUAIKAN DENGAN URL ANDA
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'];
$doc_root = str_replace('\\', '/', rtrim($_SERVER['DOCUMENT_ROOT'], '/'));
$dir = str_replace('\\', '/', dirname(__DIR__));
$base_path = str_replace($doc_root, '', $dir);

if (!defined('BASE_URL')) {
    define('BASE_URL', $protocol . $host . $base_path);
}

if (!defined('BASE_PATH')) {
    define('BASE_PATH', $base_path);
}
?>