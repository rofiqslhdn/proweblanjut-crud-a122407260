<?php

class UserController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Menampilkan form login
    public function login() {
        $error = $_SESSION['error'] ?? '';
        unset($_SESSION['error']); // Hapus setelah dibaca
        
        include __DIR__ . '/../views/login.php';
    }

    // Memproses form login
    public function loginProcess() {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            header('Location: index.php?page=login');
            exit();
        }

        $username = trim($_POST["username"] ?? '');
        $password = $_POST["password"] ?? '';
        $remember = isset($_POST["remember"]);

        if (empty($username) || empty($password)) {
            $_SESSION['error'] = "Username dan Password tidak boleh kosong!";
            header('Location: index.php?page=login');
            exit();
        }

        include_once __DIR__ . '/../models/User.php';
        $userModel = new User($this->db);
        $user = $userModel->getByUsername($username);

        if ($user) {
            if (password_verify($password, $user["password"])) {
                $_SESSION["user_id"] = $user["id"];

                if ($remember) {
                    // Set cookie remember_user selama 7 hari
                    setcookie('remember_user', $user['id'], time() + (86400 * 7), "/"); 
                }

                header('Location: index.php?page=dashboard');
                exit();
            } else {
                $_SESSION['error'] = "Password salah!";
            }
        } else {
            $_SESSION['error'] = "Username tidak ditemukan!";
        }

        header('Location: index.php?page=login');
        exit();
    }

    // Menampilkan form register
    public function register() {
        $message = $_SESSION['message'] ?? '';
        $message_class = $_SESSION['message_class'] ?? '';
        unset($_SESSION['message'], $_SESSION['message_class']);
        
        include __DIR__ . '/../views/register.php';
    }

    // Memproses form register
    public function registerProcess() {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            header('Location: index.php?page=register');
            exit();
        }

        $username = trim($_POST["username"] ?? '');
        $password = $_POST["password"] ?? '';

        if (empty($username) || empty($password)) {
            $_SESSION['message'] = "Semua kolom pendaftaran wajib diisi!";
            $_SESSION['message_class'] = "error-msg";
            header('Location: index.php?page=register');
            exit();
        }

        include_once __DIR__ . '/../models/User.php';
        $userModel = new User($this->db);

        if ($userModel->checkExists($username)) {
            $_SESSION['message'] = "Username sudah terdaftar!";
            $_SESSION['message_class'] = "error-msg";
            header('Location: index.php?page=register');
            exit();
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            if ($userModel->create($username, $hashedPassword)) {
                $_SESSION['pesan'] = "Registrasi berhasil! Silakan masuk.";
                header('Location: index.php?page=login');
                exit();
            } else {
                $_SESSION['message'] = "Gagal mendaftar!";
                $_SESSION['message_class'] = "error-msg";
                header('Location: index.php?page=register');
                exit();
            }
        }
    }

    // Memproses logout
    public function logout() {
        $_SESSION = [];
        if (session_id()) {
            session_destroy();
        }

        if (isset($_COOKIE['remember_user'])) {
            setcookie('remember_user', '', time() - 3600, "/");
        }

        header('Location: index.php?page=login');
        exit();
    }
}