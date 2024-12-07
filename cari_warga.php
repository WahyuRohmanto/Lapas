<?php
include 'koneksi.php';

if (isset($_GET['search'])) {
    $searchTerm = $_GET['search'];

    // Inisialisasi hasil sebagai array kosong
    $resultsnarapidana = [];
    $resultstahanan = [];

    // Query untuk tabel narapidana
    $stmtNarapidana = $pdo->prepare("SELECT nama, jenis_kejahatan, Blok_Kamar_hunian AS blok, status AS keadaan FROM narapidana WHERE nama LIKE ?");
    $stmtNarapidana->execute(["%$searchTerm%"]);
    $resultsnarapidana = $stmtNarapidana->fetchAll(PDO::FETCH_ASSOC);

    // Query untuk tabel tahanan
    $stmtTahanan = $pdo->prepare("SELECT nama, jenis_kejahatan, Blok_Kamar_hunian AS blok, status AS keadaan FROM tahanan WHERE nama LIKE ?");
    $stmtTahanan->execute(["%$searchTerm%"]);
    $resultstahanan = $stmtTahanan->fetchAll(PDO::FETCH_ASSOC);

    // Gabungkan hasil dari kedua query
    $results = array_merge($resultsnarapidana, $resultstahanan);

    // Kirim hasil sebagai JSON
    echo json_encode($results);
    exit;
}
?>
