<?php
session_start();
require 'config.php'; // Koneksi ke database

// Cek apakah cookie ada dan atur ulang nilai input jika tersedia
$saved_username = isset($_COOKIE['username']) ? $_COOKIE['username'] : '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $remember = isset($_POST['remember']);

    // Enkripsi password input menggunakan MD5
    $hashed_password = md5($password);

    // Query untuk mendapatkan user berdasarkan username
    $query = $db->prepare("SELECT * FROM user WHERE username = :username");
    $query->execute(['username' => $username]);
    $user = $query->fetch(PDO::FETCH_ASSOC);

    // Verifikasi password
    if ($user && $user['password'] === $hashed_password) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];

        // Jika "Remember Me" dipilih, simpan username dalam cookie selama 30 hari
        if ($remember) {
            setcookie("username", $username, time() + (86400 * 30), "/"); // Cookie untuk 30 hari
        } else {
            // Hapus cookie jika "Remember Me" tidak dipilih
            setcookie("username", "", time() - 3600, "/");
        }

        header("Location: dashboard.php"); // Arahkan ke dashboard
        exit;
    } else {
        $error = "Username atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/login_admin.css">
    <script src="https://kit.fontawesome.com/e8437d67c2.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,100..700;1,100..700&family=Nunito:ital,wght@0,600;1,600&display=swap" rel="stylesheet">
    <title>Login</title>
</head>
<body>
    
    <div class="container">
        <h2>Login Admin</h2>
        <form action="login.php" method="POST">
            <label>Username:</label>
            <input type="text" name="username" value="<?php echo htmlspecialchars($saved_username); ?>" required>
            
    <div class="password-container">
    <label for="password">Password:</label>
    <div class="input-group">
        <input type="password" name="password" id="password"  required >
        <span id="eye-icon" class="fa fa-eye toggle-password" onclick="togglePassword()"></span>
    <script>
    function togglePassword() {
        const passwordInput = document.getElementById("password");
        const eyeIcon = document.getElementById("eye-icon");

        // Ubah tipe input antara "password" dan "text"
        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            eyeIcon.classList.remove("fa-eye");
            eyeIcon.classList.add("fa-eye-slash");
        } else {
            passwordInput.type = "password";
            eyeIcon.classList.remove("fa-eye-slash");
            eyeIcon.classList.add("fa-eye");
        }
    }
</script>
    </div>
</div>
            <!-- Pesan kesalahan ditempatkan di bawah input password -->
            <?php if (isset($error)) echo "<p class='error-message'>$error</p>"; ?>

            <!-- Checkbox Remember Me -->
            <label>
                <input type="checkbox" name="remember" <?php if ($saved_username) echo 'checked'; ?>> Remember Me
            </label>

            <button type="submit">Login</button>
        </form>
    </div>
    
</body>
</html>
