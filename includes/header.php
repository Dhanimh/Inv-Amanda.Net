<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?? 'Dashboard' ?> - Inv-Amanda.Net</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .sidebar-link { transition: all 0.2s; }
        .sidebar-link:hover { background: #f1f5f9; color: #0f172a; }
        .sidebar-link.active { background: #eff6ff; color: #2563eb; }
        .profile-image {
            object-fit: cover;
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 tracking-tight">
    <nav class="bg-white shadow-sm border-b border-gray-200 fixed top-0 left-0 right-0 z-50">
        <div class="px-4 lg:px-6">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-4 flex-1">
                    <button onclick="toggleSidebar()" class="lg:hidden p-2 rounded-lg hover:bg-gray-100">
                        <i data-lucide="menu" class="w-6 h-6"></i>
                    </button>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center">
                            <i data-lucide="package" class="w-6 h-6 text-white"></i>
                        </div>
                        <div class="hidden md:block min-w-max">
                            <h1 class="text-lg font-bold text-gray-900">Inv-Amanda.Net</h1>
                            <p class="text-xs text-gray-500">Sistem Manajemen WiFi</p>
                        </div>
                    </div>

                    
                    <div class="hidden lg:flex max-w-md w-full ml-8 mr-auto">
                    <form action="<?= BASE_URL ?>/public/transaksi/riwayat" method="GET" class="relative w-full">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i data-lucide="search" class="w-4 h-4 text-gray-400"></i>
                        </div>
                        <input type="text" name="search" class="block w-full pl-10 pr-3 py-2 border border-gray-200 rounded-xl leading-5 bg-gray-50 text-sm placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all font-medium" placeholder="Cari transaksi, barang, atau fitur...">
                    </form>
                </div> <!-- Close Search -->
                </div> <!-- Close Left Section -->

                
                <div class="flex items-center gap-4 md:gap-6">
                    <div class="hidden lg:flex items-center gap-3 px-4 py-1.5 bg-slate-50 border border-slate-200 rounded-xl">
                        <div class="w-7 h-7 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                            <i data-lucide="calendar-clock" class="w-3.5 h-3.5"></i>
                        </div>
                        <div class="text-left">
                            <p id="liveTime" class="text-sm font-bold tracking-tight text-slate-800 leading-none">--:--:--</p>
                            <p id="liveDate" class="text-[10px] font-semibold text-slate-500 uppercase mt-0.5">------</p>
                        </div>
                    </div>

                    <div class="relative">
                        <button onclick="toggleUserMenu()" class="flex items-center gap-3 p-2 rounded-xl hover:bg-slate-100 transition">
                            <div class="hidden md:block text-right">
                                <p class="text-sm font-semibold text-gray-900"><?= $user['nama_lengkap'] ?></p>
                                <p class="text-xs text-gray-500"><?= ucfirst($user['role']) ?></p>
                            </div>
                            <i data-lucide="chevron-down" class="w-4 h-4 text-gray-500"></i>
                        </button>
                        <div id="userMenu" class="hidden absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-[0_4px_20px_-4px_rgba(0,0,0,0.1)] border border-gray-100 py-2">
                            <div class="px-4 py-3 border-b border-gray-100">
                                <div class="flex items-center gap-3">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-gray-900 truncate"><?= $user['nama_lengkap'] ?></p>
                                        <p class="text-xs text-gray-500">@<?= $user['username'] ?></p>
                                    </div>
                                </div>
                            </div>

                            <div class="py-1">
                                <a href="<?= BASE_URL ?>/public/user/edit_profil" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition">
                                    <i data-lucide="user-pen" class="w-4 h-4 text-gray-400"></i>
                                    <span>Edit Profil</span>
                                </a>
                                <?php if (isAdmin()): ?>
                                <a href="<?= BASE_URL ?>/public/user/index" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition">
                                    <i data-lucide="users" class="w-4 h-4 text-gray-400"></i>
                                    <span>Kelola User</span>
                                </a>
                                <?php endif; ?>
                            </div>
                            
                            <hr class="my-1 border-gray-200">
                            
                            <a href="<?= BASE_URL ?>/public/logout" class="flex items-center gap-3 px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition">
                                <i data-lucide="log-out" class="w-4 h-4 text-red-500"></i>
                                <span>Logout</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <script>
        function updateDateTime() {
            const now = new Date();
            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
            
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            
            const dayName = days[now.getDay()];
            const date = String(now.getDate()).padStart(2, '0');
            const monthName = months[now.getMonth()];
            const year = now.getFullYear();

            const timeString = `${hours}:${minutes}:${seconds} WIB`;
            const dateString = `${dayName}, ${date} ${monthName} ${year}`;
            
            const timeEl = document.getElementById('liveTime');
            const dateEl = document.getElementById('liveDate');
            if(timeEl) timeEl.textContent = timeString;
            if(dateEl) dateEl.textContent = dateString;
        }
        setInterval(updateDateTime, 1000);
        document.addEventListener('DOMContentLoaded', updateDateTime);
        
        function toggleUserMenu() {
            const menu = document.getElementById('userMenu');
            menu.classList.toggle('hidden');
        }
        
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('-translate-x-full');
        }

        document.addEventListener('click', function(e) {
            const userMenuButton = e.target.closest('button[onclick="toggleUserMenu()"]');
            const userMenu = document.getElementById('userMenu');
            
            if (!userMenuButton && !userMenu.contains(e.target)) {
                userMenu.classList.add('hidden');
            }
        });

        document.addEventListener('click', function(e) {
            const sidebar = document.getElementById('sidebar');
            const sidebarButton = e.target.closest('button[onclick="toggleSidebar()"]');
            
            if (window.innerWidth < 1024 && !sidebarButton && !sidebar?.contains(e.target)) {
                sidebar?.classList.add('-translate-x-full');
            }
        });
    </script>