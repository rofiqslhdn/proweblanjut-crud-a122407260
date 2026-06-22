<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk | Gaming Inventory</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/auth.css">
</head>
<body>
    <div class="auth-card">
        <div class="auth-logo">
            <i class="fas fa-gamepad"></i> <span>GAMING INVENTORY</span>
        </div>

        <h2>Masuk</h2>
        <p>Masukkan kredensial Anda untuk melanjutkan</p>

        <!-- Pesan Error/Sukses -->
        <?php if(!empty($error)): ?>
            <div class="error-msg"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <?php if(isset($_SESSION['pesan'])): ?>
            <div class="success-msg">
                <?php echo htmlspecialchars($_SESSION['pesan']); ?>
            </div>
            <?php unset($_SESSION['pesan']); ?>
        <?php endif; ?>

        <form action="index.php?page=login_process" method="POST">
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
            Belum punya akun? <a href="index.php?page=register">Daftar sekarang</a>
        </div>
    </div>
</body>
</html>
