<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data Tahanan</title>
    <link rel="stylesheet" href="css/tambah_tahanan.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com">
<link rel="stylesheet" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,100..700;1,100..700&family=Nunito:ital,wght@0,600;1,600&display=swap" rel="stylesheet"> <!-- Ganti nama file CSS jika perlu -->
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
    <h2>Tambah Data Tahanan</h2>
    <form action="simpan_tahanan.php" method="POST" enctype="multipart/form-data">
        <div class="form-grid">
            <!-- Kolom Kiri -->
            <div>
                <label for="nama">Nama:</label>
                <input type="text" id="nama" name="nama" required>
            </div>
            <div>
                <label for="jenis_kelamin">Jenis Kelamin:</label>
                <select id="jenis_kelamin" name="jenis_kelamin" required>
                    <option value="Laki-laki">Laki-laki</option>
                    <option value="Perempuan">Perempuan</option>
                </select>
            </div>
            <div>
                <label for="jenis_kejahatan">Jenis Kejahatan:</label>
                <input type="text" id="jenis_kejahatan" name="jenis_kejahatan" required>
            </div>
            <div>
                <label for="Blok_Kamar_hunian">Blok / Kamar Hunian:</label>
                <input type="text" id="Blok_Kamar_hunian" name="Blok_Kamar_hunian" required>
            </div>

            <!-- Kolom Kanan -->
            <div>
                <label for="tanggal_masuk">Tanggal Masuk:</label>
                <input type="date" id="tanggal_masuk" name="tanggal_masuk" required>
            </div>
            <div>
                <label for="total_hukuman">Total Hukuman (dalam tahun):</label>
                <input type="number" id="total_hukuman" name="total_hukuman" required oninput="hitungSetengahHukuman()">
            </div>
            <div>
                <label for="setengah_hukuman">1/2 Masa Tahanan (dalam tahun):</label>
                <input type="number" id="setengah_hukuman" name="setengah_hukuman" readonly>
            </div>
            <div>
                <label for="foto">Foto Tahanan:</label>
                <input type="file" id="foto" name="foto" accept="image/*" required>
            </div>
        </div>
        <button type="submit">Simpan</button>
    </form>
</div>

</body>
</html>
