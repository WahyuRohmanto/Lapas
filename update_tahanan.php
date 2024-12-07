<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "project_lapas";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Cek apakah ID telah diberikan
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Ambil data tahanan berdasarkan ID
    $sql = "SELECT * FROM tahanan WHERE id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
    } else {
        echo "<script>alert('Data tidak ditemukan!'); window.location.href='data_tahanan.php';</script>";
        exit;
    }
}

// Proses update data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $jenis_kejahatan = $_POST['jenis_kejahatan'];
    $blok_kamar = $_POST['blok_kamar'];
    $tanggal_masuk = $_POST['tanggal_masuk'];
    $total_hukuman = $_POST['total_hukuman'];
    $setengah_hukuman = $_POST['setengah_hukuman'];

    // Proses upload foto baru jika ada
    if (!empty($_FILES['foto']['name'])) {
        $foto = $_FILES['foto']['name'];
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($foto);
        move_uploaded_file($_FILES['foto']['tmp_name'], $target_file);
    } else {
        $foto = $data['foto']; // Gunakan foto lama jika tidak ada foto baru
    }

    $update_sql = "UPDATE tahanan SET 
        nama = '$nama',
        jenis_kelamin = '$jenis_kelamin',
        jenis_kejahatan = '$jenis_kejahatan',
        Blok_Kamar_hunian = '$blok_kamar',
        tanggal_masuk = '$tanggal_masuk',
        total_hukuman = '$total_hukuman',
        setengah_hukuman = '$setengah_hukuman',
        foto = '$foto'
        WHERE id = $id";

    if ($conn->query($update_sql) === TRUE) {
        echo "<script>alert('Data berhasil diupdate!'); window.location.href='data_tahanan.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Data Tahanan</title>
    <link rel="stylesheet" href="css/updatetahanan.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com">
<link rel="stylesheet" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,100..700;1,100..700&family=Nunito:ital,wght@0,600;1,600&display=swap" rel="stylesheet">
    <script>
        // Fungsi untuk menghitung 1/2 Masa Tahanan
        function hitungSetengahHukuman() {
            const totalHukuman = document.getElementById('total_hukuman').value;
            const setengahHukuman = Math.ceil(totalHukuman / 2);
            document.getElementById('setengah_hukuman').value = setengahHukuman;
        }
    </script>
    
</head>
<body>
<div class="data-table">
    <h3>Update Data Tahanan</h3>
    <form method="POST" enctype="multipart/form-data">
        <label for="nama">Nama:</label>
        <input type="text" id="nama" name="nama" value="<?= $data['nama']; ?>" required>

        <label for="jenis_kelamin">Jenis Kelamin:</label>
        <select id="jenis_kelamin" name="jenis_kelamin">
            <option value="Laki-laki" <?= $data['jenis_kelamin'] === 'Laki-laki' ? 'selected' : ''; ?>>Laki-laki</option>
            <option value="Perempuan" <?= $data['jenis_kelamin'] === 'Perempuan' ? 'selected' : ''; ?>>Perempuan</option>
        </select>

        <label for="jenis_kejahatan">Jenis Kejahatan:</label>
        <input type="text" id="jenis_kejahatan" name="jenis_kejahatan" value="<?= $data['jenis_kejahatan']; ?>" required>

        <label for="blok_kamar">Blok/Kamar Hunian:</label>
        <input type="text" id="blok_kamar" name="blok_kamar" value="<?= $data['Blok_Kamar_hunian']; ?>" required>

        <label for="tanggal_masuk">Tanggal Masuk:</label>
        <input type="date" id="tanggal_masuk" name="tanggal_masuk" value="<?= $data['tanggal_masuk']; ?>" required>

        <label>Total Hukuman (tahun):</label>
            <input type="number" id="total_hukuman" name="total_hukuman" required oninput="hitungSetengahHukuman()">

            <label>Setengah Hukuman (tahun):</label>
            <input type="number" id="setengah_hukuman" name="setengah_hukuman" readonly>

        <label for="foto">Foto:</label>
        <input type="file" id="foto" name="foto">
        <img src="uploads/<?= $data['foto']; ?>" alt="Foto Tahanan" width="100">

        <button type="submit" class="btn-ubah">Simpan Perubahan</button>
        <a href="data_tahanan.php" class="btn-dashboard">Batal</a>
    </form>
</div>
</body>
</html>
