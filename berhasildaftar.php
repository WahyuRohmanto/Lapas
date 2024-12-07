<?php
// Koneksi ke database
include 'koneksi.php';

// Ambil ID pendaftar dari URL
$pendaftarId = $_GET['id'];

// Query untuk mengambil data pendaftar berdasarkan ID
$stmt = $pdo->prepare("SELECT * FROM pendaftar WHERE id = ?");
$stmt->execute([$pendaftarId]);
$pendaftar = $stmt->fetch(PDO::FETCH_ASSOC);

// Menampilkan informasi warga binaan yang dikunjungi
if ($pendaftar) {
    // Hitung total pengikut
    $totalPengikut = $pendaftar['pengikut_laki'] + $pendaftar['pengikut_perempuan'] + $pendaftar['pengikut_anak'];
    ?>
   <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Surat Izin Kunjungan</title>
    <link rel="stylesheet" href="css/daftarberhasil.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,100..700;1,100..700&family=Nunito:ital,wght@0,600;1,600&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="header-content">
                <img src="lapas.png" alt="Pengayoman Logo" class="logo">
                <div class="header-text">
                    <h1>KEMENTERIAN HUKUM DAN HAM RI</h1>
                    <h2>KANTOR WILAYAH PAPUA</h2>
                    <h2>LAPAS KELAS II B MERAUKE</h2>
                    <p>JL. ERMASU NO. 18 KEL. MARO KAB. MERAUKE Telp (0971)321178</p>
                    <p>Fax (0971)321178</p>
                </div>
            </div>
        </div>
        <div class="title-section">
            <div class="title-queue">
                <h3>Surat Izin Kunjungan</h3>
                <p class="queue-number">No Antrian: <?= htmlspecialchars($pendaftar['dibuat']) ?></p>
            </div>
            <hr>
        </div>

        <div class="content-section">
            <table class="details-table">
                <tr>
                    <td class="label">Nama Pengunjung</td>
                    <td class="separator">:</td>
                    <td class="content"><?= htmlspecialchars($pendaftar['nama']) ?></td>
                </tr>
                <tr>
                    <td class="label">Jenis Kelamin</td>
                    <td class="separator">:</td>
                    <td class="content"><?= htmlspecialchars($pendaftar['jenis_kelamin']) ?></td>
                </tr>
                <tr>
                    <td class="label">KTP</td>
                    <td class="separator">:</td>
                    <td class="content"><?= isset($pendaftar['ktp']) ? htmlspecialchars($pendaftar['ktp']) : 'Data KTP tidak tersedia'; ?></td>
                </tr>
                <tr>
                    <td class="label">Alamat</td>
                    <td class="separator">:</td>
                    <td class="content"><?= htmlspecialchars($pendaftar['alamat']) ?></td>
                </tr>
                <tr>
                    <td class="label">No. Telepon</td>
                    <td class="separator">:</td>
                    <td class="content"><?= htmlspecialchars($pendaftar['tlp']) ?></td>
                </tr>
                <tr>
                    <td class="label">Pengikut</td>
                    <td class="separator"></td>
                    <td class="content"></td>
                </tr>
                <tr>
                    <td class="label">Laki-laki</td>
                    <td class="separator">:</td>
                    <td class="content"><?= htmlspecialchars($pendaftar['pengikut_laki']) ?> orang</td>
                </tr>
                <tr>
                    <td class="label">Perempuan</td>
                    <td class="separator">:</td>
                    <td class="content"><?= htmlspecialchars($pendaftar['pengikut_perempuan']) ?> orang</td>
                </tr>
                <tr>
                    <td class="label">Anak-anak</td>
                    <td class="separator">:</td>
                    <td class="content"><?= htmlspecialchars($pendaftar['pengikut_anak']) ?> orang</td>
                </tr>
                <tr>
                    <td class="label">Jumlah</td>
                    <td class="separator">:</td>
                    <td class="content"><?= htmlspecialchars($totalPengikut) ?> orang</td>
                </tr>
            </table>

            <h4>Barang Titipan</h4>
            <table class="item-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Jenis Barang</th>
                        <th>Jumlah</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                        <?php
                        // Ambil data barang dari tabel barang berdasarkan id_pendaftar
                        try {
                            $stmtBarang = $pdo->prepare("SELECT * FROM barang WHERE id_pendaftar = :id_pendaftar");
                            $stmtBarang->execute(['id_pendaftar' => $pendaftarId]);
                            $barangList = $stmtBarang->fetchAll(PDO::FETCH_ASSOC);

                            if ($barangList) {
                                foreach ($barangList as $index => $barang) {
                                    echo "<tr>
                                            <td>" . ($index + 1) . "</td>
                                            <td>" . htmlspecialchars($barang['jenis_barang']) . "</td>
                                            <td>" . htmlspecialchars($barang['jumlah_barang']) . "</td>
                                            <td>" . htmlspecialchars($barang['keterangan_barang']) . "</td>
                                          </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='4'>Tidak ada barang titipan.</td></tr>";
                            }
                        } catch (PDOException $e) {
                            echo "<tr><td colspan='4'>Data barang tidak ditemukan atau tabel barang belum ada.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>

                <h4>Warga Binaan yang Dikunjungi</h4>
<div class="item-napi">
    <?php
    // Query untuk mencari warga binaan di tabel narapidana dan tahanan
    $stmtWargaBinaan = $pdo->prepare("
        SELECT nama, jenis_kejahatan, Blok_Kamar_hunian, foto, status 
        FROM narapidana 
        WHERE nama = :nama 
        UNION ALL 
        SELECT nama, jenis_kejahatan, Blok_Kamar_hunian, foto, status
        FROM tahanan 
        WHERE nama = :nama
    ");
    $stmtWargaBinaan->execute(['nama' => $pendaftar['warga_binaan']]);
    $wargaBinaan = $stmtWargaBinaan->fetch(PDO::FETCH_ASSOC);

    if ($wargaBinaan) {
        // Tampilkan foto jika tersedia dan file ada di folder uploads
        $fotoPath = "uploads/" . htmlspecialchars($wargaBinaan['foto']);
        ?>
        <div class="detail-container">
            <!-- Foto -->
            <div>
                <?php if (!empty($wargaBinaan['foto']) && file_exists($fotoPath)) { ?>
                    <img src="<?php echo $fotoPath; ?>" alt="Foto Warga Binaan" class="warga-foto">
                <?php } else { ?>
                    <p><strong>Foto:</strong> Foto tidak tersedia atau jalur tidak valid.</p>
                <?php } ?>
            </div>

            <!-- Data -->
    <div class="detail-data">
    <p><strong>Nama :</strong> <span><?php echo htmlspecialchars($wargaBinaan['nama'] ?? 'Data tidak tersedia'); ?></span></p>
    <p><strong>Perkara :</strong> <span><?php echo htmlspecialchars($wargaBinaan['jenis_kejahatan'] ?? 'Data tidak tersedia'); ?></span></p>
    <p><strong>Blok / Kamar Hunian :</strong> <span><?php echo htmlspecialchars($wargaBinaan['Blok_Kamar_hunian'] ?? 'Data tidak tersedia'); ?></span></p>
    <p><strong>Status :</strong> <span><?php echo htmlspecialchars($wargaBinaan['status'] ?? 'Data tidak tersedia'); ?></span></p>
</div>

        </div>
        <?php
    } else {
        echo "<p>Data warga binaan tidak ditemukan.</p>";
    }
    ?>
</div>
    </table>
            </div>

            <!-- Tombol cetak -->
            <button class="print-btn" onclick="window.print();">Cetak Surat Izin</button>
        </div>
    </body>
    </html>
    <?php
} else {
    echo "<p>Data pendaftar tidak ditemukan.</p>";
}
?>
