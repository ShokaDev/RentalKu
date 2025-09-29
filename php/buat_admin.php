<?php
// create_admin.php
// Letakkan file ini di projectmu, pastikan path include koneksi sesuai
include("../config/koneksi.php"); // sesuaikan path jika perlu

$username = "admin";
$password_plain = "admin"; // kata sandi yang kamu mau

// Buat hash password (PASSWORD_DEFAULT -> bcrypt biasanya)
$hashed = password_hash($password_plain, PASSWORD_DEFAULT);

if (!$conn) {
    die("Koneksi DB gagal");
}

// Cek dulu apakah user 'admin' sudah ada
$q = $conn->prepare("SELECT id FROM users WHERE username = ? LIMIT 1");
$q->bind_param("s", $username);
$q->execute();
$q->store_result();

if ($q->num_rows > 0) {
    // Kalau sudah ada, update password dan role jadi 'admin'
    $q->close();
    $u = $conn->prepare("UPDATE users SET password = ?, role = 'admin' WHERE username = ? LIMIT 1");
    $u->bind_param("ss", $hashed, $username);
    $ok = $u->execute();
    if ($ok) {
        echo "Akun 'admin' berhasil di-update. Hash: <br><code>$hashed</code>";
    } else {
        echo "Gagal update akun admin: " . $conn->error;
    }
    $u->close();
} else {
    // Kalau belum ada, insert baru (sesuaikan struktur kolom jika perlu: id auto, username, password, role)
    $i = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'admin')");
    $i->bind_param("ss", $username, $hashed);
    $ok = $i->execute();
    if ($ok) {
        echo "Akun 'admin' berhasil dibuat. Username: admin, Password: admin<br>Hash: <br><code>$hashed</code>";
    } else {
        echo "Gagal insert akun admin: " . $conn->error;
    }
    $i->close();
}

$conn->close();
?>
