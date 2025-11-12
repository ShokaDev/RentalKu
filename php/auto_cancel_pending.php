<?php
include(__DIR__ . "/../../config/koneksi.php");


// Batalkan pesanan pending lebih dari 15 menit
$sql = "
    UPDATE sewa s
    JOIN kendaraan k ON s.id_kendaraan = k.id_kendaraan
    SET 
        s.status = 'ditolak',
        k.status = 'tersedia'
    WHERE 
        s.status = 'menunggu_konfirmasi'
        AND TIMESTAMPDIFF(MINUTE, s.dibuat_pada, NOW()) >= 15
";

mysqli_query($conn, $sql);
?>
