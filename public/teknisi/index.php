<?php
require_once '../../includes/auth_check.php';
require_once '../../functions/helpers.php';
require_once '../../functions/teknisi.php';

$page_title = 'Data Teknisi';
$active_page = 'teknisi';

// Handle search
$teknisi_list = isset($_GET['search']) && $_GET['search'] != '' 
    ? searchTeknisi($conn, $_GET['search']) 
    : getAllTeknisi($conn);

require_once '../../includes/header.php';
?>

<?php require_once '../../includes/sidebar.php'; ?>

<!-- Main Content -->
<div class="lg:ml-64 pt-16">
    <div class="p-6">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Data Teknisi</h1>
                <p class="text-gray-600 mt-1">Kelola data teknisi WiFi</p>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="tambah" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <i data-lucide="plus" class="w-5 h-5"></i>
                    Tambah Teknisi
                </a>
            </div>
        </div>

        <?php showAlert(); ?>

        <!-- Search -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
            <form method="GET" class="flex flex-col md:flex-row gap-3">
                <div class="flex-1">
                    <input type="text" name="search" value="<?= $_GET['search'] ?? '' ?>" 
                           placeholder="Cari nama, no HP, atau alamat teknisi..."
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

        <!-- Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php while ($row = mysqli_fetch_assoc($teknisi_list)): 
                $stats = getTeknisiStats($conn, $row['id']);
            ?>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition overflow-hidden">
                <!-- Card Header -->
                <div class="p-6 <?= $row['status'] == 'aktif' ? 'bg-gradient-to-r from-green-500 to-emerald-600' : 'bg-gradient-to-r from-gray-400 to-gray-500' ?> text-white">
                    <div class="flex items-start justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center text-xl font-bold">
                                <?= strtoupper(substr($row['nama'], 0, 1)) ?>
                            </div>
                            <div>
                                <h3 class="font-bold text-lg"><?= $row['nama'] ?></h3>
                                <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full bg-white bg-opacity-20">
                                    <?= ucfirst($row['status']) ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card Body -->
                <div class="p-6">
                    <div class="space-y-3 mb-4">
                        <div class="flex items-center gap-3 text-gray-700">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            <span class="text-sm"><?= $row['no_hp'] ?: '-' ?></span>
                        </div>
                        <div class="flex items-start gap-3 text-gray-700">
                            <svg class="w-5 h-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span class="text-sm"><?= $row['alamat'] ?: '-' ?></span>
                        </div>
                    </div>

                    <!-- Stats -->
                    <div class="grid grid-cols-2 gap-3 py-3 border-t border-gray-200">
                        <div class="text-center">
                            <p class="text-2xl font-bold text-blue-600"><?= $stats['total_transaksi'] ?></p>
                            <p class="text-xs text-gray-600">Transaksi</p>
                        </div>
                        <div class="text-center">
                            <p class="text-2xl font-bold text-green-600"><?= $stats['total_barang'] ?></p>
                            <p class="text-xs text-gray-600">Barang Terpakai</p>
                        </div>
                    </div>
                </div>

                <!-- Card Footer -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex gap-2">
                    <a href="edit.php?id=<?= $row['id'] ?>" 
                       class="flex-1 px-4 py-2 bg-blue-600 text-white text-center text-sm font-semibold rounded-lg hover:bg-blue-700 transition">
                        Edit
                    </a>
                    <a href="hapus.php?id=<?= $row['id'] ?>"
                       onclick="return confirm('Apakah Anda yakin ingin menghapus teknisi \'<?= htmlspecialchars($row['nama'], ENT_QUOTES) ?>\'?\n\nData yang sudah dihapus tidak dapat dikembalikan!');"
                       class="flex-1 px-4 py-2 bg-red-600 text-white text-center text-sm font-semibold rounded-lg hover:bg-red-700 transition">
                        Hapus
                    </a>
                </div>
            </div>
            <?php endwhile; ?>

            <?php if (mysqli_num_rows($teknisi_list) === 0): ?>
            <div class="col-span-full">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
                    <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <p class="text-lg font-semibold text-gray-900">Data tidak ditemukan</p>
                    <p class="text-sm text-gray-600 mt-1">Belum ada data teknisi atau coba kata kunci lain</p>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

<?php require_once '../../includes/footer.php'; ?>