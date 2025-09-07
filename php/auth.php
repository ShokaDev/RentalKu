<?php
session_start();
include("../config/koneksi.php");

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Ini untuk deteksi bagian Login nya berhasil / ngga
if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $query = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");
    $user = mysqli_fetch_assoc($query);

    if ($user && $user['password'] === md5($password)) {
    $_SESSION['user'] = $user;
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['role'] = $user['role'];

    header("Location: ../views/dashboard.php");
    } else {
        echo "<script>alert('Login gagal! Username atau password salah.'); window.location.href='../public/login.php';</script>";
    }

    exit();
}

?>
