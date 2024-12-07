<?php
session_start();

// Hapus semua data sesi
session_unset();
session_destroy();

// Hapus cookie password saat logout untuk keamanan
setcookie("password", "", time() - 3600, "/");

// Redirect ke halaman login setelah logout
header("Location: login.php");
exit;
?>
