<?php

class Barang {
    private $db;

    // Konstruktor menerima objek koneksi PDO
    public function __construct($db) {
        $this->db = $db;
    }

    // Mengambil semua data barang untuk ditampilkan di tabel
    public function getAll() {
        $stmt = $this->db->prepare("SELECT * FROM barang ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Mengambil satu data barang berdasarkan ID (untuk Edit & Hapus)
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM barang WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Memeriksa apakah kode barang sudah pernah terdaftar
    public function checkKodeExists($kode_barang) {
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM barang WHERE kode_barang = ?");
        $stmt->execute([$kode_barang]);
        $row = $stmt->fetch();
        return $row['count'] > 0;
    }

    // Mencari nomor urut kode barang terbesar untuk auto-code (GM001, GM002, dst)
    public function getMaxKodeNumber() {
        $stmt = $this->db->prepare("SELECT MAX(SUBSTRING(kode_barang, 3)) as max_code FROM barang WHERE kode_barang LIKE 'GM%'");
        $stmt->execute();
        $row = $stmt->fetch();
        return $row['max_code'] ?? 0;
    }

    // Menyimpan data barang baru ke database
    public function insert($data) {
        $stmt = $this->db->prepare("INSERT INTO barang 
            (kode_barang, nama_barang, kategori, jumlah, tanggal_masuk, harga, gambar) 
            VALUES (?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['kode_barang'],
            $data['nama_barang'],
            $data['kategori'],
            $data['jumlah'],
            $data['tanggal_masuk'],
            $data['harga'],
            $data['gambar']
        ]);
    }

    // Memperbarui data barang yang sudah ada
    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE barang SET 
            nama_barang = ?, 
            kategori = ?, 
            jumlah = ?, 
            tanggal_masuk = ?, 
            harga = ?, 
            gambar = ? 
            WHERE id = ?");
        return $stmt->execute([
            $data['nama_barang'],
            $data['kategori'],
            $data['jumlah'],
            $data['tanggal_masuk'],
            $data['harga'],
            $data['gambar'],
            $id
        ]);
    }

    // Menghapus data barang berdasarkan ID
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM barang WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // --- QUERY KEBUTUHAN STATISTIK DASHBOARD ---

    // Menghitung barang dengan stok tipis (< 10)
    public function countStokRendah() {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM barang WHERE jumlah < 10");
        $stmt->execute();
        $row = $stmt->fetch();
        return $row['total'] ?? 0;
    }

    // Menghitung total jenis barang
    public function countTotalBarang() {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM barang");
        $stmt->execute();
        $row = $stmt->fetch();
        return $row['total'] ?? 0;
    }

    // Menghitung total stok seluruh unit barang
    public function sumTotalStok() {
        $stmt = $this->db->prepare("SELECT SUM(jumlah) as total FROM barang");
        $stmt->execute();
        $row = $stmt->fetch();
        return $row['total'] ?? 0;
    }

    // Menghitung total nilai aset barang (jumlah * harga)
    public function sumNilaiInventory() {
        $stmt = $this->db->prepare("SELECT SUM(jumlah * harga) as total FROM barang");
        $stmt->execute();
        $row = $stmt->fetch();
        return $row['total'] ?? 0;
    }
}
