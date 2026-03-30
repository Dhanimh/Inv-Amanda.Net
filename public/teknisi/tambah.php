<?php
require_once '../../includes/auth_check.php';
require_once '../../functions/helpers.php';
require_once '../../functions/teknisi.php';

$page_title = 'Tambah Teknisi';
$active_page = 'teknisi';

// Process form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = sanitize($_POST['nama']);
    $no_hp = sanitize($_POST['no_hp']);
    $alamat = sanitize($_POST['alamat']);
    $status = sanitize($_POST['status']);
    
    // Validate
    $errors = [];
    
    if (empty($nama)) {
        $errors[] = "Nama teknisi harus diisi";
    }
    
    if (empty($errors)) {
        $data = [
            'nama' => $nama,
            'no_hp' => $no_hp,
            'alamat' => $alamat,
            'status' => $status
        ];
        
        if (addTeknisi($conn, $data)) {
            logActivity($user['id'], 'Menambah teknisi baru', "Teknisi: $nama");
            setAlert('success', 'Teknisi berhasil ditambahkan!');
            header("Location: index");
            exit();
        } else {
            $errors[] = "Gagal menambahkan teknisi: " . mysqli_error($conn);
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
                <a href="index" class="hover:text-blue-600">Data Teknisi</a>
                <span>/</span>
                <span class="text-gray-900 font-medium">Tambah Teknisi</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">Tambah Teknisi Baru</h1>
            <p class="text-gray-600 mt-1">Lengkapi form untuk menambah teknisi</p>
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
                    <!-- Nama -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Nama Teknisi <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama" required
                               value="<?= $_POST['nama'] ?? '' ?>"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Contoh: Budi Santoso">
                    </div>

                    <!-- No HP -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            No HP / WhatsApp
                        </label>
                        <input type="text" name="no_hp"
                               value="<?= $_POST['no_hp'] ?? '' ?>"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Contoh: 081234567890">
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select name="status" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="aktif" <?= ($_POST['status'] ?? 'aktif') == 'aktif' ? 'selected' : '' ?>>Aktif</option>
                            <option value="nonaktif" <?= ($_POST['status'] ?? '') == 'nonaktif' ? 'selected' : '' ?>>Non-Aktif</option>
                        </select>
                    </div>

                    <!-- Alamat -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Alamat
                        </label>
                        <textarea name="alamat" rows="4"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Masukkan alamat lengkap"><?= $_POST['alamat'] ?? '' ?></textarea>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex items-center gap-3 mt-6 pt-6 border-t border-gray-200">
                    <button type="submit"
                            class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
                        <span class="flex items-center gap-2">
                            <i data-lucide="check-square" class="w-5 h-5"></i>
                            Simpan Teknisi
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