<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo isset($page_title) ? $page_title . ' | ' : ''; ?>Inventory Toko Gaming</title>

    <link rel="stylesheet" href="../assets/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>

    <div class="container">

        <!-- Header -->
        <header class="header">
            <div class="header-left">
                <div class="logo">
                    <i class="fas fa-gamepad"></i>
                    <h1>Inventory Toko Gaming</h1>
                </div>
            </div>

            <div class="header-right">
                <div class="date-time">
                    <i class="fas fa-calendar-day"></i>
                    <span id="current-date"><?php echo date('d/m/Y'); ?></span>
                </div>
            </div>
        </header>

        <!-- Notifikasi Sesi (Pesan Sukses/Error CRUD) -->
        <?php if (isset($_SESSION['pesan'])): ?>
            <div class="notification <?php echo $_SESSION['tipe']; ?>">
                <div class="notification-content">
                    <i class="fas <?php echo $_SESSION['tipe'] == 'success' ? 'fa-check-circle' : 'fa-triangle-exclamation'; ?>"></i>
                    <span><?php echo $_SESSION['pesan']; ?></span>
                </div>
                <button class="notification-close" onclick="this.parentElement.style.display='none'">
                    <i class="fas fa-xmark"></i>
                </button>
            </div>
            <?php
            // Hapus notifikasi setelah ditampilkan sekali
            unset($_SESSION['pesan']);
            unset($_SESSION['tipe']);
            ?>
        <?php endif; ?>
