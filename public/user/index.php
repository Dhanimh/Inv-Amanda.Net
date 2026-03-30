<?php
require_once '../../includes/auth_check.php';
require_once '../../functions/helpers.php';
require_once '../../functions/user.php';

if (!isAdmin()) {
    setAlert('error', 'Akses ditolak! Hanya admin yang dapat mengakses halaman ini.');
    header("Location: ../dashboard");
    exit();
}

$page_title = 'Kelola User';
$active_page = 'user';

$user_list = getAllUsers($conn);

require_once '../../includes/header.php';
?>

<?php require_once '../../includes/sidebar.php'; ?>

<div class="lg:ml-64 pt-16">
    <div class="p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Kelola User</h1>
                <p class="text-gray-600 mt-1">Manajemen pengguna sistem</p>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="tambah" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <i data-lucide="plus" class="w-5 h-5"></i>
                    Tambah User
                </a>
            </div>
        </div>

        <?php showAlert(); ?>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">No</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">User</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Role</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Terdaftar</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php 
                        $no = 1;
                        while ($row = mysqli_fetch_assoc($user_list)): 
                        ?>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm text-gray-900"><?= $no++ ?></td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <?php if (!empty($row['foto_profil']) && file_exists('../../' . $row['foto_profil'])): ?>
                                        <img src="../../<?= $row['foto_profil'] ?>" 
                                             alt="<?= $row['nama_lengkap'] ?>"
                                             class="w-10 h-10 rounded-full object-cover border-2 border-gray-200">
                                    <?php else: ?>
                                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-full flex items-center justify-center text-white font-semibold">
                                            <?= strtoupper(substr($row['nama_lengkap'], 0, 1)) ?>
                                        </div>
                                    <?php endif; ?>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900"><?= $row['nama_lengkap'] ?></div>
                                        <div class="text-xs text-gray-500">@<?= $row['username'] ?></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                <?php if (!empty($row['email'])): ?>
                                    <div class="flex items-center gap-2">
                                        <i data-lucide="mail" class="w-4 h-4 text-gray-400"></i>
                                        <?= $row['email'] ?>
                                    </div>
                                <?php else: ?>
                                    <span class="text-gray-400 italic">Belum diisi</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4">
                                <?php if ($row['role'] === 'admin'): ?>
                                    <span class="inline-flex items-center gap-1 px-3 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                        <i data-lucide="shield-check" class="w-3 h-3"></i>
                                        Admin
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center gap-1 px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                        <i data-lucide="user" class="w-3 h-3"></i>
                                        Teknisi
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900"><?= date('d/m/Y', strtotime($row['created_at'])) ?></div>
                                <div class="text-xs text-gray-500"><?= date('H:i', strtotime($row['created_at'])) ?> WIB</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <a href="edit?id=<?= $row['id'] ?>" 
                                       class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition"
                                       title="Edit User">
                                        <i data-lucide="pencil" class="w-5 h-5"></i>
                                    </a>
                                    <?php if ($row['id'] != $user['id']): ?>
                                    <button onclick="confirmDelete('hapus?id=<?= $row['id'] ?>', '<?= $row['username'] ?>')"
                                            class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition"
                                            title="Hapus User">
                                        <i data-lucide="trash-2" class="w-5 h-5"></i>
                                    </button>
                                    <?php else: ?>
                                    <div class="px-3 py-1 text-xs text-gray-400 bg-gray-100 rounded-lg">
                                        Akun Anda
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                        
                        <?php if (mysqli_num_rows($user_list) === 0): ?>
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    <div>
                                        <p class="text-gray-500 font-medium">Belum ada user</p>
                                        <p class="text-sm text-gray-400 mt-1">Tambahkan user pertama Anda</p>
                                    </div>
                                    <a href="tambah" class="mt-2 inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition">
                                        <i data-lucide="plus" class="w-4 h-4"></i>
                                        Tambah User
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-600">
                        Total: <span class="font-semibold text-gray-900"><?= mysqli_num_rows($user_list) ?> user</span>
                    </span>
                    <div class="flex items-center gap-4">
                        <?php
                        mysqli_data_seek($user_list, 0);
                        $admin_count = 0;
                        $teknisi_count = 0;
                        while ($row = mysqli_fetch_assoc($user_list)) {
                            if ($row['role'] === 'admin') {
                                $admin_count++;
                            } else {
                                $teknisi_count++;
                            }
                        }
                        mysqli_data_seek($user_list, 0);
                        ?>
                        <span class="text-gray-600">
                            <span class="inline-flex items-center gap-1">
                                <span class="w-2 h-2 bg-purple-500 rounded-full"></span>
                                <?= $admin_count ?> Admin
                            </span>
                        </span>
                        <span class="text-gray-600">
                            <span class="inline-flex items-center gap-1">
                                <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                                <?= $teknisi_count ?> Teknisi
                            </span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(url, username) {
    if (confirm(`Yakin ingin menghapus user "${username}"?\n\nTindakan ini tidak dapat dibatalkan!`)) {
        window.location.href = url;
    }
}
</script>

<?php require_once '../../includes/footer.php'; ?>