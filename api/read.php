<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Muat konfigurasi database dan model Barang
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../app/models/Barang.php';

try {
    // Pastikan koneksi database tersedia ($pdo didefinisikan di koneksi.php)
    if (!isset($pdo)) {
        throw new Exception("Koneksi database tidak tersedia.");
    }

    $barangModel = new Barang($pdo);
    $data = $barangModel->getAll();

    // Sesuaikan nilai numerik ke tipe data yang sesuai
    $formattedData = [];
    foreach ($data as $row) {
        $formattedData[] = [
            "id" => (int)$row['id'],
            "kode_barang" => $row['kode_barang'],
            "nama_barang" => $row['nama_barang'],
            "kategori" => $row['kategori'],
            "jumlah" => (int)$row['jumlah'],
            "tanggal_masuk" => $row['tanggal_masuk'],
            "harga" => (float)$row['harga'],
            "gambar" => $row['gambar'] ? $row['gambar'] : null
        ];
    }

    // Mengembalikan data barang dalam format JSON (array of objects)
    http_response_code(200);
    echo json_encode($formattedData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Gagal mengambil data barang: " . $e->getMessage()
    ], JSON_PRETTY_PRINT);
}
?>