<?php
include("../../config/koneksi.php");

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = "DELETE FROM pemilik WHERE id_pemilik = $id";
    if (mysqli_query($conn, $query)) {
        header("Location: pemilik.php?success=1");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    echo "ID tidak ditemukan!";
}
?>
