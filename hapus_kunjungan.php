<?php
// Include koneksi ke database
include 'koneksi.php';

// Memastikan ID pendaftar telah diterima
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Query untuk menghapus data pendaftar berdasarkan ID
    $stmt = $pdo->prepare("DELETE FROM pendaftar WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    
    // Cek apakah query berhasil dieksekusi
    if ($stmt->execute()) {
        // Jika berhasil, arahkan kembali ke halaman daftar kunjungan dengan pesan sukses
        header("Location: kunjungan.php?pesan=sukses_hapus");
        exit();
    } else {
        // Jika gagal, arahkan kembali dengan pesan error
        header("Location: kunjungan.php?pesan=gagal_hapus");
        exit();
    }
} else {
    // Jika ID tidak ditemukan, arahkan kembali dengan pesan error
    header("Location: kunjungan.php?pesan=id_tidak_ditemukan");
    exit();
}
