<?php
require_once '../../includes/auth_check.php';
require_once '../../functions/helpers.php';
require_once '../../functions/transaksi.php';

$page_title = 'Riwayat Transaksi';
$active_page = 'riwayat';

$tipe_filter = $_GET['tipe'] ?? '';
$search = $_GET['search'] ?? '';
$start_date = $_GET['start_date'] ?? '';
$end_date = $_GET['end_date'] ?? '';

if ($search) {
    $transaksi_list = searchTransaksi($conn, $search, $tipe_filter ?: null);
} elseif ($start_date && $end_date) {
    $transaksi_list = filterTransaksiByDate($conn, $start_date, $end_date, $tipe_filter ?: null);
} elseif ($tipe_filter) {
    $transaksi_list = getTransaksiByTipe($conn, $tipe_filter);
} else {
    $transaksi_list = getAllTransaksi($conn);
}

require_once '../../includes/header.php';
?>

<?php require_once '../../includes/sidebar.php'; ?>

<div class="lg:ml-64 pt-16">
    <div class="p-6">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Riwayat Transaksi</h1>
            <p class="text-gray-600 mt-1">Semua riwayat transaksi barang masuk dan keluar</p>
        </div>

        <?php showAlert(); ?>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <?php
            $bulan_ini = date('Y-m');
            
            $query = "SELECT COUNT(*) as total FROM transaksi WHERE DATE_FORMAT(tanggal, '%Y-%m') = '$bulan_ini'";
            $total_transaksi = mysqli_fetch_assoc(mysqli_query($conn, $query))['total'];
            
            $query = "SELECT COALESCE(SUM(jumlah), 0) as total FROM transaksi WHERE tipe = 'masuk' AND DATE_FORMAT(tanggal, '%Y-%m') = '$bulan_ini'";
            $total_masuk = mysqli_fetch_assoc(mysqli_query($conn, $query))['total'];
            
            $query = "SELECT COALESCE(SUM(jumlah), 0) as total FROM transaksi WHERE tipe = 'keluar' AND DATE_FORMAT(tanggal, '%Y-%m') = '$bulan_ini'";
            $total_keluar = mysqli_fetch_assoc(mysqli_query($conn, $query))['total'];
            ?>
            
            <div class="bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl shadow-sm p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 mb-1">Total Transaksi</p>
                        <h3 class="text-3xl font-bold"><?= $total_transaksi ?></h3>
                        <p class="text-sm text-blue-100 mt-1">Bulan ini</p>
                    </div>
                    <div class="w-14 h-14 bg-white bg-opacity-20 rounded-xl flex items-center justify-center">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl shadow-sm p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 mb-1">Barang Masuk</p>
                        <h3 class="text-3xl font-bold"><?= $total_masuk ?></h3>
                        <p class="text-sm text-green-100 mt-1">Bulan ini</p>
                    </div>
                    <div class="w-14 h-14 bg-white bg-opacity-20 rounded-xl flex items-center justify-center">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-orange-500 to-red-600 rounded-xl shadow-sm p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-orange-100 mb-1">Barang Keluar</p>
                        <h3 class="text-3xl font-bold"><?= $total_keluar ?></h3>
                        <p class="text-sm text-orange-100 mt-1">Bulan ini</p>
                    </div>
                    <div class="w-14 h-14 bg-white bg-opacity-20 rounded-xl flex items-center justify-center">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
            <form method="GET" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Transaksi</label>
                        <select name="tipe" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Semua</option>
                            <option value="masuk" <?= $tipe_filter == 'masuk' ? 'selected' : '' ?>>Barang Masuk</option>
                            <option value="keluar" <?= $tipe_filter == 'keluar' ? 'selected' : '' ?>>Barang Keluar</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Dari Tanggal</label>
                        <input type="date" name="start_date" value="<?= $start_date ?>"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sampai Tanggal</label>
                        <input type="date" name="end_date" value="<?= $end_date ?>"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pencarian</label>
                        <input type="text" name="search" value="<?= $search ?>" placeholder="Cari kode, barang..."
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        <span class="flex items-center gap-2">
                            <i data-lucide="search" class="w-5 h-5"></i>
                            Filter
                        </span>
                    </button>
                    <a href="riwayat" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                        Reset
                    </a>
                    <a href="<?= BASE_URL ?>/public/laporan/laporan_pdf?<?= http_build_query($_GET) ?>" 
                       class="ml-auto px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                        <span class="flex items-center gap-2">
                            <i data-lucide="file-down" class="w-5 h-5"></i>
                            Export PDF
                        </span>
                    </a>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">No</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Kode</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Tipe</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Barang</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Teknisi</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Jumlah</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">User</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php 
                        $no = 1;
                        while ($row = mysqli_fetch_assoc($transaksi_list)): 
                        ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm text-gray-900"><?= $no++ ?></td>
                            <td class="px-6 py-4">
                                <span class="text-xs font-mono px-2 py-1 rounded <?= $row['tipe'] == 'masuk' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                    <?= $row['kode_transaksi'] ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600"><?= date('d/m/Y', strtotime($row['tanggal'])) ?></td>
                            <td class="px-6 py-4">
                                <?php if ($row['tipe'] === 'masuk'): ?>
                                    <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/>
                                        </svg>
                                        Masuk
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/>
                                        </svg>
                                        Keluar
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900"><?= $row['nama_barang'] ?></td>
                            <td class="px-6 py-4 text-sm text-gray-900"><?= $row['nama_teknisi'] ?: '-' ?></td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-semibold <?= $row['tipe'] == 'masuk' ? 'text-green-600' : 'text-red-600' ?>">
                                    <?= $row['tipe'] == 'masuk' ? '+' : '-' ?><?= $row['jumlah'] ?> <?= $row['satuan'] ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600"><?= $row['nama_lengkap'] ?></td>
                            <td class="px-6 py-4 text-sm text-gray-600"><?= $row['keterangan'] ?: '-' ?></td>
                        </tr>
                        <?php endwhile; ?>
                        <?php if (mysqli_num_rows($transaksi_list) === 0): ?>
                        <tr>
                            <td colspan="9" class="px-6 py-8 text-center text-gray-500">
                                <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <p class="text-lg font-semibold">Data tidak ditemukan</p>
                                <p class="text-sm mt-1">Belum ada transaksi atau coba filter lain</p>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<?php require_once '../../includes/footer.php'; ?>