<?php
session_start();
include("../config/koneksi.php");

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    // Ambil data user berdasarkan username
    $query = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username' LIMIT 1");
    $user = mysqli_fetch_assoc($query);

    if ($user && password_verify($password, $user['password'])) {
        // Simpan session umum
        $_SESSION['user_id']  = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role']     = $user['role'];

        // Cek role user
        if ($user['role'] === 'agen') {
            // Cari data agen di tabel pemilik berdasarkan id_user
            $qPemilik = mysqli_query($conn, "SELECT id_pemilik FROM pemilik WHERE id_user = " . $user['id'] . " LIMIT 1");
            $pemilik = mysqli_fetch_assoc($qPemilik);

            if ($pemilik) {
                $_SESSION['id_pemilik'] = $pemilik['id_pemilik'];
                header("Location: ../public/views/dashboard_agen.php");
                exit();
            } else {
                echo "<script>alert('Data agen belum terdaftar di tabel pemilik!'); window.location.href='../public/login.php';</script>";
                exit();
            }
        } elseif ($user['role'] === 'admin') {
            // ADMIN langsung ke dashboard admin
            header("Location: ../public/views/dashboard_admin.php");
            exit();
        } elseif ($user['role'] === 'pelanggan') {
            // Pelanggan diarahkan ke index / dashboard pelanggan
            header("Location: ../public/views/index.php");
            exit();
        } else {
            // Role tidak dikenal
            header("Location: ../public/login.php?error=invalid_role");
            exit();
        }
    } else {
        echo "<script>alert('Login gagal! Username atau password salah.'); window.location.href='../public/login.php';</script>";
        exit();
    }
}
