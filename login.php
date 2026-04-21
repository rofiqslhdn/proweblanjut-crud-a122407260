<?php
include "koneksi.php";

if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_user'])) {
    $_SESSION['user_id'] = $_COOKIE['remember_user'];
    header("Location: index.php?page=dashboard");
    exit();
}

if (isset($_SESSION['user_id'])) {
    header("Location: index.php?page=dashboard");
    exit();
}

$error = "";

// Proses Login saat Form di-submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = $_POST["password"];
    $remember = isset($_POST["remember"]);

    try {
        $stmt = $pdo->prepare("SELECT id, password FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            if (password_verify($password, $user["password"])) {
                $_SESSION["user_id"] = $user["id"];

                if ($remember) {
                    setcookie('remember_user', $user['id'], time() + (86400 * 7), "/"); 
                }

                header("Location: index.php?page=dashboard");
                exit();
            } else {
                $error = "Password salah!";
            }
        } else {
            $error = "Username tidak ditemukan!";
        }
    } catch (PDOException $e) {
        $error = "Terjadi kesalahan sistem: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk | Gaming Inventory</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/auth.css">
</head>
<body>
    <div class="auth-card">
        <div class="auth-logo">
            <i class="fas fa-gamepad"></i> <span>GAMING INVENTORY</span>
        </div>

        <h2>Masuk</h2>
        <p>Masukkan kredensial Anda untuk melanjutkan</p>

        <?php if($error): ?>
            <div class="error-msg"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <input type="text" name="username" placeholder="Username" required>
            </div>
            <div class="form-group">
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <div class="auth-options">
                <label style="display:flex; align-items:center; gap:5px; cursor:pointer;">
                    <input type="checkbox" name="remember"> Remember Me!
                </label>
                <a href="#" style="color: #4DA3FF; text-decoration: none;">Lupa password?</a>
            </div>
            <button type="submit" class="btn-signin">Masuk</button>
        </form>
        <div class="auth-footer">
            Belum punya akun? <a href="register.php">Daftar sekarang</a>
        </div>
    </div>
</body>
</html>