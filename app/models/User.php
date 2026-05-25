<?php

class User {
    private $db;

    // Konstruktor menerima objek koneksi PDO
    public function __construct($db) {
        $this->db = $db;
    }

    // Mencari user berdasarkan username untuk login
    public function getByUsername($username) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetch();
    }

    // Mencari user berdasarkan ID untuk profil
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Cek apakah username sudah pernah digunakan
    public function checkExists($username) {
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $row = $stmt->fetch();
        return $row['count'] > 0;
    }

    // Menyimpan registrasi user baru
    public function create($username, $password) {
        $stmt = $this->db->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        return $stmt->execute([$username, $password]);
    }
}
