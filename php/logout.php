<?php
session_start();

// Hapus semua session
session_unset();
session_destroy();

// Hapus cookie user_session
setcookie("user_session", "", time() - 3600, "/"); // expired di masa lalu

// Redirect ke halaman utama
header("Location: ../public/login.php");
exit();
?>
