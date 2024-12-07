<?php
// Include koneksi ke database
include 'koneksi.php';

// Nomor antrian awal
$queueNumber = 1;

// Hitung total pendaftar untuk nomor antrian
$stmt = $pdo->query("SELECT COUNT(*) FROM pendaftar");
$queueNumber += $stmt->fetchColumn();

// Proses ketika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validasi jika KTP kosong
    if (empty(trim($_POST['ktp']))) {
        echo "KTP tidak boleh kosong!";
        exit;
    }

    // Menghitung total pengikut
    $totalPengikut = (int)$_POST['pengikut_laki'] + (int)$_POST['pengikut_perempuan'] + (int)$_POST['pengikut_anak'];

    // Kumpulkan data dari form
    $userData = [
        'nama' => $_POST['nama'],
        'jenis_kelamin' => $_POST['jenis_kelamin'],
        'ktp' => $_POST['ktp'],
        'alamat' => $_POST['alamat'],
        'tlp' => $_POST['tlp'],
        'pengikut_laki' => $_POST['pengikut_laki'],
        'pengikut_perempuan' => $_POST['pengikut_perempuan'],
        'pengikut_anak' => $_POST['pengikut_anak'],
        'jumlah' => $totalPengikut,
        'dibuat' => $queueNumber,
        'warga_binaan' => $_POST['warga_binaan'] // Warga binaan yang ingin dikunjungi
    ];

    // Simpan data pendaftar ke database
   // Modifikasi query untuk menyimpan data pendaftar ke database
$sql = "INSERT INTO pendaftar (nama, jenis_kelamin, ktp, alamat, tlp, pengikut_laki, pengikut_perempuan, pengikut_anak, jumlah, dibuat, warga_binaan, tanggal_daftar) 
VALUES (:nama, :jenis_kelamin, :ktp, :alamat, :tlp, :pengikut_laki, :pengikut_perempuan, :pengikut_anak, :jumlah, :dibuat, :warga_binaan, NOW())";
$stmt = $pdo->prepare($sql);
$stmt->execute($userData);


    // Dapatkan ID pendaftar baru
    $pendaftarId = $pdo->lastInsertId();

    // Jika ada barang titipan, simpan ke database
    if (!empty($_POST['jenis_barang'])) {
        foreach ($_POST['jenis_barang'] as $index => $jenisBarang) {
            $barangData = [
                'id_pendaftar' => $pendaftarId,
                'jenis_barang' => $jenisBarang,
                'jumlah_barang' => $_POST['jumlah_barang'][$index],
                'keterangan_barang' => $_POST['keterangan_barang'][$index]
            ];

            $sqlBarang = "INSERT INTO barang (id_pendaftar, jenis_barang, jumlah_barang, keterangan_barang) 
                          VALUES (:id_pendaftar, :jenis_barang, :jumlah_barang, :keterangan_barang)";
            $stmtBarang = $pdo->prepare($sqlBarang);
            $stmtBarang->execute($barangData);
        }
    }

    // Redirect ke halaman berhasil
    header('Location: berhasildaftar.php?id=' . $pendaftarId);
    exit;
}

// Endpoint pencarian warga binaan
if (isset($_GET['search'])) {
    $searchTerm = $_GET['search'];
    $stmt = $pdo->prepare("SELECT * FROM warga_binaan WHERE nama LIKE ?");
    $stmt->execute(["%$searchTerm%"]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($results);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Izin Kunjungan</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@100..700&family=Nunito:wght@600&display=swap">
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
                <p class="queue-number">No Antrian: <?= $queueNumber ?></p>
            </div>
            <hr>
        </div>

        <!-- Form Section -->
        <form action="" method="POST" oninput="totalPengikut.value=parseInt(pengikut_laki.value)+parseInt(pengikut_perempuan.value)+parseInt(pengikut_anak.value)">
            <div class="form-row">
                <label for="nama">Nama Pengunjung:</label>
                <input type="text" id="nama" name="nama" class="short-input" required>
            </div>

            <div class="form-row">
                <label for="jenis_kelamin">Jenis Kelamin:</label>
                <select id="jenis_kelamin" name="jenis_kelamin" class="short-input" required>
                    <option value=""></option>
                    <option value="Perempuan">Perempuan</option>
                    <option value="Laki-laki">Laki-laki</option>
                </select>
            </div>

            <div class="form-row">
                <label for="ktp">KTP:</label>
                <input type="text" id="ktp" name="ktp" class="short-input" required>
            </div>

            <div class="form-row">
                <label for="alamat">Alamat:</label>
                <input type="text" id="alamat" name="alamat" class="short-input" required>
            </div>

            <div class="form-row">
                <label for="tlp">No. Telepon:</label>
                <input type="tel" id="tlp" name="tlp" class="short-input">
            </div>

            <h4>Pengikut</h4>
            <div class="form-row">
                <label for="pengikut_laki">Laki-laki:</label>
                <input type="number" id="pengikut_laki" name="pengikut_laki" class="short-input" value="0" min="0">
            </div>

            <div class="form-row">
                <label for="pengikut_perempuan">Perempuan:</label>
                <input type="number" id="pengikut_perempuan" name="pengikut_perempuan" class="short-input" value="0" min="0">
            </div>

            <div class="form-row">
                <label for="pengikut_anak">Anak-anak:</label>
                <input type="number" id="pengikut_anak" name="pengikut_anak" class="short-input" value="0" min="0">
            </div>

            <div class="form-row">
                <label>Total Pengikut:</label>
                <input type="number" name="totalPengikut" id="totalPengikut" value="0" readonly>
            </div>

            <h4>Barang yang Dititipkan</h4>
            <table id="barangTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Jenis Barang</th>
                        <th>Jumlah</th>
                        <th>Keterangan</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td><input type="text" name="jenis_barang[]" placeholder="Jenis Barang" required></td>
                        <td><input type="number" name="jumlah_barang[]" placeholder="Jumlah" required></td>
                        <td><input type="text" name="keterangan_barang[]" placeholder="Keterangan" required></td>
                        <td><button type="button" onclick="removeRow(this)">Hapus</button></td>
                    </tr>
                </tbody>
            </table>
            <button type="button" onclick="addRow()">Tambah Kolom</button>

            <!-- Input untuk pencarian warga binaan -->
            <div class="form-row">
                <label for="warga_binaan">Cari Warga Binaan:</label>
                <input type="text" id="warga_binaan" name="warga_binaan" class="short-input" oninput="searchWargaBinaan()" required>
                <div id="searchResults"></div>
            </div>

            <!-- Tombol Submit -->
            <div class="form-row">
                <button type="submit">Kirim</button>
            </div>
        </form>
    </div>

    <script>
        function addRow() {
            const table = document.getElementById("barangTable").getElementsByTagName('tbody')[0];
            const newRow = table.rows[0].cloneNode(true);
            table.appendChild(newRow);
            const rowCount = table.rows.length;
            newRow.cells[0].innerText = rowCount;
        }

        function removeRow(button) {
            const row = button.closest("tr");
            row.remove();
            updateRowNumbers();
        }

        function updateRowNumbers() {
            const table = document.getElementById("barangTable").getElementsByTagName('tbody')[0];
            for (let i = 0; i < table.rows.length; i++) {
                table.rows[i].cells[0].innerText = i + 1;
            }
        }

        async function searchWargaBinaan() {
            const searchTerm = document.getElementById("warga_binaan").value;
            const resultsDiv = document.getElementById("searchResults");

            if (searchTerm.length === 0) {
                resultsDiv.innerHTML = '';
                return;
            }

            const response = await fetch(`?search=${searchTerm}`);
            const results = await response.json();

            resultsDiv.innerHTML = results.map(result => `
                <p onclick="selectResult('${result.nama}')">${result.nama}</p>
            `).join('');
        }

        function selectResult(name) {
            document.getElementById("warga_binaan").value = name;
            document.getElementById("searchResults").innerHTML = '';
        }
    </script>
</body>
</html>
