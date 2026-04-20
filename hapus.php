<?php
session_start();
include 'koneksi.php';

// Ambil id dari url dengan aman
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    header("Location: index.php?page=data_barang");
    exit();
}

// Cek apakah barang ada dan ambil data gambarnya
$stmt = $pdo->prepare("SELECT gambar FROM barang WHERE id = ?");
$stmt->execute([$id]);
$barang = $stmt->fetch(PDO::FETCH_ASSOC);

if ($barang) {
    // Hapus file gambar fisik dari folder assets/img/ jika ada
    if (!empty($barang['gambar'])) {
        $target_file = "assets/img/" . $barang['gambar'];
        if (file_exists($target_file)) {
            unlink($target_file); // Menghapus file dari server
        }
    }

    // 4. Hapus data barang dari database (Prepared Statement)
    $stmt = $pdo->prepare("DELETE FROM barang WHERE id = ?");
    $stmt->execute([$id]);
    
    // Tambahkan pesan sukses ke session
    $_SESSION['pesan'] = "Barang berhasil dihapus!";
    $_SESSION['tipe'] = "success";
}

// Kembali ke halaman data barang
header("Location: index.php?page=data_barang");
exit();