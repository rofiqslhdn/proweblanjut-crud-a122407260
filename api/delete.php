<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Muat konfigurasi database dan model Barang
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../app/models/Barang.php';

try {
    if (!isset($pdo)) {
        throw new Exception("Koneksi database tidak tersedia.");
    }

    $barangModel = new Barang($pdo);

    // Ambil input dari JSON payload atau $_POST
    $inputData = json_decode(file_get_contents("php://input"), true);
    if (!is_array($inputData)) {
        $inputData = $_POST;
    }

    // Identifikasi barang yang akan dihapus melalui id yang dikirim (bisa dari body atau query params)
    $id = $inputData['id'] ?? $_GET['id'] ?? null;

    if (!$id) {
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "ID barang tidak boleh kosong."
        ], JSON_PRETTY_PRINT);
        exit();
    }

    // Ambil data barang dari database untuk memastikan barang ada dan mengambil nama file gambar
    $barang = $barangModel->getById($id);
    if (!$barang) {
        http_response_code(404);
        echo json_encode([
            "status" => "error",
            "message" => "Barang dengan ID $id tidak ditemukan."
        ], JSON_PRETTY_PRINT);
        exit();
    }

    // Hapus gambar produk di folder fisik jika ada
    if (!empty($barang['gambar'])) {
        $target_file = "../assets/img/" . $barang['gambar'];
        if (file_exists($target_file)) {
            unlink($target_file);
        }
    }

    // Hapus baris data di database
    if ($barangModel->delete($id)) {
        http_response_code(200);
        echo json_encode([
            "status" => "success",
            "message" => "Barang berhasil dihapus!",
            "deleted_id" => (int)$id
        ], JSON_PRETTY_PRINT);
    } else {
        http_response_code(500);
        echo json_encode([
            "status" => "error",
            "message" => "Terjadi kesalahan sistem saat mencoba menghapus barang."
        ], JSON_PRETTY_PRINT);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Kesalahan server: " . $e->getMessage()
    ], JSON_PRETTY_PRINT);
}
?>