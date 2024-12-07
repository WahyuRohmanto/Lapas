<?php
require 'config.php'; // Koneksi ke database

// Cek apakah ID telah diberikan
if (!isset($_GET['id'])) {
    echo "<script>alert('ID tidak ditemukan!'); window.location.href='data_narapidana.php';</script>";
    exit;
}

$id = $_GET['id'];

// Ambil data narapidana berdasarkan ID
$sql = "SELECT * FROM narapidana WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if (!$data) {
    echo "<script>alert('Data tidak ditemukan!'); window.location.href='data_narapidana.php';</script>";
    exit;
}



// Update data narapidana jika form di-submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $jenis_kejahatan = $_POST['jenis_kejahatan'];
    $blok_kamar = $_POST['blok_kamar'];
    $tanggal_masuk = $_POST['tanggal_masuk'];
    $total_hukuman = $_POST['total_hukuman'];
    $setengah_hukuman = $_POST['setengah_hukuman'];
    $foto = $data['foto'];

    // Cek jika ada foto baru yang diupload
    if (!empty($_FILES['foto']['name'])) {
        $foto = $_FILES['foto']['name'];
        move_uploaded_file($_FILES['foto']['tmp_name'], "uploads/" . $foto);
    }

    $update_sql = "UPDATE narapidana 
                   SET nama = ?, jenis_kelamin = ?, jenis_kejahatan = ?, Blok_Kamar_hunian = ?, tanggal_masuk = ?, total_hukuman = ?, setengah_hukuman = ?, foto = ? 
                   WHERE id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("ssssssssi", $nama, $jenis_kelamin, $jenis_kejahatan, $blok_kamar, $tanggal_masuk, $total_hukuman, $setengah_hukuman, $foto, $id);

    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil diubah!'); window.location.href='data_narapidana.php';</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan saat mengubah data!');</script>";
    }
}   
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/updatenapi.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com">
<link rel="stylesheet" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,100..700;1,100..700&family=Nunito:ital,wght@0,600;1,600&display=swap" rel="stylesheet">
    <title>Ubah Data Narapidana</title>
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
<div class="form-container">
    <h2>Ubah Data Narapidana</h2>
    <form action="" method="POST" enctype="multipart/form-data">
        <div class="left-column">
            <label>Nama:</label>
            <input type="text" name="nama" value="<?= htmlspecialchars($data['nama']) ?>" required>

            <label>Jenis Kelamin:</label>
            <select name="jenis_kelamin" required>
                <option value="Laki-laki" <?= $data['jenis_kelamin'] === 'Laki-laki' ? 'selected' : '' ?>>Laki-laki</option>
                <option value="Perempuan" <?= $data['jenis_kelamin'] === 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
            </select>

            <label>Jenis Kejahatan:</label>
            <input type="text" name="jenis_kejahatan" value="<?= htmlspecialchars($data['jenis_kejahatan']) ?>" required>

            <label>Blok / Kamar Hunian:</label>
            <input type="text" name="blok_kamar" value="<?= htmlspecialchars($data['Blok_Kamar_hunian']) ?>" required>

           
        </div>

        <div class="right-column">
        <label>Tanggal Masuk:</label>
            <input type="date" name="tanggal_masuk" value="<?= htmlspecialchars($data['tanggal_masuk']) ?>" required>

            <label>Total Hukuman (tahun):</label>
            <input type="number" id="total_hukuman" name="total_hukuman" required oninput="hitungSetengahHukuman()">
            
            <label>Setengah Hukuman (tahun):</label>
            <input type="number" id="setengah_hukuman" name="setengah_hukuman" readonly>

            <label for="foto">Foto:</label>
            <input type="file" id="foto" name="foto">
            <img src="uploads/<?= $data['foto']; ?>" alt="Foto narapidana" width="100">

            <button type="submit"> Simpan </button>
            <a href="data_tahanan.php" class="btn-dashboard"> Batal </a>
        </div>
    </form>
</div>


</body>
</html>
