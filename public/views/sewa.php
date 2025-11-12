<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include(__DIR__ . "/../../config/koneksi.php");
session_start();

// Pastikan user pelanggan sudah login
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'pelanggan') {
    header("Location: /RentalKu/public/login.php?error=unauthorized");
    exit;
}

// Ambil user_id dari session
$user_id = (int)$_SESSION['user_id'];

// Ambil id_pelanggan berdasarkan id_user
$stmt = mysqli_prepare($conn, "SELECT id_pelanggan FROM pelanggan WHERE id_user = ? LIMIT 1");
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$pelanggan_data = mysqli_stmt_get_result($stmt)->fetch_assoc();
mysqli_stmt_close($stmt);

if (!$pelanggan_data) {
    die("Error: Data pelanggan tidak ditemukan. Pastikan user ini terdaftar sebagai pelanggan.");
}

$pelanggan_id = $pelanggan_data['id_pelanggan'];

// Ambil id kendaraan dari query string
if (!isset($_GET['id'])) {
    header("Location: kendaraan.php");
    exit;
}

$id_kendaraan = (int)$_GET['id'];

// Ambil data kendaraan + lokasi
$stmt = mysqli_prepare($conn, "
    SELECT k.*, p.no_wa AS wa_pemilik, p.no_hp, p.nama_pemilik, l.nama_lokasi 
    FROM kendaraan k
    JOIN pemilik p ON k.id_pemilik = p.id_pemilik
    LEFT JOIN lokasi l ON k.id_lokasi = l.id_lokasi
    WHERE k.id_kendaraan = ? AND k.status = 'tersedia'
");
mysqli_stmt_bind_param($stmt, "i", $id_kendaraan);
mysqli_stmt_execute($stmt);
$kendaraan = mysqli_stmt_get_result($stmt)->fetch_assoc();
mysqli_stmt_close($stmt);

if (!$kendaraan) {
    echo "<script>window.location.href='kendaraan.php';</script>";
    exit;
}

// Simpan ke database saat form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tgl_sewa = $_POST['tgl_sewa'];
    $tgl_kembali = $_POST['tgl_kembali'];
    $lama_sewa = (int)$_POST['lama_sewa'];
    $total = (float)$_POST['harga_total'];
    $metode = $_POST['metode_pembayaran'];
    $status = 'menunggu_konfirmasi';

    // Validasi harga total
    if ($total != $lama_sewa * $kendaraan['harga_sewa']) {
        die("Error: Total harga tidak sesuai dengan perhitungan.");
    }

    // Validasi tanggal sewa tidak bentrok
    $stmt = mysqli_prepare($conn, "
        SELECT id_sewa 
        FROM sewa 
        WHERE id_kendaraan = ? 
        AND status IN ('menunggu_konfirmasi', 'aktif') 
        AND (tgl_sewa <= ? AND tgl_kembali >= ?)
    ");
    mysqli_stmt_bind_param($stmt, "iss", $id_kendaraan, $tgl_kembali, $tgl_sewa);
    mysqli_stmt_execute($stmt);
    if (mysqli_stmt_get_result($stmt)->num_rows > 0) {
        die("Error: Kendaraan sudah disewa atau menunggu konfirmasi pada tanggal tersebut.");
    }
    mysqli_stmt_close($stmt);

    // Masukkan data ke tabel sewa
    $sql = "INSERT INTO sewa 
    (id_kendaraan, id_pelanggan, tgl_sewa, tgl_kembali, lama_sewa, harga_total, metode_pembayaran, status, dibuat_pada)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "iissidss", $id_kendaraan, $pelanggan_id, $tgl_sewa, $tgl_kembali, $lama_sewa, $total, $metode, $status);
    if (!mysqli_stmt_execute($stmt)) {
        die("Error saat menyimpan data sewa: " . mysqli_error($conn));
    }
    $id_sewa = mysqli_insert_id($conn); // Ambil ID sewa yang baru dibuat
    mysqli_stmt_close($stmt);

    // Update status kendaraan menjadi 'pending'
    $stmt = mysqli_prepare($conn, "UPDATE kendaraan SET status = 'pending' WHERE id_kendaraan = ?");
    mysqli_stmt_bind_param($stmt, "i", $id_kendaraan);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // Simpan notifikasi untuk pemilik
    $pesan_notifikasi = "Pesanan sewa baru untuk kendaraan {$kendaraan['merk']} {$kendaraan['tipe']} dari pelanggan ID {$pelanggan_id}.";
    $stmt = mysqli_prepare($conn, "INSERT INTO notifikasi (id_pemilik, id_sewa, pesan) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "iis", $kendaraan['id_pemilik'], $id_sewa, $pesan_notifikasi);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // Buat pesan WA otomatis
    $wa = $kendaraan['wa_pemilik'] ?? $kendaraan['no_hp'];
    $wa = preg_replace('/[^0-9]/', '', $wa);
    if (substr($wa, 0, 1) === '0') $wa = '62' . substr($wa, 1);
    if (empty($wa)) {
        die("Error: Nomor WhatsApp pemilik tidak tersedia.");
    }

    $pesan = urlencode(
        "Halo {$kendaraan['nama_pemilik']},\n\n" .
        "Saya ingin menyewa kendaraan berikut:\n\n" .
        "🚗 *{$kendaraan['merk']} {$kendaraan['tipe']}*\n" .
        "📍 Lokasi: " . ($kendaraan['nama_lokasi'] ?? 'Tidak ditentukan') . "\n" .
        "🔢 No Plat: {$kendaraan['no_plat']}\n" .
        "📅 Tanggal Sewa: {$tgl_sewa}\n" .
        "📅 Tanggal Kembali: {$tgl_kembali}\n" .
        "🕐 Lama Sewa: {$lama_sewa} hari\n" .
        "💰 Total Harga: Rp " . number_format($total, 0, ',', '.') . "\n" .
        "💳 Metode Pembayaran: {$metode}\n\n" .
        "Mohon konfirmasi ketersediaan kendaraan ini. Terima kasih!"
    );

    $wa_link = "https://wa.me/{$wa}?text={$pesan}";
    echo "<script>window.location.href='$wa_link';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Sewa - <?= htmlspecialchars($kendaraan['merk'] . ' ' . $kendaraan['tipe']) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet">
</head>

<body class="min-h-screen w-full bg-gradient-to-br from-purple-500 to-indigo-500 flex items-center justify-center p-6">
    <div class="w-full max-w-6xl grid lg:grid-cols-2 gap-6">
        
        <!-- Kartu Kendaraan -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden flex flex-col">
            <div class="relative h-64 bg-gray-50 flex items-center justify-center">
                <?php if (!empty($kendaraan['gambar'])): ?>
                    <img src="../../Uploads/<?= htmlspecialchars($kendaraan['gambar']) ?>" 
                        alt="<?= htmlspecialchars($kendaraan['merk']) ?>" class="max-h-56 object-contain">
                <?php else: ?>
                    <i class="ri-image-line text-6xl text-gray-400"></i>
                <?php endif; ?>
                <div class="absolute top-3 left-3 px-3 py-1 rounded-full text-sm font-bold shadow
                    <?php echo $kendaraan['status'] === 'tersedia' ? 'bg-green-500 text-white' : ($kendaraan['status'] === 'pending' ? 'bg-yellow-500 text-white' : 'bg-red-500 text-white'); ?>">
                    <i class="ri-checkbox-circle-fill mr-1"></i>
                    <?= ucfirst($kendaraan['status']); ?>
                </div>
            </div>
            <div class="p-6 flex-1 flex flex-col">
                <h2 class="text-xl font-bold text-gray-800 mb-3">
                    <?= htmlspecialchars($kendaraan['merk'] . ' ' . $kendaraan['tipe']) ?>
                </h2>
                <p class="text-gray-600 text-sm mb-1"><i class="ri-calendar-line text-purple-600 mr-1"></i>Tahun: <b><?= $kendaraan['tahun'] ?></b></p>
                <p class="text-gray-600 text-sm mb-1"><i class="ri-profile-line text-purple-600 mr-1"></i>No Plat: <b><?= $kendaraan['no_plat'] ?></b></p>
                <p class="text-gray-600 text-sm"><i class="ri-map-pin-line text-purple-600 mr-1"></i>Lokasi: <b><?= htmlspecialchars($kendaraan['nama_lokasi'] ?? 'Tidak ditentukan') ?></b></p>

                <div class="bg-gradient-to-r from-purple-500 to-indigo-500 rounded-xl p-4 text-white mt-auto">
                    <p class="text-xs opacity-80">Harga / Hari</p>
                    <p class="text-2xl font-bold">Rp <?= number_format($kendaraan['harga_sewa'], 0, ',', '.') ?></p>
                </div>
            </div>
        </div>

        <!-- Form Sewa -->
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <h3 class="text-xl font-bold text-gray-800 mb-2">Form Pemesanan</h3>
            <p class="text-sm text-gray-500 mb-6">Isi data dengan benar</p>

            <form method="POST" class="space-y-3 flex-1 flex flex-col" onsubmit="return validateForm()">
                <input type="hidden" name="id_kendaraan" value="<?= $kendaraan['id_kendaraan'] ?>">
                <input type="hidden" name="id_pelanggan" value="<?= $pelanggan_id ?>">
                <input type="hidden" name="harga_per_hari" value="<?= $kendaraan['harga_sewa'] ?>">
                <input type="hidden" id="harga_total_hidden" name="harga_total">

                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Tanggal Mulai</label>
                    <input type="date" name="tgl_sewa" id="tgl_sewa" required
                        class="w-full px-3 py-2 border rounded-lg text-sm focus:border-purple-500 focus:ring-2 focus:ring-purple-200"
                        onchange="hitungTotal()">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Tanggal Kembali</label>
                    <input type="date" name="tgl_kembali" id="tgl_kembali" required
                        class="w-full px-3 py-2 border rounded-lg text-sm focus:border-purple-500 focus:ring-2 focus:ring-purple-200"
                        onchange="hitungTotal()">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Lama Sewa (hari)</label>
                    <input type="number" id="lama_sewa_display" readonly
                        class="w-full px-3 py-2 border rounded-lg bg-gray-100 text-sm font-semibold">
                    <input type="hidden" name="lama_sewa" id="lama_sewa">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Total Harga</label>
                    <input type="text" id="harga_total_display" readonly
                        class="w-full px-3 py-2 border rounded-lg bg-gray-100 text-sm font-bold">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Metode Pembayaran</label>
                    <select name="metode_pembayaran" id="metode_pembayaran" required
                        class="w-full px-3 py-2 border rounded-lg text-sm focus:border-purple-500 focus:ring-2 focus:ring-purple-200 bg-white">
                        <option value="">-- Pilih Metode --</option>
                        <option value="Transfer Bank">Transfer Bank</option>
                        <option value="E-Wallet">E-Wallet</option>
                        <option value="Tunai">Tunai</option>
                    </select>
                </div>

                <button type="submit"
                    class="w-full bg-gradient-to-r from-purple-600 to-indigo-600 text-white py-3 rounded-lg font-bold text-sm hover:shadow-md hover:-translate-y-0.5 transition-all">
                    Konfirmasi & Hubungi via WhatsApp
                </button>
            </form>
        </div>
    </div>

<script>
function hitungTotal() {
    const tglSewa = document.getElementById('tgl_sewa').value;
    const tglKembali = document.getElementById('tgl_kembali').value;
    const hargaPerHari = <?= (float)$kendaraan['harga_sewa'] ?>;

    if (tglSewa && tglKembali) {
        const start = new Date(tglSewa);
        const end = new Date(tglKembali);
        const diffDays = Math.ceil((end - start) / (1000 * 60 * 60 * 24));

        if (diffDays > 0) {
            const total = diffDays * hargaPerHari;
            document.getElementById('lama_sewa_display').value = diffDays;
            document.getElementById('lama_sewa').value = diffDays;
            document.getElementById('harga_total_display').value = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
            document.getElementById('harga_total_hidden').value = total;
        } else {
            document.getElementById('lama_sewa_display').value = '';
            document.getElementById('lama_sewa').value = '';
            document.getElementById('harga_total_display').value = '';
            document.getElementById('harga_total_hidden').value = '';
        }
    }
}

function validateForm() {
    hitungTotal();
    const lama = parseInt(document.getElementById('lama_sewa').value) || 0;
    if (lama <= 0) {
        alert("Tanggal kembali harus setelah tanggal sewa.");
        return false;
    }
    return true;
}

window.onload = function() {
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('tgl_sewa').setAttribute('min', today);
    document.getElementById('tgl_kembali').setAttribute('min', today);
}
</script>
</body>
</html>