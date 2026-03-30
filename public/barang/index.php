<?php
require_once '../../includes/auth_check.php';
require_once '../../functions/helpers.php';
require_once '../../functions/barang.php';

$page_title = 'Data Barang';
$active_page = 'barang';

// Handle search
$barang_list = isset($_GET['search']) && $_GET['search'] != '' 
    ? searchBarang($conn, $_GET['search']) 
    : getAllBarang($conn);

require_once '../../includes/header.php';
?>

<?php require_once '../../includes/sidebar.php'; ?>

<!-- Main Content -->
<div class="lg:ml-64 pt-16">
    <div class="p-6">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Data Barang</h1>
                <p class="text-gray-600 mt-1">Kelola data barang inventory</p>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="tambah" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <i data-lucide="plus" class="w-5 h-5"></i>
                    Tambah Barang
                </a>
            </div>
        </div>

        <?php showAlert(); ?>

        <!-- Search & Filter -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
            <form method="GET" class="flex flex-col md:flex-row gap-3">
                <div class="flex-1">
                    <input type="text" name="search" value="<?= $_GET['search'] ?? '' ?>" 
                           placeholder="Cari kode barang, nama, kategori, atau merk..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <span class="flex items-center gap-2">
                        <i data-lucide="search" class="w-5 h-5"></i>
                        Cari
                    </span>
                </button>
                <?php if (isset($_GET['search'])): ?>
                <a href="index" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                    Reset
                </a>
                <?php endif; ?>
            </form>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">No</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Kode Barang</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Nama Barang</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Kategori</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Merk</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Stok</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Harga</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php 
                        $no = 1;
                        while ($row = mysqli_fetch_assoc($barang_list)): 
                        ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm text-gray-900"><?= $no++ ?></td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <?= $row['kode_barang'] ?>
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div>
                                    <p class="text-sm font-semibold text-gray-900"><?= $row['nama_barang'] ?></p>
                                    <?php if ($row['deskripsi']): ?>
                                    <p class="text-xs text-gray-500"><?= substr($row['deskripsi'], 0, 50) ?>...</p>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900"><?= $row['kategori'] ?></td>
                            <td class="px-6 py-4 text-sm text-gray-900"><?= $row['merk'] ?></td>
                            <td class="px-6 py-4">
                                <?php if ($row['stok'] < 10): ?>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                        <?= $row['stok'] ?> <?= $row['satuan'] ?>
                                    </span>
                                <?php elseif ($row['stok'] < 20): ?>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                                        <?= $row['stok'] ?> <?= $row['satuan'] ?>
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                        <?= $row['stok'] ?> <?= $row['satuan'] ?>
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-sm font-semibold text-gray-900"><?= formatRupiah($row['harga']) ?></td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <a href="edit.php?id=<?= $row['id'] ?>" 
                                       class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition"
                                       title="Edit">
                                        <i data-lucide="pencil" class="w-5 h-5"></i>
                                    </a>
                                    <a href="hapus.php?id=<?= $row['id'] ?>" 
                                       class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition inline-flex"
                                       title="Hapus"
                                       onclick="return confirm('Apakah Anda yakin ingin menghapus \'<?= htmlspecialchars($row['nama_barang'], ENT_QUOTES) ?>\'?\n\nData yang sudah dihapus tidak dapat dikembalikan!');">
                                        <i data-lucide="trash-2" class="w-5 h-5"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                        <?php if (mysqli_num_rows($barang_list) === 0): ?>
                        <tr>
                            <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                                <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                </svg>
                                <p class="text-lg font-semibold">Data tidak ditemukan</p>
                                <p class="text-sm mt-1">Belum ada data barang atau coba kata kunci lain</p>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<?php require_once '../../includes/footer.php'; ?>