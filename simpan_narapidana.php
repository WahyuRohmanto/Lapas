<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "project_lapas";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Mengamankan input pengguna
    $nama = $conn->real_escape_string($_POST['nama']);
    $jenis_kelamin = $conn->real_escape_string($_POST['jenis_kelamin']);
    $jenis_kejahatan = $conn->real_escape_string($_POST['jenis_kejahatan']);
    $Blok_Kamar_hunian = $conn->real_escape_string($_POST['Blok_Kamar_hunian']);
    $tanggal_masuk = $conn->real_escape_string($_POST['tanggal_masuk']);
    $total_hukuman = (int)$_POST['total_hukuman'];

    // Hitung setengah masa tahanan
    $setengah_hukuman = ceil($total_hukuman / 2);

    // Tentukan status narapidana berdasarkan tanggal masuk dan total hukuman
    $tanggal_bebas = date('Y-m-d', strtotime("+$total_hukuman years", strtotime($tanggal_masuk)));
    $status = (date('Y-m-d') >= $tanggal_bebas) ? "Bebas" : "Narapidana";

    // Proses upload foto
    $foto = $_FILES['foto']['name'];
    $target_dir = "uploads/";

    // Pastikan folder uploads ada
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $target_file = $target_dir . basename($foto);
    if (move_uploaded_file($_FILES['foto']['tmp_name'], $target_file)) {
        // Query untuk memasukkan data ke database, termasuk kolom status
        $sql = "INSERT INTO narapidana (nama, jenis_kelamin, jenis_kejahatan, Blok_Kamar_hunian, tanggal_masuk, total_hukuman, setengah_hukuman, foto, status) 
                VALUES ('$nama', '$jenis_kelamin', '$jenis_kejahatan', '$Blok_Kamar_hunian', '$tanggal_masuk', '$total_hukuman', '$setengah_hukuman', '$foto', '$status')";

        if ($conn->query($sql) === TRUE) {
            echo "<script>
                    alert('Data berhasil disimpan!');
                    window.location.href = 'data_narapidana.php';
                  </script>";
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        echo "Error saat mengunggah foto.";
    }
}

$conn->close();
?>
