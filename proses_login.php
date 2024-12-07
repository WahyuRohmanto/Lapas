<?php
session_start();

// Ganti dengan username dan password yang sesuai
$admin_username = "admin"; 
$admin_password_md5 = md5("adminpassword"); // Password terenkripsi MD5

if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = md5($_POST['password']); // Enkripsi password yang dimasukkan dengan MD5

    // Verifikasi login
    if ($username === $admin_username && $password === $admin_password_md5) {
        $_SESSION['admin_logged_in'] = true;
        header('Location: dashboard.php');
        exit();
    } else {
        echo "Username atau password salah!";
    }
}
?>
