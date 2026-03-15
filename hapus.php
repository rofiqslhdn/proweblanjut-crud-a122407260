<?php
session_start();
include 'koneksi.php';

// ambil id dari url
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    header("Location: index.php?page=data_barang");
    exit();
}

// cek apakah barang ada
$stmt = $pdo->prepare("SELECT id FROM barang WHERE id = ?");
$stmt->execute([$id]);

$barang = $stmt->fetch(PDO::FETCH_ASSOC);

if ($barang) {

    // hapus barang
    $stmt = $pdo->prepare("DELETE FROM barang WHERE id = ?");
    $stmt->execute([$id]);

}

// kembali ke halaman data barang
header("Location: index.php?page=data_barang");
exit();