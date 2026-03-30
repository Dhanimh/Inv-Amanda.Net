<?php
require_once '../../includes/auth_check.php';
require_once '../../functions/helpers.php';
require_once '../../functions/transaksi.php';

$tcpdf_path = __DIR__ . '/../../includes/tcpdf/tcpdf.php';
if (!file_exists($tcpdf_path)) {
    die('Error: TCPDF library not found. Please download TCPDF from https://github.com/tecnickcom/tcpdf and extract to includes/tcpdf/ folder');
}

require_once '../../functions/pdf_helpers.php';

$page_title = 'Laporan PDF';
$active_page = 'laporan';

$tipe_filter = $_GET['tipe'] ?? '';
$search = $_GET['search'] ?? '';
$start_date = $_GET['start_date'] ?? '';
$end_date = $_GET['end_date'] ?? '';

if (isset($_GET['generate'])) {
    if ($search) {
        $transaksi_list = searchTransaksi($conn, $search, $tipe_filter ?: null);
    } elseif ($start_date && $end_date) {
        $transaksi_list = filterTransaksiByDate($conn, $start_date, $end_date, $tipe_filter ?: null);
    } elseif ($tipe_filter) {
        $transaksi_list = getTransaksiByTipe($conn, $tipe_filter);
    } else {
        $transaksi_list = getAllTransaksi($conn);
    }
    $transaksi_data = [];
    while ($row = mysqli_fetch_assoc($transaksi_list)) {
        $transaksi_data[] = $row;
    }
    $filters = [
        'tipe' => $tipe_filter,
        'start_date' => $start_date,
        'end_date' => $end_date,
        'search' => $search
    ];
    $filename = 'Laporan_Transaksi_' . date('Y-m-d') . '.pdf';
     generateTransactionPDF($transaksi_data, $filters, $filename);
    exit();
}

require_once '../../includes/header.php';
?>

<style>
@media print {
    body * {
        visibility: hidden;
    }
    .print-area, .print-area * {
        visibility: visible;
    }
    .print-area {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }
    .no-print {
        display: none !important;
    }
}

.a4-preview {
    width: 210mm;
    min-height: 297mm;
    margin: 0 auto;
    background: white;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}

@media screen and (max-width: 1024px) {
    .a4-preview {
        width: 100%;
        box-shadow: none;
    }
}

@media screen and (max-width: 768px) {
    .responsive-table {
        font-size: 0.75rem;
    }
    .responsive-table th,
    .responsive-table td {
        padding: 0.5rem 0.25rem;
    }
}

@media screen and (max-width: 640px) {
    .responsive-table {
        font-size: 0.65rem;
    }
    .responsive-table th,
    .responsive-table td {
        padding: 0.4rem 0.2rem;
    }
}
</style>

<?php require_once '../../includes/sidebar.php'; ?>

<div class="lg:ml-64 pt-16">
    <div class="p-4 md:p-6">
        <div class="mb-4 md:mb-6">
            <h1 class="text-xl md:text-2xl font-bold text-gray-900">Laporan PDF</h1>
            <p class="text-sm md:text-base text-gray-600 mt-1">Cetak laporan transaksi menggunakan PDF</p>
        </div>

        <?php showAlert(); ?>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 md:p-6 mb-4 md:mb-6 no-print">
            <h2 class="text-base md:text-lg font-bold text-gray-900 mb-4">Filter Laporan</h2>
            
            <form method="GET" action="">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4 mb-4 md:mb-6">
                    <div>
                        <label class="block text-xs md:text-sm font-medium text-gray-700 mb-1 md:mb-2">Tipe Transaksi</label>
                        <select name="tipe" class="w-full px-3 md:px-4 py-2 text-sm md:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Semua</option>
                            <option value="masuk" <?= $tipe_filter == 'masuk' ? 'selected' : '' ?>>Barang Masuk</option>
                            <option value="keluar" <?= $tipe_filter == 'keluar' ? 'selected' : '' ?>>Barang Keluar</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs md:text-sm font-medium text-gray-700 mb-1 md:mb-2">Dari Tanggal</label>
                        <input type="date" name="start_date" value="<?= $start_date ?>"
                               class="w-full px-3 md:px-4 py-2 text-sm md:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs md:text-sm font-medium text-gray-700 mb-1 md:mb-2">Sampai Tanggal</label>
                        <input type="date" name="end_date" value="<?= $end_date ?>"
                               class="w-full px-3 md:px-4 py-2 text-sm md:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs md:text-sm font-medium text-gray-700 mb-1 md:mb-2">Pencarian</label>
                        <input type="text" name="search" value="<?= $search ?>" placeholder="Cari..."
                               class="w-full px-3 md:px-4 py-2 text-sm md:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
                <div class="flex flex-wrap gap-2 md:gap-3">
                    <button type="submit" class="flex-1 sm:flex-initial px-4 md:px-6 py-2 text-sm md:text-base bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        <span class="flex items-center justify-center gap-2">
                            <i data-lucide="eye" class="w-4 h-4 md:w-5 md:h-5"></i>
                            Preview
                        </span>
                    </button>
                    <a href="laporan_pdf" class="flex-1 sm:flex-initial px-4 md:px-6 py-2 text-sm md:text-base bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-center">
                        Reset
                    </a>
                    <?php
                    $query_string = http_build_query(array_merge($_GET, ['generate' => '1']));
                    ?>
                    <button type="button" onclick="generatePDF()"
                            class="flex-1 sm:flex-initial px-4 md:px-6 py-2 text-sm md:text-base bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                        <span class="flex items-center justify-center gap-2">
                            <i data-lucide="file-down" class="w-4 h-4 md:w-5 md:h-5"></i>
                            Cetak PDF
                        </span>
                    </button>
                </div>
            </form>
        </div>

        <?php if (isset($_GET['tipe']) || isset($_GET['start_date']) || isset($_GET['search'])): ?>
        <div class="a4-preview print-area">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6 md:p-8">
                <div class="text-center mb-6 md:mb-8 pb-4 md:pb-8 border-b-2 border-gray-300">
                    <h1 class="text-xl sm:text-2xl md:text-3xl font-bold text-gray-900 mb-1 md:mb-2">LAPORAN TRANSAKSI</h1>
                    <h2 class="text-base sm:text-lg md:text-xl font-semibold text-gray-700">Inventory Teknisi WiFi</h2>
                    <p class="text-xs sm:text-sm md:text-base text-gray-600 mt-2">
                        <?php if ($start_date && $end_date): ?>
                            Periode: <?= formatTanggal($start_date) ?> s/d <?= formatTanggal($end_date) ?>
                        <?php else: ?>
                            Tanggal Cetak: <?= formatTanggal(date('Y-m-d')) ?>
                        <?php endif; ?>
                    </p>
                    <?php if ($tipe_filter): ?>
                        <p class="text-xs sm:text-sm md:text-base text-gray-600">Tipe: <?= ucfirst($tipe_filter) ?></p>
                    <?php endif; ?>
                </div>

                <?php
                if ($search) {
                    $report_data = searchTransaksi($conn, $search, $tipe_filter ?: null);
                } elseif ($start_date && $end_date) {
                    $report_data = filterTransaksiByDate($conn, $start_date, $end_date, $tipe_filter ?: null);
                } elseif ($tipe_filter) {
                    $report_data = getTransaksiByTipe($conn, $tipe_filter);
                } else {
                    $report_data = getAllTransaksi($conn);
                }
                ?>

                <div class="overflow-x-auto -mx-4 sm:mx-0">
                    <div class="inline-block min-w-full align-middle">
                        <table class="min-w-full responsive-table text-xs sm:text-sm">
                            <thead>
                                <tr class="border-b-2 border-gray-300">
                                    <th class="py-2 md:py-3 px-1 md:px-2 text-left font-bold">No</th>
                                    <th class="py-2 md:py-3 px-1 md:px-2 text-left font-bold">Kode</th>
                                    <th class="py-2 md:py-3 px-1 md:px-2 text-left font-bold hidden sm:table-cell">Tanggal</th>
                                    <th class="py-2 md:py-3 px-1 md:px-2 text-left font-bold">Tipe</th>
                                    <th class="py-2 md:py-3 px-1 md:px-2 text-left font-bold">Barang</th>
                                    <th class="py-2 md:py-3 px-1 md:px-2 text-left font-bold hidden md:table-cell">Teknisi</th>
                                    <th class="py-2 md:py-3 px-1 md:px-2 text-right font-bold">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $no = 1;
                                $total_masuk = 0;
                                $total_keluar = 0;
                                while ($row = mysqli_fetch_assoc($report_data)): 
                                    if ($row['tipe'] == 'masuk') {
                                        $total_masuk += $row['jumlah'];
                                    } else {
                                        $total_keluar += $row['jumlah'];
                                    }
                                ?>
                                <tr class="border-b border-gray-200">
                                    <td class="py-2 md:py-3 px-1 md:px-2"><?= $no++ ?></td>
                                    <td class="py-2 md:py-3 px-1 md:px-2 text-xs"><?= $row['kode_transaksi'] ?></td>
                                    <td class="py-2 md:py-3 px-1 md:px-2 hidden sm:table-cell"><?= date('d/m/Y', strtotime($row['tanggal'])) ?></td>
                                    <td class="py-2 md:py-3 px-1 md:px-2">
                                        <span class="inline-block px-2 py-1 rounded text-xs <?= $row['tipe'] == 'masuk' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>">
                                            <?= ucfirst($row['tipe']) ?>
                                        </span>
                                    </td>
                                    <td class="py-2 md:py-3 px-1 md:px-2"><?= $row['nama_barang'] ?></td>
                                    <td class="py-2 md:py-3 px-1 md:px-2 hidden md:table-cell"><?= $row['nama_teknisi'] ?: '-' ?></td>
                                    <td class="py-2 md:py-3 px-1 md:px-2 text-right font-medium"><?= $row['jumlah'] ?> <?= $row['satuan'] ?></td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                            <tfoot>
                                <tr class="border-t-2 border-blue-500 font-bold bg-green-50">
                                    <td colspan="6" class="py-2 md:py-3 px-1 md:px-2 text-right text-green-700">Total Barang Masuk:</td>
                                    <td class="py-2 md:py-3 px-1 md:px-2 text-right text-green-700 text-base md:text-lg"><?= number_format($total_masuk, 0, ',', '.') ?></td>
                                </tr>
                                <tr class="font-bold bg-red-50">
                                    <td colspan="6" class="py-2 md:py-3 px-1 md:px-2 text-right text-red-700">Total Barang Keluar:</td>
                                    <td class="py-2 md:py-3 px-1 md:px-2 text-right text-red-700 text-base md:text-lg"><?= number_format($total_keluar, 0, ',', '.') ?></td>
                                </tr>
                                <tr class="font-bold border-b-2 border-blue-500 <?= ($total_masuk - $total_keluar) >= 0 ? 'bg-green-50' : 'bg-red-50' ?>">
                                    <td colspan="6" class="py-2 md:py-3 px-1 md:px-2 text-right <?= ($total_masuk - $total_keluar) >= 0 ? 'text-green-700' : 'text-red-700' ?>">Saldo (Masuk - Keluar):</td>
                                    <td class="py-2 md:py-3 px-1 md:px-2 text-right <?= ($total_masuk - $total_keluar) >= 0 ? 'text-green-700' : 'text-red-700' ?> text-base md:text-lg font-extrabold"><?= number_format($total_masuk - $total_keluar, 0, ',', '.') ?></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <div class="mt-8 md:mt-12 pt-6 md:pt-8 border-t border-gray-300">
                    <div class="grid grid-cols-2 gap-4 text-xs sm:text-sm md:text-base">
                        <div class="text-center">
                            <p class="mb-12 md:mb-16">Mengetahui,</p>
                            <div class="inline-block">
                                <p class="font-bold border-t border-gray-400 pt-2 px-4 md:px-8">Pimpinan</p>
                            </div>
                        </div>
                        <div class="text-center">
                            <p class="mb-12 md:mb-16">Dicetak oleh,</p>
                            <div class="inline-block">
                                <p class="font-bold border-t border-gray-400 pt-2 px-4 md:px-8"><?= $user['nama_lengkap'] ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php else: ?>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 md:p-12 text-center">
            <svg class="w-16 h-16 md:w-24 md:h-24 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <h3 class="text-lg md:text-xl font-bold text-gray-900 mb-2">Pilih Filter Laporan</h3>
            <p class="text-sm md:text-base text-gray-600">Gunakan filter di atas untuk menampilkan preview laporan</p>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
function generatePDF() {
    const btn = event.target;
    const originalText = btn.innerHTML;
    btn.innerHTML = '<span class="flex items-center justify-center gap-2"><svg class="animate-spin h-4 w-4 md:h-5 md:w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Generating PDF...</span>';
    btn.disabled = true;

    const queryParams = new URLSearchParams(window.location.search);
    queryParams.set('generate', '1');
    window.open('?' + queryParams.toString(), '_blank');

    setTimeout(() => {
        btn.innerHTML = originalText;
        btn.disabled = false;
    }, 2000);
}
</script>

<?php require_once '../../includes/footer.php'; ?>