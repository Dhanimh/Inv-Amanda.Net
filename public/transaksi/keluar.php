<?php
require_once '../../includes/auth_check.php';
require_once '../../functions/helpers.php';
require_once '../../functions/barang.php';
require_once '../../functions/teknisi.php';
require_once '../../functions/transaksi.php';

$page_title = 'Barang Keluar';
$active_page = 'keluar';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_barang = sanitize($_POST['id_barang']);
    $id_teknisi = sanitize($_POST['id_teknisi']);
    $jumlah = sanitize($_POST['jumlah']);
    $tanggal = sanitize($_POST['tanggal']);
    $keterangan = sanitize($_POST['keterangan']);
    $errors = [];
    
    if (empty($id_barang)) {
        $errors[] = "Barang harus dipilih";
    }
    
    if (empty($id_teknisi)) {
        $errors[] = "Teknisi harus dipilih";
    }
    
    if ($jumlah <= 0) {
        $errors[] = "Jumlah harus lebih dari 0";
    }
    
    if (!checkStockAvailability($conn, $id_barang, $jumlah)) {
        $errors[] = "Stok barang tidak mencukupi!";
    }
    
    if (empty($tanggal)) {
        $errors[] = "Tanggal harus diisi";
    }
    
    if (empty($errors)) {
        $kode_transaksi = generateKode('TRX-K-', $conn, 'transaksi', 'kode_transaksi');
        
        $data = [
            'kode_transaksi' => $kode_transaksi,
            'id_barang' => $id_barang,
            'id_teknisi' => $id_teknisi,
            'id_user' => $user['id'],
            'tipe' => 'keluar',
            'jumlah' => $jumlah,
            'tanggal' => $tanggal,
            'keterangan' => $keterangan
        ];
        
        mysqli_begin_transaction($conn);
        
        try {
            if (!addTransaksi($conn, $data)) {
                throw new Exception("Gagal menambahkan transaksi");
            }

            if (!updateStok($conn, $id_barang, $jumlah, 'keluar')) {
                throw new Exception("Gagal update stok");
            }
            
            $barang = getBarangById($conn, $id_barang);
            $teknisi = getTeknisiById($conn, $id_teknisi);

            logActivity($user['id'], 'Menambah barang keluar', "Kode: $kode_transaksi, Barang: {$barang['nama_barang']}, Teknisi: {$teknisi['nama']}, Jumlah: $jumlah");
            
            mysqli_commit($conn);
            setAlert('success', 'Transaksi barang keluar berhasil ditambahkan!');
            header("Location: keluar");
            exit();
            
        } catch (Exception $e) {
            mysqli_rollback($conn);
            $errors[] = $e->getMessage();
        }
    }
}

$recent_keluar = getTransaksiByTipe($conn, 'keluar');

require_once '../../includes/header.php';
?>

<?php require_once '../../includes/sidebar.php'; ?>

<div class="lg:ml-64 pt-16">
    <div class="p-6">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Barang Keluar</h1>
            <p class="text-gray-600 mt-1">Catat pengeluaran barang untuk teknisi</p>
        </div>

        <?php showAlert(); ?>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden sticky top-20">
                    <div class="p-6 bg-gradient-to-r from-orange-500 to-red-600 text-white">
                        <h2 class="text-lg font-bold flex items-center gap-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/>
                            </svg>
                            Form Barang Keluar
                        </h2>
                    </div>

                    <?php if (!empty($errors)): ?>
                    <div class="m-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                        <div class="flex items-start gap-3">
                            <i data-lucide="alert-triangle" class="w-5 h-5 text-red-600 mt-0.5"></i>
                            <div class="flex-1">
                                <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                                    <?php foreach ($errors as $error): ?>
                                    <li><?= $error ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <form method="POST" action="" class="p-6 space-y-4">
                         <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Teknisi <span class="text-red-500">*</span>
                            </label>
                            <select name="id_teknisi" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                                <option value="">Pilih Teknisi</option>
                                <?php 
                                $teknisi_list = getActiveTeknisi($conn);
                                while ($t = mysqli_fetch_assoc($teknisi_list)): 
                                ?>
                                <option value="<?= $t['id'] ?>" <?= ($_POST['id_teknisi'] ?? '') == $t['id'] ? 'selected' : '' ?>>
                                    <?= $t['nama'] ?>
                                </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Pilih Barang <span class="text-red-500">*</span>
                            </label>
                            <select name="id_barang" id="id_barang" required onchange="updateStock()"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                                <option value="">Pilih Barang</option>
                                <?php 
                                $barang_list = getAllBarang($conn);
                                while ($b = mysqli_fetch_assoc($barang_list)): 
                                ?>
                                <option value="<?= $b['id'] ?>" data-stok="<?= $b['stok'] ?>" data-satuan="<?= $b['satuan'] ?>">
                                    <?= $b['kode_barang'] ?> - <?= $b['nama_barang'] ?> (Stok: <?= $b['stok'] ?>)
                                </option>
                                <?php endwhile; ?>
                            </select>
                            <p id="stokInfo" class="text-xs text-gray-500 mt-1">Pilih barang untuk melihat stok</p>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Jumlah <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="jumlah" id="jumlah" required min="1"
                                   value="<?= $_POST['jumlah'] ?? '' ?>"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                                   placeholder="0"
                                   onchange="updateStock()">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Tanggal <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="tanggal" required
                                   value="<?= $_POST['tanggal'] ?? date('Y-m-d') ?>"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Keterangan
                            </label>
                            <textarea name="keterangan" rows="3"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                                      placeholder="Tujuan/lokasi instalasi"><?= $_POST['keterangan'] ?? '' ?></textarea>
                        </div>

                        <button type="submit"
                                class="w-full px-6 py-3 bg-gradient-to-r from-orange-500 to-red-600 text-white font-semibold rounded-lg hover:from-orange-600 hover:to-red-700 transition shadow-lg">
                            <span class="flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/>
                                </svg>
                                Tambah Barang Keluar
                            </span>
                        </button>
                    </form>
                </div>
            </div>

            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-lg font-bold text-gray-900">Riwayat Barang Keluar</h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Kode</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Barang</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Teknisi</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Jumlah</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Tanggal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <?php 
                                $count = 0;
                                while ($row = mysqli_fetch_assoc($recent_keluar)): 
                                    if ($count >= 10) break;
                                    $count++;
                                ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <span class="text-xs font-mono bg-red-100 text-red-800 px-2 py-1 rounded">
                                            <?= $row['kode_transaksi'] ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900"><?= $row['nama_barang'] ?></td>
                                    <td class="px-6 py-4 text-sm text-gray-900"><?= $row['nama_teknisi'] ?></td>
                                    <td class="px-6 py-4">
                                        <span class="text-sm font-semibold text-red-600">
                                            -<?= $row['jumlah'] ?> <?= $row['satuan'] ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600"><?= date('d/m/Y', strtotime($row['tanggal'])) ?></td>
                                </tr>
                                <?php endwhile; ?>
                                <?php if (mysqli_num_rows($recent_keluar) === 0): ?>
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                        Belum ada transaksi barang keluar
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="p-4 border-t border-gray-200 bg-gray-50">
                        <a href="riwayat?tipe=keluar" class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                            Lihat Semua Transaksi Keluar →
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script>
function updateStock() {
    const barangSelect = document.getElementById('id_barang');
    const jumlahInput = document.getElementById('jumlah');
    const stokInfo = document.getElementById('stokInfo');
    
    if (barangSelect.value) {
        const selectedOption = barangSelect.options[barangSelect.selectedIndex];
        const stokSekarang = parseInt(selectedOption.dataset.stok || 0);
        const satuan = selectedOption.dataset.satuan || 'Unit';
        const jumlah = parseInt(jumlahInput.value || 0);
        const stokAkhir = stokSekarang - jumlah;
        
        if (jumlah > stokSekarang) {
            stokInfo.innerHTML = `<span class="text-red-600 font-semibold">⚠️ Stok tidak mencukupi! Stok tersedia: ${stokSekarang} ${satuan}</span>`;
        } else {
            stokInfo.innerHTML = `Stok saat ini: <strong>${stokSekarang} ${satuan}</strong> → Stok setelah transaksi: <strong class="${stokAkhir < 10 ? 'text-red-600' : 'text-orange-600'}">${stokAkhir} ${satuan}</strong>`;
        }
    }
}
</script>

<?php require_once '../../includes/footer.php'; ?>