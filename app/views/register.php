<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun | Gaming Inventory</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/auth.css">
</head>
<body>
    <div class="auth-card">
        <div class="auth-logo">
            <i class="fas fa-gamepad"></i>
            <span>GAMING INVENTORY</span>
        </div>

        <h2>Daftar Akun</h2>
        <p>Buat akun baru untuk mengelola inventaris</p>

        <?php if(!empty($message)): ?>
            <div class="<?php echo $message_class; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <form action="index.php?page=register_process" method="POST">
            <div class="form-group">
                <input type="text" name="username" placeholder="Username Baru" required>
            </div>
            <div class="form-group">
                <input type="password" name="password" placeholder="Password Baru" required>
            </div>
            <button type="submit" class="btn-signin" style="margin-top: 10px;">Daftar Akun</button>
        </form>
        <div class="auth-footer">
            Sudah punya akun? <a href="index.php?page=login">Masuk di sini</a>
        </div>
    </div>
</body>
</html>
