<?php
// Memuat koneksi database & inisialisasi session
require_once __DIR__ . '/../config/koneksi.php';

// Fitur Remember Me: Auto-login dengan Cookie jika session kosong tapi cookie ada
if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_user'])) {
    $_SESSION['user_id'] = $_COOKIE['remember_user'];
}

// Menentukan halaman aktif (Default: dashboard)
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

// Pengecekan status login
$isLoggedIn = isset($_SESSION['user_id']);

// Daftar halaman yang dapat diakses Tanpa Login (Public Pages)
$publicPages = ['login', 'login_process', 'register', 'register_process'];

// PROTEKSI AKSES:
if (!$isLoggedIn && !in_array($page, $publicPages)) {
    // Jika belum login dan mencoba masuk ke halaman admin, paksa ke login
    header("Location: index.php?page=login");
    exit();
}

if ($isLoggedIn && in_array($page, ['login', 'register'])) {
    // Jika sudah login dan mencoba membuka form login/register, alihkan ke dashboard
    header("Location: index.php?page=dashboard");
    exit();
}

// ROUTER SEDERHANA MVC (Switch-Case Halaman)
switch ($page) {
    case 'login':
        require_once __DIR__ . '/../app/controllers/UserController.php';
        $controller = new UserController($pdo);
        $controller->login();
        break;

    case 'login_process':
        require_once __DIR__ . '/../app/controllers/UserController.php';
        $controller = new UserController($pdo);
        $controller->loginProcess();
        break;

    case 'register':
        require_once __DIR__ . '/../app/controllers/UserController.php';
        $controller = new UserController($pdo);
        $controller->register();
        break;

    case 'register_process':
        require_once __DIR__ . '/../app/controllers/UserController.php';
        $controller = new UserController($pdo);
        $controller->registerProcess();
        break;

    case 'logout':
        require_once __DIR__ . '/../app/controllers/UserController.php';
        $controller = new UserController($pdo);
        $controller->logout();
        break;

    case 'dashboard':
        require_once __DIR__ . '/../app/controllers/DashboardController.php';
        $controller = new DashboardController($pdo);
        $controller->index();
        break;

    case 'data_barang':
        require_once __DIR__ . '/../app/controllers/BarangController.php';
        $controller = new BarangController($pdo);
        $controller->index();
        break;

    case 'tambah':
        require_once __DIR__ . '/../app/controllers/BarangController.php';
        $controller = new BarangController($pdo);
        $controller->tambah();
        break;

    case 'edit':
        require_once __DIR__ . '/../app/controllers/BarangController.php';
        $controller = new BarangController($pdo);
        $controller->edit();
        break;

    case 'hapus':
        require_once __DIR__ . '/../app/controllers/BarangController.php';
        $controller = new BarangController($pdo);
        $controller->hapus();
        break;

    default:
        // Jika parameter page tidak dikenal, alihkan ke dashboard
        header("Location: index.php?page=dashboard");
        exit();
}
?>