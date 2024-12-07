<?php
// Include koneksi ke database
include 'koneksi.php';

try {
    // Query untuk mengambil data pendaftar
    $stmt = $pdo->query("SELECT * FROM pendaftar ORDER BY dibuat DESC");
    $pendaftarList = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Jika query gagal, berikan array kosong untuk menghindari error
    $pendaftarList = [];
    // Opsional: Tampilkan pesan error (untuk debugging)
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Kunjungan</title>
    <link rel="stylesheet" href="kunjungan.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,100..700;1,100..700&family=Nunito:ital,wght@0,600;1,600&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2>Daftar Kunjungan</h2>
        
        <div class="back-button">
            <a href="dashboard.php" class="btn-back">Kembali ke Dashboard</a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Jenis Kelamin</th>
                    <th>KTP</th>
                    <th>Alamat</th>
                    <th>No Telepon</th>
                    <th>Jumlah Pengikut</th>
                    <th>Warga Binaan</th>
                    <th>Tanggal Daftar</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>

            <?php if (!empty($pendaftarList)): ?>
                <?php foreach ($pendaftarList as $index => $pendaftar): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= htmlspecialchars($pendaftar['nama']) ?></td>
                        <td><?= htmlspecialchars($pendaftar['jenis_kelamin']) ?></td>
                        <td><?= htmlspecialchars($pendaftar['ktp']) ?></td>
                        <td><?= htmlspecialchars($pendaftar['alamat']) ?></td>
                        <td><?= htmlspecialchars($pendaftar['tlp']) ?></td>
                        <td><?= htmlspecialchars($pendaftar['jumlah']) ?></td>
                        <td><?= htmlspecialchars($pendaftar['warga_binaan']) ?></td>
                        <td><?= date("Y-m-d", strtotime($pendaftar['tanggal_daftar'])) ?></td>
                        <td>
                            <a href="detail_pendaftar.php?id=<?= $pendaftar['id'] ?>" class="detail-button">Detail</a>
                            <a href="hapus_kunjungan.php?id=<?= $pendaftar['id'] ?>" class="hapus-button" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="10" style="text-align:center;">Tidak ada data kunjungan yang ditemukan.</td>
                </tr>
            <?php endif; ?>

            </tbody>
        </table>
    </div>
</body>
</html>
