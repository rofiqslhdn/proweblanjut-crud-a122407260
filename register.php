<?php
include "koneksi.php";

$message = "";
$message_class = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]); 
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT); 

    try {
        $cek = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
        $cek->execute([$username]);
        
        if ($cek->fetchColumn() > 0) {
            $message = "Username sudah terdaftar!";
            $message_class = "error-msg";
        } else {
            $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            if ($stmt->execute([$username, $password])) {
                header("Location: login.php");
                exit();
            } else {
                $message = "Gagal mendaftar!";
                $message_class = "error-msg";
            }
        }
    } catch (PDOException $e) {
        $message = "Terjadi kesalahan: " . $e->getMessage();
        $message_class = "error-msg";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun | Gaming Inventory</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/auth.css">
</head>
<body>
    <div class="auth-card">
        <div class="auth-logo">
            <i class="fas fa-gamepad"></i>
            <span>GAMING INVENTORY</span>
        </div>

        <h2>Daftar Akun</h2>
        <p>Buat akun baru untuk mengelola inventaris</p>

        <?php if($message): ?>
            <div class="<?php echo $message_class; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <input type="text" name="username" placeholder="Username Baru" required>
            </div>
            <div class="form-group">
                <input type="password" name="password" placeholder="Password Baru" required>
            </div>
            <button type="submit" class="btn-signin" style="margin-top: 10px;">Daftar Akun</button>
        </form>
        <div class="auth-footer">
            Sudah punya akun? <a href="login.php">Masuk di sini</a>
        </div>
    </div>
</body>
</html>