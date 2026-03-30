<?php
require_once '../../includes/auth_check.php';
require_once '../../functions/helpers.php';
require_once '../../functions/user.php';

if (!isAdmin()) {
    setAlert('error', 'Akses ditolak!');
    header("Location: " . BASE_URL . "/public/dashboard");
    exit();
}

$page_title = 'Edit User';
$active_page = 'user';

$id = $_GET['id'] ?? 0;
$user_data = getUserById($conn, $id);

if (!$user_data) {
    setAlert('error', 'User tidak ditemukan!');
    header("Location: index");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username']);
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];
    $nama_lengkap = sanitize($_POST['nama_lengkap']);
    $email = sanitize($_POST['email']);
    $role = sanitize($_POST['role']);
    $errors = [];
    $foto_profil = $user_data['foto_profil'];
    
    if (empty($username)) {
        $errors[] = "Username harus diisi";
    } elseif (isUsernameExists($conn, $username, $id)) {
        $errors[] = "Username sudah digunakan";
    }
    
    if (!empty($password)) {
        if (strlen($password) < 6) {
            $errors[] = "Password minimal 6 karakter";
        } elseif ($password !== $password_confirm) {
            $errors[] = "Konfirmasi password tidak cocok";
        }
    }
    
    if (empty($nama_lengkap)) {
        $errors[] = "Nama lengkap harus diisi";
    }
    
    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Format email tidak valid";
    }

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
            $new_filename = 'profil_' . $id . '_' . time() . '.' . $file_extension;
            $upload_path = $upload_dir . $new_filename;
            
            if (move_uploaded_file($_FILES['foto_profil']['tmp_name'], $upload_path)) {
                if (!empty($user_data['foto_profil'])) {
                    $old_file = __DIR__ . '/../../' . ltrim($user_data['foto_profil'], '/');
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
        if (!empty($user_data['foto_profil'])) {
            $old_file = __DIR__ . '/../../' . ltrim($user_data['foto_profil'], '/');
            if (file_exists($old_file)) {
                unlink($old_file);
            }
        }
        $foto_profil = '';
    }
    
    if (empty($errors)) {
        $data = [
            'username' => $username,
            'password' => $password,
            'nama_lengkap' => $nama_lengkap,
            'email' => $email,
            'role' => $role,
            'foto_profil' => $foto_profil
        ];
        
        if (updateUser($conn, $id, $data)) {
            logActivity($user['id'], 'Mengubah data user', "Username: $username");
            setAlert('success', 'User berhasil diperbarui!');
            header("Location: index");
            exit();
        } else {
            $errors[] = "Gagal memperbarui user: " . mysqli_error($conn);
        }
    }

    $user_data = array_merge($user_data, $_POST);
    if (isset($foto_profil)) {
        $user_data['foto_profil'] = $foto_profil;
    }
}

require_once '../../includes/header.php';
?>

<style>
#preview-container {
    transition: all 0.3s ease;
}
.preview-image {
    object-fit: cover;
}
</style>

<?php require_once '../../includes/sidebar.php'; ?>

<div class="lg:ml-64 pt-16">
    <div class="p-6">
        <div class="mb-6">
            <div class="flex items-center gap-2 text-sm text-gray-600 mb-2">
                <a href="index" class="hover:text-blue-600">Kelola User</a>
                <span>/</span>
                <span class="text-gray-900 font-medium">Edit User</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">Edit User</h1>
            <p class="text-gray-600 mt-1">Perbarui informasi user</p>
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

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <form method="POST" action="" enctype="multipart/form-data" class="p-6">
                <div class="mb-6 pb-6 border-b border-gray-200">
                    <label class="block text-sm font-semibold text-gray-700 mb-4">
                        Foto Profil
                    </label>
                    
                    <div class="flex flex-col md:flex-row items-start gap-6">
                        <div class="flex flex-col items-center gap-3">
                            <div id="preview-container" class="relative">
                                <?php if (!empty($user_data['foto_profil']) && file_exists('../../' . $user_data['foto_profil'])): ?>
                                <img id="photo-preview" 
                                     src="../../<?= $user_data['foto_profil'] ?>" 
                                     alt="Foto Profil"
                                     class="w-32 h-32 rounded-full preview-image border-4 border-gray-200 shadow-lg">
                                <?php else: ?>
                                <div id="photo-preview" class="w-32 h-32 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center border-4 border-gray-200 shadow-lg">
                                    <span class="text-4xl font-bold text-white">
                                        <?= strtoupper(substr($user_data['nama_lengkap'], 0, 1)) ?>
                                    </span>
                                </div>
                                <?php endif; ?>
                            </div>
                            
                            <?php if (!empty($user_data['foto_profil'])): ?>
                            <button type="button" onclick="hapusFoto()" 
                                    class="text-sm text-red-600 hover:text-red-700 font-medium">
                                <span class="flex items-center gap-1">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    Hapus Foto
                                </span>
                            </button>
                            <input type="hidden" name="hapus_foto" id="hapus_foto" value="0">
                            <?php endif; ?>
                        </div>

                        <div class="flex-1">
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-400 transition">
                                <input type="file" name="foto_profil" id="foto_profil" 
                                       accept="image/jpeg,image/jpg,image/png,image/gif"
                                       onchange="previewImage(this)"
                                       class="hidden">
                                <label for="foto_profil" class="cursor-pointer">
                                    <div class="flex flex-col items-center gap-2">
                                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <div>
                                            <span class="text-blue-600 font-medium">Upload foto</span>
                                            <span class="text-gray-600"> atau drag & drop</span>
                                        </div>
                                        <p class="text-xs text-gray-500">JPG, PNG, GIF (Maks. 2MB)</p>
                                    </div>
                                </label>
                            </div>
                            <p class="text-xs text-gray-500 mt-2">
                                <strong>Tips:</strong> Gunakan foto dengan rasio 1:1 (persegi) untuk hasil terbaik
                            </p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Username <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="username" required
                               value="<?= $user_data['username'] ?>"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama_lengkap" required
                               value="<?= $user_data['nama_lengkap'] ?>"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Email
                        </label>
                        <input type="email" name="email"
                               value="<?= $user_data['email'] ?>"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Role <span class="text-red-500">*</span>
                        </label>
                        <select name="role" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="teknisi" <?= $user_data['role'] == 'teknisi' ? 'selected' : '' ?>>Teknisi</option>
                            <option value="admin" <?= $user_data['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Password Baru
                        </label>
                        <input type="password" name="password"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Kosongkan jika tidak diubah">
                        <p class="text-xs text-gray-500 mt-1">Minimal 6 karakter</p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Konfirmasi Password
                        </label>
                        <input type="password" name="password_confirm"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Ulangi password baru">
                    </div>
                </div>

                <div class="flex items-center gap-3 mt-6 pt-6 border-t border-gray-200">
                    <button type="submit"
                            class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
                        <span class="flex items-center gap-2">
                            <i data-lucide="check-square" class="w-5 h-5"></i>
                            Perbarui User
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
</div>

<script>
function previewImage(input) {
    const preview = document.getElementById('photo-preview');
    const container = document.getElementById('preview-container');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.outerHTML = `<img id="photo-preview" src="${e.target.result}" alt="Preview" class="w-32 h-32 rounded-full preview-image border-4 border-gray-200 shadow-lg">`;

            if (document.getElementById('hapus_foto')) {
                document.getElementById('hapus_foto').value = '0';
            }
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}

function hapusFoto() {
    if (confirm('Yakin ingin menghapus foto profil?')) {
        const preview = document.getElementById('photo-preview');
        const namaLengkap = "<?= $user_data['nama_lengkap'] ?>";
        const initial = namaLengkap.charAt(0).toUpperCase();
 
        preview.outerHTML = `<div id="photo-preview" class="w-32 h-32 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center border-4 border-gray-200 shadow-lg">
            <span class="text-4xl font-bold text-white">${initial}</span>
        </div>`;

        document.getElementById('hapus_foto').value = '1';

        document.getElementById('foto_profil').value = '';
    }
}
</script>

<?php require_once '../../includes/footer.php'; ?>