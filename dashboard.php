<?php

include 'koneksi.php';
// Koneksi ke database menggunakan PDO
$host = "localhost";
$dbname = "project_lapas";
$username = "root";
$password = "";


session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Arahkan ke login jika belum login
    exit;
}

// Query untuk menghitung total pendaftar hari ini
$today = date("Y-m-d");
$stmt = $pdo->prepare("SELECT COUNT(*) AS total_pendaftar FROM pendaftar WHERE DATE(tanggal_daftar) = ?");
$stmt->execute([$today]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

// Debug: Tampilkan hasil query
if ($row) {
    echo "Jumlah kunjungan hari ini: " . $row['total_pendaftar'];
} else {
    echo "Tidak ada data untuk kunjungan hari ini.";
}

// Query untuk menghitung total pendaftar hari ini
$today = date("Y-m-d");
$stmt = $pdo->prepare("SELECT COUNT(*) AS total_pendaftar FROM pendaftar WHERE DATE(tanggal_daftar) = ?");
$stmt->execute([$today]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$total_pendaftar = $row ? $row['total_pendaftar'] : 0;



// Query untuk menghitung total narapidana
$sql_narapidana = "SELECT COUNT(*) AS total_narapidana FROM narapidana";
$result_narapidana = $pdo->query($sql_narapidana);
$total_narapidana = 0;
if ($result_narapidana->rowCount() > 0) {
    $row = $result_narapidana->fetch(PDO::FETCH_ASSOC);
    $total_narapidana = $row['total_narapidana'];
}

// Query untuk menghitung total tahanan
$sql_tahanan = "SELECT COUNT(*) AS total_tahanan FROM tahanan";
$result_tahanan = $pdo->query($sql_tahanan);
$total_tahanan = 0;
if ($result_tahanan->rowCount() > 0) {
    $row = $result_tahanan->fetch(PDO::FETCH_ASSOC);
    $total_tahanan = $row['total_tahanan'];
}



?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Kantor Lapas</title>
    <link rel="stylesheet" href="dashboard.css">
    <script src="https://kit.fontawesome.com/e8437d67c2.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com">
<link rel="stylesheet" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,100..700;1,100..700&family=Nunito:ital,wght@0,600;1,600&display=swap" rel="stylesheet">
</head>
<body>
    <div class="sidebar">
        <div class="logo">
            <img src="lapas.png" alt="Logo Lapas">
            <h2>Lapas Kelas IIB Merauke</h2>
        </div>
        <ul>
            <li><a href="dashboard.php"><i class="fa-solid fa-house"></i> Home </a></li>
            <li><a href="data_narapidana.php"><i class="fa-solid fa-file-lines"></i> Data Narapidana </a></li>
            <li><a href="data_tahanan.php"><i class="fa-solid fa-file-lines"></i> Data Tahanan </a></li>
            <li><a href="kunjungan.php"><i class="fa-solid fa-person"></i> Kunjungan </a></li>
            <li><a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout </a></li>
        </ul>
    </div>

    <div class="main-content">
        <header>
        <h2>Dashboard <span id="tanggal"></span></h2>
        <script>
    // Fungsi untuk mendapatkan dan menampilkan tanggal saat ini
    function tampilkanTanggal() {
        const tanggalElement = document.getElementById("tanggal");
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        const today = new Date();

        // Format tanggal sesuai lokal
        tanggalElement.textContent = ', ' + today.toLocaleDateString('id-ID', options);
    }

    // Panggil fungsi saat halaman dimuat
    tampilkanTanggal();
</script>
            <p>Selamat datang,   <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
        </header>
        

        
        <!-- Statistik -->
        <div class="stats">
    <div class="card card-narapidana">
        <div>
            <h3>Total Narapidana</h3>
            <p><?php echo $total_narapidana; ?></p>
        </div>
        <i class="fa-solid fa-user card-icon"></i>
    </div>
    
    <div class="card card-kunjungan">
                <div>
                    <h3>Kunjungan Hari Ini</h3>
                    <p><?php echo $total_pendaftar; ?></p>
                </div>
                <i class="fa-solid fa-calendar-day card-icon"></i>
            </div>
    
    <div class="card card-tahanan">
        <div>
            <h3>Total Tahanan</h3>
            <p><?php echo $total_tahanan; ?></p>
        </div>
        <i class="fa-solid fa-users card-icon"></i>
    </div>
    
</div>
<div class="info-bawah">
        <div class="icon-tahanan">
            <i class="fa-solid fa-user"></i>
            <p>Lapas Kelas IIB Merauke</p>
        </div>
        <div class="alamat">
            <p>Alamat: JL. Ermasu, No. 18, 99601, Mandala, Kec. Merauke, Kabupaten Merauke, Papua 99614</p>
        </div>
        </div>
</div>

    </div>
</body>
</html>

<?php

$pdo = null;

?>
