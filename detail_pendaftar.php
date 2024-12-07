<?php
// Include koneksi ke database
include 'koneksi.php';

// Dapatkan ID pendaftar dari URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Ambil data pendaftar berdasarkan ID
$stmt = $pdo->prepare("SELECT * FROM pendaftar WHERE id = ?");
$stmt->execute([$id]);
$pendaftar = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$pendaftar) {
    echo "Data pendaftar tidak ditemukan.";
    exit;
    
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pendaftar</title>
    <link rel="stylesheet" href="detailpendaftar.css">
    <script src="https://kit.fontawesome.com/e8437d67c2.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com">
<link rel="stylesheet" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,100..700;1,100..700&family=Nunito:ital,wght@0,600;1,600&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2>Detail Pendaftar - <?= htmlspecialchars($pendaftar['nama']) ?></h2>
        
        <table class="detail-table">
            <tr><th>Nama</th><td><?= htmlspecialchars($pendaftar['nama']) ?></td></tr>
            <tr><th>Jenis Kelamin</th><td><?= htmlspecialchars($pendaftar['jenis_kelamin']) ?></td></tr>
            <tr><th>KTP</th><td><?= htmlspecialchars($pendaftar['ktp']) ?></td></tr>
            <tr><th>Alamat</th><td><?= htmlspecialchars($pendaftar['alamat']) ?></td></tr>
            <tr><th>No Telepon</th><td><?= htmlspecialchars($pendaftar['tlp']) ?></td></tr>
            <tr><th>Pengikut Laki-laki</th><td><?= htmlspecialchars($pendaftar['pengikut_laki']) ?></td></tr>
            <tr><th>Pengikut Perempuan</th><td><?= htmlspecialchars($pendaftar['pengikut_perempuan']) ?></td></tr>
            <tr><th>Pengikut Anak-anak</th><td><?= htmlspecialchars($pendaftar['pengikut_anak']) ?></td></tr>
            <tr><th>Jumlah Pengikut</th><td><?= htmlspecialchars($pendaftar['jumlah']) ?></td></tr>
            <tr><th>Warga Binaan Dikunjungi</th><td><?= htmlspecialchars($pendaftar['warga_binaan']) ?></td></tr>
            <tr><th>Tanggal Daftar</th><td><?= date("Y-m-d", strtotime($pendaftar['tanggal_daftar'])) ?></td></tr>
        </table>

        <a href="kunjungan.php" class="back-button">Kembali</a>
    </div>
</body>
</html>
