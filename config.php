<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "project_lapas";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

try {
    $db = new PDO("mysql:host=localhost;dbname=project_lapas", "root", "");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Koneksi gagal: " . $e->getMessage();
    exit;
}
?>
