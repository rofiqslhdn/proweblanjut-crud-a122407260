<?php
// Ambil $pdo dari scope global (karena file ini di-include dari dalam method controller)
global $pdo;

$user_name = "Guest";
if (isset($_SESSION['user_id'])) {
    include_once __DIR__ . '/../models/User.php';
    $userModelForMenu = new User($pdo);
    $currentUser = $userModelForMenu->getById($_SESSION['user_id']);
    if ($currentUser) {
        $user_name = htmlspecialchars($currentUser['username']);
    }
}

// Menentukan kelas aktif untuk menu navigasi
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
$dashboardActive = ($page == 'dashboard') ? 'active' : '';
$barangActive = ($page == 'data_barang' || $page == 'tambah' || $page == 'edit') ? 'active' : '';
?>

<!-- Sidebar Menu -->
<aside class="sidebar">
    <div class="sidebar-profile">
        <div class="profile-avatar">
            <i class="fas fa-user-circle"></i>
        </div>
        <div class="profile-info">
            <h4 class="profile-name"><?php echo $user_name; ?></h4>
        </div>
    </div>

    <nav class="main-menu">
        <ul>
            <li>
                <a href="index.php?page=dashboard" class="<?php echo $dashboardActive; ?>">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="index.php?page=data_barang" class="<?php echo $barangActive; ?>">
                    <i class="fas fa-box"></i>
                    <span>Data Barang</span>
                </a>
            </li>
        </ul>
    </nav>

    <div class="sidebar-footer">
        <a href="index.php?page=logout" class="logout-btn">
            <i class="fas fa-sign-out-alt"></i>
            <span>Keluar</span>
        </a>
    </div>
</aside>
