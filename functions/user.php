<?php
function getAllUsers($conn) {
    $query = "SELECT * FROM users ORDER BY created_at ASC";
    return mysqli_query($conn, $query);
}

function getUserById($conn, $id) {
    $id = mysqli_real_escape_string($conn, $id);
    $query = "SELECT * FROM users WHERE id = '$id'";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_assoc($result);
}

function isUsernameExists($conn, $username, $exclude_id = null) {
    $username = mysqli_real_escape_string($conn, $username);
    $query = "SELECT id FROM users WHERE username = '$username'";
    
    if ($exclude_id) {
        $exclude_id = mysqli_real_escape_string($conn, $exclude_id);
        $query .= " AND id != '$exclude_id'";
    }
    
    $result = mysqli_query($conn, $query);
    return mysqli_num_rows($result) > 0;
}

function isEmailExists($conn, $email, $exclude_id = null) {
    $email = mysqli_real_escape_string($conn, $email);
    $query = "SELECT id FROM users WHERE email = '$email'";
    
    if ($exclude_id) {
        $exclude_id = mysqli_real_escape_string($conn, $exclude_id);
        $query .= " AND id != '$exclude_id'";
    }
    
    $result = mysqli_query($conn, $query);
    return mysqli_num_rows($result) > 0;
}

function addUser($conn, $data) {
    $username = mysqli_real_escape_string($conn, $data['username']);
    $password = password_hash($data['password'], PASSWORD_DEFAULT);
    $nama_lengkap = mysqli_real_escape_string($conn, $data['nama_lengkap']);
    $email = mysqli_real_escape_string($conn, $data['email']);
    $role = mysqli_real_escape_string($conn, $data['role']);
    
    $query = "INSERT INTO users (username, password, nama_lengkap, email, role) 
              VALUES ('$username', '$password', '$nama_lengkap', '$email', '$role')";
    
    return mysqli_query($conn, $query);
}

function updateUser($conn, $id, $data) {
    $id = mysqli_real_escape_string($conn, $id);
    $username = mysqli_real_escape_string($conn, $data['username']);
    $nama_lengkap = mysqli_real_escape_string($conn, $data['nama_lengkap']);
    $email = mysqli_real_escape_string($conn, $data['email']);
    $role = mysqli_real_escape_string($conn, $data['role']);
    
    $query = "UPDATE users SET 
              username = '$username',
              nama_lengkap = '$nama_lengkap',
              email = '$email',
              role = '$role'";

    if (!empty($data['password'])) {
        $password = password_hash($data['password'], PASSWORD_DEFAULT);
        $query .= ", password = '$password'";
    }

    if (isset($data['foto_profil'])) {
        $foto_profil = mysqli_real_escape_string($conn, $data['foto_profil']);
        $query .= ", foto_profil = '$foto_profil'";
    }
    
    $query .= " WHERE id = '$id'";
    
    return mysqli_query($conn, $query);
}

function deleteUser($conn, $id) {
    $id = mysqli_real_escape_string($conn, $id);
    
    $user = getUserById($conn, $id);
    if ($user && !empty($user['foto_profil'])) {
        $file_path = __DIR__ . '/../' . $user['foto_profil'];
        if (file_exists($file_path)) {
            unlink($file_path);
        }
    }
    
    $query = "DELETE FROM users WHERE id = '$id'";
    return mysqli_query($conn, $query);
}

function updateProfile($conn, $id, $data) {
    $id = mysqli_real_escape_string($conn, $id);
    $nama_lengkap = mysqli_real_escape_string($conn, $data['nama_lengkap']);
    $email = mysqli_real_escape_string($conn, $data['email']);
    
    $query = "UPDATE users SET 
              nama_lengkap = '$nama_lengkap',
              email = '$email'";

    if (!empty($data['password'])) {
        $password = password_hash($data['password'], PASSWORD_DEFAULT);
        $query .= ", password = '$password'";
    }
    
    if (isset($data['foto_profil'])) {
        $foto_profil = mysqli_real_escape_string($conn, $data['foto_profil']);
        $query .= ", foto_profil = '$foto_profil'";
    }
    
    $query .= " WHERE id = '$id'";
    
    return mysqli_query($conn, $query);
}

function verifyUserPassword($conn, $id, $password) {
    $user = getUserById($conn, $id);
    return password_verify($password, $user['password']);
}
?>