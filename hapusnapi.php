<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "project_lapas";

// Membuat koneksi ke database
$conn = new mysqli($host, $user, $pass, $dbname);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Mengecek apakah parameter ID telah diterima untuk penghapusan
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Query untuk menghapus data narapidana berdasarkan ID
    $delete_sql = "DELETE FROM narapidana WHERE id = $id";
    if ($conn->query($delete_sql) === TRUE) {
        echo "<script>alert('Data berhasil dihapus!'); window.location.href='data_narapidana.php';</script>";

    } else {
        echo "Error: " . $conn->error;
    }
} else {
    echo "<script>alert('ID tidak ditemukan!'); window.location.href='data_narapidana.php';</script>";
}

// Menutup koneksi database
$conn->close();
?>
