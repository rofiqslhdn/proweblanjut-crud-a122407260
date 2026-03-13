<?php

$page_title = "Dashboard";

$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
?>

<?php include 'includes/header.php'; ?>

<div class="content-wrapper">
    <?php include 'includes/menu.php'; ?>
    
    <main class="main-content">
        <div class="page-header">
            <?php
            $page_titles = array(
                'dashboard' => 'Dashboard',
                'data_barang' => 'Data Barang',
            );
            ?>
            <h2><?php echo $page_titles[$page] ?? 'Dashboard'; ?></h2>
        </div>
        
        <div class="content">
            <?php
            switch($page) {
                case 'dashboard':
                    include 'pages/dashboard.php';
                    break;
                case 'data_barang':
                    include 'pages/data_barang.php';
            }
            ?>
        </div>
    </main>
</div>

<?php include 'includes/footer.php'; ?>