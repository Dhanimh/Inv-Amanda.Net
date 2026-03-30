<?php
require_once '../../includes/auth_check.php';
require_once '../../functions/helpers.php';
require_once '../../functions/barang.php';

$page_title = 'Tambah Barang';
$active_page = 'barang';

// Process form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kode_barang = sanitize($_POST['kode_barang']);
    $nama_barang = sanitize($_POST['nama_barang']);
    $kategori = sanitize($_POST['kategori']);
    $merk = sanitize($_POST['merk']);
    $satuan = sanitize($_POST['satuan']);
    $stok = sanitize($_POST['stok']);
    $harga = sanitize($_POST['harga']);
    $deskripsi = sanitize($_POST['deskripsi']);
    
    // Validate
    $errors = [];
    
    if (empty($kode_barang)) {
        $errors[] = "Kode barang harus diisi";
    } elseif (isKodeBarangExists($conn, $kode_barang)) {
        $errors[] = "Kode barang sudah digunakan";
    }
    
    if (empty($nama_barang)) {
        $errors[] = "Nama barang harus diisi";
    }
    
    if (empty($kategori)) {
        $errors[] = "Kategori harus diisi";
    }
    
    if (empty($satuan)) {
        $errors[] = "Satuan harus diisi";
    }
    
    if ($stok < 0) {
        $errors[] = "Stok tidak boleh negatif";
    }
    
    if (empty($errors)) {
        $data = [
            'kode_barang' => $kode_barang,
            'nama_barang' => $nama_barang,
            'kategori' => $kategori,
            'merk' => $merk,
            'satuan' => $satuan,
            'stok' => $stok,
            'harga' => $harga,
            'deskripsi' => $deskripsi
        ];
        
        if (addBarang($conn, $data)) {
            logActivity($user['id'], 'Menambah barang baru', "Barang: $nama_barang ($kode_barang)");
            setAlert('success', 'Barang berhasil ditambahkan!');
            header("Location: index");
            exit();
        } else {
            $errors[] = "Gagal menambahkan barang: " . mysqli_error($conn);
        }
    }
}

require_once '../../includes/header.php';
?>

<?php require_once '../../includes/sidebar.php'; ?>

<!-- Main Content -->
<div class="lg:ml-64 pt-16">
    <div class="p-6">
        <!-- Page Header -->
        <div class="mb-6">
            <div class="flex items-center gap-2 text-sm text-gray-600 mb-2">
                <a href="index" class="hover:text-blue-600">Data Barang</a>
                <span>/</span>
                <span class="text-gray-900 font-medium">Tambah Barang</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">Tambah Barang Baru</h1>
            <p class="text-gray-600 mt-1">Lengkapi form untuk menambah barang</p>
        </div>

        <?php if (!empty($errors)): ?>
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
            <div class="flex items-start gap-3">
                <i data-lucide="alert-triangle" class="w-5 h-5 text-red-600 mt-0.5"></i>
                <div class="flex-1">
                    <h3 class="text-red-800 font-semibold mb-1">Terjadi Kesalahan</h3>
                    <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                        <?php foreach ($errors as $error): ?>
                        <li><?= $error ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Form -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <form method="POST" action="" class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Kode Barang -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Kode Barang <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="kode_barang" required
                               value="<?= $_POST['kode_barang'] ?? '' ?>"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Contoh: BRG001">
                        <p class="text-xs text-gray-500 mt-1">Kode unik untuk identifikasi barang</p>
                    </div>

                    <!-- Nama Barang -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Nama Barang <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama_barang" required
                               value="<?= $_POST['nama_barang'] ?? '' ?>"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Contoh: Router WiFi AC1200">
                    </div>

                    <!-- Kategori -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Kategori <span class="text-red-500">*</span>
                        </label>
                        <select name="kategori" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Pilih Kategori</option>
                            <option value="Router" <?= ($_POST['kategori'] ?? '') == 'Router' ? 'selected' : '' ?>>Router</option>
                            <option value="Access Point" <?= ($_POST['kategori'] ?? '') == 'Access Point' ? 'selected' : '' ?>>Access Point</option>
                            <option value="Switch" <?= ($_POST['kategori'] ?? '') == 'Switch' ? 'selected' : '' ?>>Switch</option>
                            <option value="Kabel" <?= ($_POST['kategori'] ?? '') == 'Kabel' ? 'selected' : '' ?>>Kabel</option>
                            <option value="ONT" <?= ($_POST['kategori'] ?? '') == 'ONT' ? 'selected' : '' ?>>ONT</option>
                            <option value="Modem" <?= ($_POST['kategori'] ?? '') == 'Modem' ? 'selected' : '' ?>>Modem</option>
                            <option value="Antena" <?= ($_POST['kategori'] ?? '') == 'Antena' ? 'selected' : '' ?>>Antena</option>
                            <option value="Lainnya" <?= ($_POST['kategori'] ?? '') == 'Lainnya' ? 'selected' : '' ?>>Lainnya</option>
                        </select>
                    </div>

                    <!-- Merk -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Merk
                        </label>
                        <input type="text" name="merk"
                               value="<?= $_POST['merk'] ?? '' ?>"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Contoh: TP-Link">
                    </div>

                    <!-- Satuan -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Satuan <span class="text-red-500">*</span>
                        </label>
                        <select name="satuan" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Pilih Satuan</option>
                            <option value="Unit" <?= ($_POST['satuan'] ?? '') == 'Unit' ? 'selected' : '' ?>>Unit</option>
                            <option value="Pcs" <?= ($_POST['satuan'] ?? '') == 'Pcs' ? 'selected' : '' ?>>Pcs</option>
                            <option value="Meter" <?= ($_POST['satuan'] ?? '') == 'Meter' ? 'selected' : '' ?>>Meter</option>
                            <option value="Roll" <?= ($_POST['satuan'] ?? '') == 'Roll' ? 'selected' : '' ?>>Roll</option>
                            <option value="Box" <?= ($_POST['satuan'] ?? '') == 'Box' ? 'selected' : '' ?>>Box</option>
                            <option value="Set" <?= ($_POST['satuan'] ?? '') == 'Set' ? 'selected' : '' ?>>Set</option>
                        </select>
                    </div>

                    <!-- Stok -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Stok Awal <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="stok" required min="0"
                               value="<?= $_POST['stok'] ?? '0' ?>"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="0">
                    </div>

                    <!-- Harga -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Harga Satuan
                        </label>
                        <input type="text" name="harga"
                               value="<?= $_POST['harga'] ?? '' ?>"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="0"
                               onkeyup="formatCurrency(this)">
                        <p class="text-xs text-gray-500 mt-1">Tanpa titik atau koma</p>
                    </div>

                    <!-- Deskripsi -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Deskripsi
                        </label>
                        <textarea name="deskripsi" rows="4"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Deskripsi barang (opsional)"><?= $_POST['deskripsi'] ?? '' ?></textarea>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex items-center gap-3 mt-6 pt-6 border-t border-gray-200">
                    <button type="submit"
                            class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
                        <span class="flex items-center gap-2">
                            <i data-lucide="check-square" class="w-5 h-5"></i>
                            Simpan Barang
                        </span>
                    </button>
                    <a href="index"
                       class="px-6 py-2 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

<?php require_once '../../includes/footer.php'; ?>