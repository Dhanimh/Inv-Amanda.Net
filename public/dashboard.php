<?php
require_once '../includes/auth_check.php';
require_once '../functions/helpers.php';

$page_title = 'Dashboard';
$active_page = 'dashboard';
$stats = [];
$query = "SELECT COUNT(*) as total FROM barang";
$result = mysqli_query($conn, $query);
$stats['total_barang'] = mysqli_fetch_assoc($result)['total'];

$query = "SELECT COUNT(*) as total FROM teknisi WHERE status = 'aktif'";
$result = mysqli_query($conn, $query);
$stats['total_teknisi'] = mysqli_fetch_assoc($result)['total'];

$bulan_ini = date('Y-m');
$query = "SELECT COUNT(*) as total FROM transaksi WHERE DATE_FORMAT(tanggal, '%Y-%m') = '$bulan_ini'";
$result = mysqli_query($conn, $query);
$stats['total_transaksi'] = mysqli_fetch_assoc($result)['total'];

$query = "SELECT COALESCE(SUM(jumlah), 0) as total FROM transaksi WHERE tipe = 'masuk' AND DATE_FORMAT(tanggal, '%Y-%m') = '$bulan_ini'";
$result = mysqli_query($conn, $query);
$stats['barang_masuk'] = mysqli_fetch_assoc($result)['total'];

$query = "SELECT COALESCE(SUM(jumlah), 0) as total FROM transaksi WHERE tipe = 'keluar' AND DATE_FORMAT(tanggal, '%Y-%m') = '$bulan_ini'";
$result = mysqli_query($conn, $query);
$stats['barang_keluar'] = mysqli_fetch_assoc($result)['total'];

$query = "SELECT COUNT(*) as total FROM barang WHERE stok < 10";
$result = mysqli_query($conn, $query);
$stats['stok_menipis'] = mysqli_fetch_assoc($result)['total'];

$query = "SELECT la.*, u.nama_lengkap, u.role, u.foto_profil 
          FROM log_aktivitas la 
          JOIN users u ON la.id_user = u.id 
          ORDER BY la.created_at DESC 
          LIMIT 5";
$activities = mysqli_query($conn, $query);

$query = "SELECT * FROM barang WHERE stok < 10 ORDER BY stok ASC LIMIT 5";
$low_stock = mysqli_query($conn, $query);

$query = "SELECT t.*, b.nama_barang, tek.nama as nama_teknisi, u.nama_lengkap 
          FROM transaksi t 
          LEFT JOIN barang b ON t.id_barang = b.id 
          LEFT JOIN teknisi tek ON t.id_teknisi = tek.id 
          LEFT JOIN users u ON t.id_user = u.id 
          ORDER BY t.created_at DESC 
          LIMIT 5";
$recent_transactions = mysqli_query($conn, $query);

function getActivityIcon($aktivitas) {
    $aktivitas_lower = strtolower($aktivitas);
    
    if (strpos($aktivitas_lower, 'login') !== false) {
        return '<i data-lucide="log-in" class="w-4 h-4"></i>';
    } elseif (strpos($aktivitas_lower, 'logout') !== false) {
        return '<i data-lucide="log-out" class="w-4 h-4"></i>';
    } elseif (strpos($aktivitas_lower, 'tambah') !== false || strpos($aktivitas_lower, 'menambah') !== false) {
        return '<i data-lucide="plus-circle" class="w-4 h-4"></i>';
    } elseif (strpos($aktivitas_lower, 'edit') !== false || strpos($aktivitas_lower, 'ubah') !== false || strpos($aktivitas_lower, 'update') !== false) {
        return '<i data-lucide="pencil" class="w-4 h-4"></i>';
    } elseif (strpos($aktivitas_lower, 'hapus') !== false || strpos($aktivitas_lower, 'delete') !== false) {
        return '<i data-lucide="trash-2" class="w-4 h-4"></i>';
    } else {
        return '<i data-lucide="activity" class="w-4 h-4"></i>';
    }
}

function getActivityColor($aktivitas) {
    $aktivitas_lower = strtolower($aktivitas);
    
    if (strpos($aktivitas_lower, 'login') !== false) {
        return 'bg-green-100 text-green-700';
    } elseif (strpos($aktivitas_lower, 'logout') !== false) {
        return 'bg-gray-100 text-gray-700';
    } elseif (strpos($aktivitas_lower, 'tambah') !== false || strpos($aktivitas_lower, 'menambah') !== false) {
        return 'bg-blue-100 text-blue-700';
    } elseif (strpos($aktivitas_lower, 'edit') !== false || strpos($aktivitas_lower, 'ubah') !== false) {
        return 'bg-yellow-100 text-yellow-700';
    } elseif (strpos($aktivitas_lower, 'hapus') !== false) {
        return 'bg-red-100 text-red-700';
    } else {
        return 'bg-purple-100 text-purple-700';
    }
}

require_once '../includes/header.php';
?>

<?php require_once '../includes/sidebar.php'; ?>

<div class="lg:ml-64 pt-16">
    <div class="p-6">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
            <p class="text-gray-600 mt-1">Selamat datang, <?= $user['nama_lengkap'] ?>!</p>
        </div>

        <?php showAlert(); ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Total Barang</p>
                        <h3 class="text-3xl font-bold text-gray-900"><?= $stats['total_barang'] ?></h3>
                    </div>
                    <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center">
                        <i data-lucide="package" class="w-7 h-7 text-blue-600"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Teknisi Aktif</p>
                        <h3 class="text-3xl font-bold text-gray-900"><?= $stats['total_teknisi'] ?></h3>
                    </div>
                    <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center">
                        <i data-lucide="users" class="w-7 h-7 text-green-600"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Transaksi Bulan Ini</p>
                        <h3 class="text-3xl font-bold text-gray-900"><?= $stats['total_transaksi'] ?></h3>
                    </div>
                    <div class="w-14 h-14 bg-purple-100 rounded-xl flex items-center justify-center">
                        <i data-lucide="file-text" class="w-7 h-7 text-purple-600"></i>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl shadow-sm p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 mb-1">Barang Masuk</p>
                        <h3 class="text-3xl font-bold"><?= $stats['barang_masuk'] ?></h3>
                        <p class="text-sm text-green-100 mt-1">Bulan ini</p>
                    </div>
                    <div class="w-14 h-14 bg-white bg-opacity-20 rounded-xl flex items-center justify-center">
                        <i data-lucide="package-plus" class="w-7 h-7"></i>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-br from-orange-500 to-red-600 rounded-xl shadow-sm p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-orange-100 mb-1">Barang Keluar</p>
                        <h3 class="text-3xl font-bold"><?= $stats['barang_keluar'] ?></h3>
                        <p class="text-sm text-orange-100 mt-1">Bulan ini</p>
                    </div>
                    <div class="w-14 h-14 bg-white bg-opacity-20 rounded-xl flex items-center justify-center">
                        <i data-lucide="package-minus" class="w-7 h-7"></i>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-br from-yellow-500 to-orange-500 rounded-xl shadow-sm p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-yellow-100 mb-1">Stok Menipis</p>
                        <h3 class="text-3xl font-bold"><?= $stats['stok_menipis'] ?></h3>
                        <p class="text-sm text-yellow-100 mt-1">Perlu restock</p>
                    </div>
                    <div class="w-14 h-14 bg-white bg-opacity-20 rounded-xl flex items-center justify-center">
                        <i data-lucide="alert-triangle" class="w-7 h-7 text-white"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-lg font-bold text-gray-900">Transaksi Terbaru</h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Kode</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Barang</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Tipe</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Jumlah</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Tanggal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <?php while ($row = mysqli_fetch_assoc($recent_transactions)): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm text-gray-900"><?= $row['kode_transaksi'] ?></td>
                                    <td class="px-6 py-4 text-sm text-gray-900"><?= $row['nama_barang'] ?></td>
                                    <td class="px-6 py-4">
                                        <?php if ($row['tipe'] === 'masuk'): ?>
                                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Masuk</span>
                                        <?php else: ?>
                                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Keluar</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900"><?= $row['jumlah'] ?></td>
                                    <td class="px-6 py-4 text-sm text-gray-500"><?= date('d/m/Y', strtotime($row['tanggal'])) ?></td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="p-4 border-t border-gray-200 bg-gray-50">
                        <a href="<?= BASE_URL ?>/public/transaksi/riwayat" class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                            Lihat Semua Transaksi →
                        </a>
                    </div>
                </div>

                <?php if ($stats['stok_menipis'] > 0): ?>
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            <i data-lucide="alert-triangle" class="w-5 h-5 text-yellow-500"></i>
                            Peringatan Stok Menipis
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            <?php while ($row = mysqli_fetch_assoc($low_stock)): ?>
                            <div class="flex items-center justify-between p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                                <div>
                                    <p class="font-semibold text-gray-900"><?= $row['nama_barang'] ?></p>
                                    <p class="text-sm text-gray-600"><?= $row['kode_barang'] ?></p>
                                </div>
                                <div class="text-right">
                                    <p class="text-2xl font-bold text-yellow-600"><?= $row['stok'] ?></p>
                                    <p class="text-xs text-gray-600"><?= $row['satuan'] ?></p>
                                </div>
                            </div>
                            <?php endwhile; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <div class="space-y-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="p-6 border-b border-gray-200 flex items-center justify-between">
                        <h2 class="text-lg font-bold text-gray-900">Log Aktivitas</h2>
                        <span class="px-2 py-1 text-xs font-semibold bg-blue-100 text-blue-800 rounded-full">
                            Live
                        </span>
                    </div>
                    <div class="p-6 max-h-[600px] overflow-y-auto">
                        <div class="space-y-4">
                            <?php if (mysqli_num_rows($activities) > 0): ?>
                                <?php while ($row = mysqli_fetch_assoc($activities)): ?>
                                <div class="flex gap-3 group hover:bg-gray-50 p-2 rounded-lg transition">
                                    <div class="flex-shrink-0">
                                        <?php if (!empty($row['foto_profil']) && file_exists(__DIR__ . '/../' . $row['foto_profil'])): ?>
                                            <img src="<?= BASE_URL ?>/<?= $row['foto_profil'] ?>" 
                                                 alt="<?= $row['nama_lengkap'] ?>"
                                                 class="w-10 h-10 rounded-full object-cover border-2 border-gray-200">
                                        <?php else: ?>
                                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-full flex items-center justify-center text-white text-sm font-semibold">
                                                <?= strtoupper(substr($row['nama_lengkap'], 0, 1)) ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-start justify-between gap-2">
                                            <div class="flex-1">
                                                <p class="text-sm text-gray-900 font-medium"><?= $row['nama_lengkap'] ?></p>
                                                <div class="flex items-center gap-2 mt-1">
                                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-medium rounded-full <?= getActivityColor($row['aktivitas']) ?>">
                                                        <?= getActivityIcon($row['aktivitas']) ?>
                                                        <span class="truncate max-w-[120px]"><?= $row['aktivitas'] ?></span>
                                                    </span>
                                                </div>
                                            </div>
                                            <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full <?= $row['role'] == 'admin' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' ?>">
                                                <?= ucfirst($row['role']) ?>
                                            </span>
                                        </div>
                                        <?php if (!empty($row['keterangan'])): ?>
                                        <p class="text-xs text-gray-600 mt-2 bg-gray-50 p-2 rounded border border-gray-200">
                                            <?= $row['keterangan'] ?>
                                        </p>
                        <?php endif; ?>
                    <p class="text-xs text-gray-400 mt-2 flex items-center gap-1">
                        <i data-lucide="clock" class="w-3 h-3"></i>
                                        <?php
                                            date_default_timezone_set('Asia/Jakarta');
                                            $created_at_wib = date('Y-m-d H:i:s', strtotime($row['created_at'] . ' +7 hours'));   
                                            echo formatWaktuRelatif($created_at_wib);
                                            ?>
                                            </p>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <div class="text-center py-8">
                                    <i data-lucide="clipboard-list" class="w-12 h-12 text-gray-300 mx-auto mb-3"></i>
                                    <p class="text-sm text-gray-500">Belum ada aktivitas</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <?php if (isAdmin()): ?>
                <div class="bg-gradient-to-br from-blue-600 to-indigo-600 rounded-xl shadow-sm p-6 text-white">
                    <h3 class="text-lg font-bold mb-4">Quick Actions</h3>
                    <div class="space-y-3">
                        <a href="<?= BASE_URL ?>/public/transaksi/masuk" class="block p-3 bg-white bg-opacity-20 rounded-lg hover:bg-opacity-30 transition">
                            <div class="flex items-center gap-3">
                                <i data-lucide="plus" class="w-5 h-5"></i>
                                <span class="font-medium">Tambah Barang Masuk</span>
                            </div>
                        </a>
                        <a href="<?= BASE_URL ?>/public/transaksi/keluar" class="block p-3 bg-white bg-opacity-20 rounded-lg hover:bg-opacity-30 transition">
                            <div class="flex items-center gap-3">
                                <i data-lucide="minus" class="w-5 h-5"></i>
                                <span class="font-medium">Tambah Barang Keluar</span>
                            </div>
                        </a>
                        <a href="<?= BASE_URL ?>/public/barang/index" class="block p-3 bg-white bg-opacity-20 rounded-lg hover:bg-opacity-30 transition">
                            <div class="flex items-center gap-3">
                                <i data-lucide="package" class="w-5 h-5"></i>
                                <span class="font-medium">Kelola Barang</span>
                            </div>
                        </a>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>