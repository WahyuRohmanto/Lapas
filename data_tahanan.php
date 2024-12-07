<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "project_lapas";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// SweetAlert untuk notifikasi hasil hapus
if (isset($_GET['hapus_id'])) {
    $hapus_id = $_GET['hapus_id'];
    $delete_sql = "DELETE FROM tahanan WHERE id = $hapus_id";
    if ($conn->query($delete_sql) === TRUE) {
        echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        title: 'Sukses!',
                        text: 'Data berhasil dihapus!',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.href = 'data_tahanan.php';
                    });
                });
              </script>";
    } else {
        echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        title: 'Gagal!',
                        text: 'Terjadi kesalahan saat menghapus data.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                });
              </script>";
    }
}

$sql = "SELECT *, 
        CASE 
            WHEN (YEAR(CURDATE()) - YEAR(tanggal_masuk)) >= total_hukuman THEN 'Bebas' 
            ELSE 'Tahanan' 
        END AS status 
        FROM tahanan";
$result = $conn->query($sql);
?>

<link rel="stylesheet" href="https://fonts.googleapis.com">
<link rel="stylesheet" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,100..700;1,100..700&family=Nunito:ital,wght@0,600;1,600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="css/datatahanan1.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
<!-- SweetAlert Library -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="data-table">   
    <h3>Data Tahanan</h3>

    <!-- Kontainer untuk tombol -->
    <div class="btn-container">
        <button onclick="printTable()" class="btn-print">Print Data</button>
        <a href="tambah_tahanan.php" class="btn-tambah">Tambah Tahanan</a>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Foto</th>
                <th>Nama</th>
                <th>Jenis Kelamin</th>
                <th>Jenis Kejahatan</th>
                <th>Blok / Kamar Hunian</th>
                <th>Tanggal Masuk</th>
                <th>1/2 Masa Tahanan</th>
                <th>Total Hukuman</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php $no = 1; ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><img src="uploads/<?= $row['foto']; ?>" width="50" height="50"></td>
                        <td><?= $row['nama']; ?></td>
                        <td><?= $row['jenis_kelamin']; ?></td>
                        <td><?= $row['jenis_kejahatan']; ?></td>
                        <td><?= $row['Blok_Kamar_hunian']; ?></td>
                        <td><?= $row['tanggal_masuk']; ?></td>
                        <td><?= $row['setengah_hukuman']; ?> tahun</td>
                        <td><?= $row['total_hukuman']; ?> tahun</td>
                        <td><?= $row['status']; ?></td>
                        <td>
    <div class="action-buttons">
        <!-- Tombol Hapus -->
        <button class="btn-hapus" onclick="konfirmasiHapus(<?= $row['id']; ?>)">
            <i class="fas fa-trash-alt"></i> Hapus
        </button>
        <!-- Tombol Ubah -->
        <a href="update_tahanan.php?id=<?= $row['id']; ?>" class="btn-ubah">
            <i class="fa-solid fa-pen" style="color: #ffffff;"></i> Ubah
        </a>
    </div>
</td>

                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="10">Tidak ada data tahanan</td>
                </tr>
            <?php endif; ?>
            <!-- Baris untuk tombol kembali -->
            <tr>
                <td colspan="10" style="text-align: right;">
                    <a href="dashboard.php" class="btn-dashboard">Kembali ke Dashboard</a>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<script>
function printTable() {
    window.print();
}

function konfirmasiHapus(id) {
    Swal.fire({
        title: 'Yakin ingin menghapus?',
        text: 'Data yang sudah dihapus tidak dapat dikembalikan!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "data_tahanan.php?hapus_id=" + id;
        }
    });
}
</script>

<?php
$conn->close();
?>
