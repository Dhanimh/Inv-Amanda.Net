<aside id="sidebar" class="fixed left-0 top-16 bottom-0 w-64 bg-white border-r border-gray-200 overflow-y-auto transition-transform duration-300 -translate-x-full lg:translate-x-0 z-40">
    <nav class="p-4 space-y-1">
        <a href="<?= BASE_URL ?>/public/dashboard" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 transition <?= $active_page === 'dashboard' ? 'active font-semibold text-blue-600' : '' ?>">
            <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
            Dashboard
        </a>
        <?php if (isAdmin()): ?>
        <a href="<?= BASE_URL ?>/public/barang/index" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 transition <?= $active_page === 'barang' ? 'active font-semibold text-blue-600' : '' ?>">
            <i data-lucide="package" class="w-5 h-5"></i>
            Data Barang
        </a>

        <a href="<?= BASE_URL ?>/public/teknisi/index" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 transition <?= $active_page === 'teknisi' ? 'active font-semibold text-blue-600' : '' ?>">
            <i data-lucide="users" class="w-5 h-5"></i>
            Data Teknisi
        </a>
        <?php endif; ?>

        <div class="pt-4 mt-4 border-t border-gray-200">
            <p class="px-4 text-xs font-semibold text-gray-400 uppercase mb-2">Transaksi</p>
        </div>

        <a href="<?= BASE_URL ?>/public/transaksi/masuk" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 transition <?= $active_page === 'masuk' ? 'active font-semibold text-blue-600' : '' ?>">
            <i data-lucide="package-plus" class="w-5 h-5"></i>
            Barang Masuk
        </a>

        <a href="<?= BASE_URL ?>/public/transaksi/keluar" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 transition <?= $active_page === 'keluar' ? 'active font-semibold text-blue-600' : '' ?>">
            <i data-lucide="package-minus" class="w-5 h-5"></i>
            Barang Keluar
        </a>

        <?php if (isAdmin()): ?>
        <a href="<?= BASE_URL ?>/public/transaksi/riwayat" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 transition <?= $active_page === 'riwayat' ? 'active font-semibold text-blue-600' : '' ?>">
            <i data-lucide="history" class="w-5 h-5"></i>
            Riwayat Transaksi
        </a>

        <div class="pt-4 mt-4 border-t border-gray-200">
            <p class="px-4 text-xs font-semibold text-gray-400 uppercase mb-2">Laporan</p>
        </div>

        <a href="<?= BASE_URL ?>/public/laporan/laporan_pdf" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 transition <?= $active_page === 'laporan' ? 'active font-semibold text-blue-600' : '' ?>">
            <i data-lucide="file-text" class="w-5 h-5"></i>
            Cetak Laporan
        </a>

        <div class="pt-4 mt-4 border-t border-gray-200">
            <p class="px-4 text-xs font-semibold text-gray-400 uppercase mb-2">Admin</p>
        </div>

        <a href="<?= BASE_URL ?>/public/user/index" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 transition <?= $active_page === 'user' ? 'active font-semibold text-blue-600' : '' ?>">
            <i data-lucide="shield-check" class="w-5 h-5"></i>
            Kelola User
        </a>
        <?php endif; ?>
    </nav>
</aside>

<div onclick="toggleSidebar()" class="fixed inset-0 bg-black bg-opacity-50 z-30 lg:hidden hidden" id="sidebarOverlay"></div>

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        sidebar.classList.toggle('-translate-x-full');
        overlay.classList.toggle('hidden');
    }
</script>