<?php

$user_name = "Guest";
if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT username FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();
    if ($user) {
        $user_name = htmlspecialchars($user['username']);
    }
}

$menu_items = array(

    'dashboard' => array(
        'icon' => 'fas fa-home',
        'title' => 'Dashboard',
        'link' => 'index.php?page=dashboard',
        'active' => ((!isset($_GET['page'])) &&
            (basename($_SERVER['PHP_SELF']) == 'index.php')) ||
            (isset($_GET['page']) && $_GET['page'] == 'dashboard')
    ),

    'data_barang' => array(
        'icon' => 'fas fa-box',
        'title' => 'Data Barang',
        'link' => 'index.php?page=data_barang',
        'active' => (isset($_GET['page']) && $_GET['page'] == 'data_barang') ||
            (basename($_SERVER['PHP_SELF']) == 'tambah.php') ||
            (basename($_SERVER['PHP_SELF']) == 'edit.php')
    )

);

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

            <?php foreach ($menu_items as $item): ?>

                <li>
                    <a href="<?php echo $item['link']; ?>" class="<?php echo $item['active'] ? 'active' : ''; ?>">
                        <i class="<?php echo $item['icon']; ?>"></i>
                        <span><?php echo $item['title']; ?></span>
                    </a>
                </li>

            <?php endforeach; ?>

        </ul>
    </nav>

    <div class="sidebar-footer">
        <a href="logout.php" class="logout-btn">
            <i class="fas fa-sign-out-alt"></i>
            <span>Keluar</span>
        </a>
    </div>

</aside>