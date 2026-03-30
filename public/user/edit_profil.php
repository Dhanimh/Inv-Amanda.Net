<?php
require_once '../../includes/auth_check.php';
require_once '../../functions/helpers.php';
require_once '../../functions/user.php';

$page_title = 'Edit Profil';
$active_page = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_lengkap = sanitize($_POST['nama_lengkap']);
    $email = sanitize($_POST['email']);
    $password_lama = $_POST['password_lama'];
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];
    $errors = [];
    
    if (empty($nama_lengkap)) {
        $errors[] = "Nama lengkap harus diisi";
    }
    
    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Format email tidak valid";
    }

    if (!empty($password_lama) || !empty($password)) {
        if (empty($password_lama)) {
            $errors[] = "Password lama harus diisi";
        } elseif (!verifyUserPassword($conn, $user['id'], $password_lama)) {
            $errors[] = "Password lama tidak sesuai";
        }
        
        if (empty($password)) {
            $errors[] = "Password baru harus diisi";
        } elseif (strlen($password) < 6) {
            $errors[] = "Password baru minimal 6 karakter";
        } elseif ($password !== $password_confirm) {
            $errors[] = "Konfirmasi password tidak cocok";
        }
    }

    $foto_profil = $user['foto_profil'];
    if (isset($_FILES['foto_profil']) && $_FILES['foto_profil']['error'] === UPLOAD_ERR_OK) {
        $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        $max_size = 2 * 1024 * 1024; // 2MB
        
        $file_type = $_FILES['foto_profil']['type'];
        $file_size = $_FILES['foto_profil']['size'];
        
        if (!in_array($file_type, $allowed_types)) {
            $errors[] = "Format file tidak valid. Hanya JPG, PNG, dan GIF yang diperbolehkan";
        } elseif ($file_size > $max_size) {
            $errors[] = "Ukuran file terlalu besar. Maksimal 2MB";
        } else {
            $upload_dir = '../../uploads/profil/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            
            $file_extension = pathinfo($_FILES['foto_profil']['name'], PATHINFO_EXTENSION);
            $new_filename = 'profil_' . $user['id'] . '_' . time() . '.' . $file_extension;
            $upload_path = $upload_dir . $new_filename;
            
            if (move_uploaded_file($_FILES['foto_profil']['tmp_name'], $upload_path)) {
                if (!empty($user['foto_profil'])) {
                    $old_file = __DIR__ . '/../../' . ltrim($user['foto_profil'], '/');
                    if (file_exists($old_file)) {
                        unlink($old_file);
                    }
                }
                $foto_profil = 'uploads/profil/' . $new_filename;
            } else {
                $errors[] = "Gagal mengupload foto profil";
            }
        }
    }
    
    if (isset($_POST['hapus_foto']) && $_POST['hapus_foto'] === '1') {
        if (!empty($user['foto_profil'])) {
            $old_file = __DIR__ . '/../../' . ltrim($user['foto_profil'], '/');
            if (file_exists($old_file)) {
                unlink($old_file);
            }
        }
        $foto_profil = '';
    }
    
    if (empty($errors)) {
        $data = [
            'nama_lengkap' => $nama_lengkap,
            'email' => $email,
            'password' => $password,
            'foto_profil' => $foto_profil
        ];
        
        if (updateProfile($conn, $user['id'], $data)) {
            $_SESSION['nama_lengkap'] = $nama_lengkap;
            
            logActivity($user['id'], 'Mengubah profil', "");
            setAlert('success', 'Profil berhasil diperbarui!');
            header("Location: edit_profil");
            exit();
        } else {
            $errors[] = "Gagal memperbarui profil: " . mysqli_error($conn);
        }
    }
}

require_once '../../includes/header.php';
?>

<?php require_once '../../includes/sidebar.php'; ?>

<div class="lg:ml-64 pt-16">
    <div class="p-6">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Edit Profil</h1>
            <p class="text-gray-600 mt-1">Kelola informasi akun Anda</p>
        </div>

        <?php showAlert(); ?>

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

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 text-center">
                    <div class="relative inline-block mb-4">
                        <?php if (!empty($user['foto_profil']) && file_exists('../../' . $user['foto_profil'])): ?>
                        <img src="../../<?= $user['foto_profil'] ?>" alt="Foto Profil" 
                             class="w-24 h-24 rounded-full object-cover border-4 border-gray-100">
                        <?php else: ?>
                        <div class="w-24 h-24 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-full flex items-center justify-center text-white text-3xl font-bold">
                            <?= strtoupper(substr($user['nama_lengkap'], 0, 1)) ?>
                        </div>
                        <?php endif; ?>
                        <label for="foto_profil_input" class="absolute bottom-0 right-0 w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center cursor-pointer hover:bg-blue-700 transition shadow-lg">
                            <i data-lucide="camera" class="w-4 h-4 text-white"></i>
                        </label>
                    </div>
                    <?php if (!empty($user['foto_profil'])): ?>
                    <div class="mb-3">
                        <button type="button" onclick="hapusFoto()" class="text-sm text-red-600 hover:text-red-700 font-medium">
                            <span class="flex items-center justify-center gap-1">
                                <i data-lucide="trash-2" class="w-4 h-4"></i> Hapus Foto
                            </span>
                        </button>
                    </div>
                    <?php endif; ?>
                    <h3 class="text-xl font-bold text-gray-900"><?= $user['nama_lengkap'] ?></h3>
                    <p class="text-gray-600 mt-1">@<?= $user['username'] ?></p>
                    <div class="mt-4">
                        <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full <?= $user['role'] == 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' ?>">
                            <?= ucfirst($user['role']) ?>
                        </span>
                    </div>
                    <div class="mt-6 pt-6 border-t border-gray-200 text-left space-y-3">
                        <div class="flex items-center gap-3 text-sm text-gray-600">
                            <i data-lucide="user" class="w-5 h-5"></i>
                            <span><?= $user['username'] ?></span>
                        </div>
                        <div class="flex items-center gap-3 text-sm text-gray-600">
                            <i data-lucide="mail" class="w-5 h-5"></i>
                            <span><?= $user['email'] ?: 'Belum diisi' ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-lg font-bold text-gray-900">Informasi Profil</h2>
                    </div>
                    
                    <form method="POST" action="" enctype="multipart/form-data" class="p-6">
                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Foto Profil
                                </label>
                                <input type="hidden" name="hapus_foto" id="hapus_foto" value="0">
                                <input type="file" name="foto_profil" id="foto_profil_input" accept="image/*"
                                       class="hidden" onchange="previewImage(this)">
                                <div class="flex items-center gap-4">
                                    <div id="preview_container" class="relative">
                                        <?php if (!empty($user['foto_profil']) && file_exists('../../' . $user['foto_profil'])): ?>
                                        <img id="preview_image" src="../../<?= $user['foto_profil'] ?>" alt="Preview" 
                                             class="w-20 h-20 rounded-lg object-cover border-2 border-gray-200">
                                        <?php else: ?>
                                        <div id="preview_placeholder" class="w-20 h-20 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-lg flex items-center justify-center text-white text-2xl font-bold">
                                            <?= strtoupper(substr($user['nama_lengkap'], 0, 1)) ?>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <label for="foto_profil_input" 
                                               class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 cursor-pointer transition">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                            </svg>
                                            Pilih Foto
                                        </label>
                                        <p class="text-xs text-gray-500 mt-2">JPG, PNG, atau GIF. Maksimal 2MB</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Nama Lengkap <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="nama_lengkap" required
                                       value="<?= $user['nama_lengkap'] ?>"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Email
                                </label>
                                <input type="email" name="email"
                                       value="<?= $user['email'] ?>"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="email@example.com">
                            </div>
                            <div class="border-t border-gray-200 pt-6">
                                <h3 class="text-lg font-bold text-gray-900 mb-4">Ubah Password</h3>
                                <p class="text-sm text-gray-600 mb-4">Kosongkan jika tidak ingin mengubah password</p>

                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                            Password Lama
                                        </label>
                                        <input type="password" name="password_lama"
                                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                               placeholder="Masukkan password lama">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                            Password Baru
                                        </label>
                                        <input type="password" name="password"
                                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                               placeholder="Minimal 6 karakter">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                            Konfirmasi Password Baru
                                        </label>
                                        <input type="password" name="password_confirm"
                                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                               placeholder="Ulangi password baru">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 mt-6 pt-6 border-t border-gray-200">
                            <button type="submit"
                                    class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
                                <span class="flex items-center gap-2">
                                    <i data-lucide="check-square" class="w-5 h-5"></i>
                                    Simpan Perubahan
                                </span>
                            </button>
                            <a href="../dashboard"
                               class="px-6 py-2 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition">
                                Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const container = document.getElementById('preview_container');
            const placeholder = document.getElementById('preview_placeholder');
            
            if (placeholder) {
                placeholder.remove();
            }
            
            let img = document.getElementById('preview_image');
            if (!img) {
                img = document.createElement('img');
                img.id = 'preview_image';
                img.className = 'w-20 h-20 rounded-lg object-cover border-2 border-gray-200';
                container.appendChild(img);
            }
            img.src = e.target.result;
            if (document.getElementById('hapus_foto')) {
                document.getElementById('hapus_foto').value = '0';
            }
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function hapusFoto() {
    if (confirm('Yakin ingin menghapus foto profil?')) {
        const previewImg = document.getElementById('preview_image');
        const container = document.getElementById('preview_container');
        const namaLengkap = "<?= $user['nama_lengkap'] ?>";
        const initial = namaLengkap.charAt(0).toUpperCase();

        if (previewImg) {
            previewImg.remove();
        }

        const placeholder = document.getElementById('preview_placeholder');
        if (!placeholder) {
            container.innerHTML = `<div id="preview_placeholder" class="w-20 h-20 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-lg flex items-center justify-center text-white text-2xl font-bold">
                ${initial}
            </div>`;
        }

        document.getElementById('hapus_foto').value = '1';
        document.getElementById('foto_profil_input').value = '';
    }
}
</script>

<?php require_once '../../includes/footer.php'; ?>