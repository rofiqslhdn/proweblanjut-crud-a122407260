<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, PUT");
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

    // Identifikasi barang yang akan diubah melalui id yang dikirim (bisa dari body atau query params)
    $id = $inputData['id'] ?? $_GET['id'] ?? null;

    if (!$id) {
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "ID barang tidak boleh kosong."
        ], JSON_PRETTY_PRINT);
        exit();
    }

    // Ambil data barang saat ini dari database
    $existing = $barangModel->getById($id);
    if (!$existing) {
        http_response_code(404);
        echo json_encode([
            "status" => "error",
            "message" => "Barang dengan ID $id tidak ditemukan."
        ], JSON_PRETTY_PRINT);
        exit();
    }

    // Mengambil parameter baru atau menggunakan data lama sebagai fallback
    $nama_barang   = trim($inputData['nama_barang'] ?? $existing['nama_barang']);
    $kategori      = trim($inputData['kategori'] ?? $existing['kategori']);
    $jumlah        = $inputData['jumlah'] ?? $existing['jumlah'];
    $harga         = $inputData['harga'] ?? $existing['harga'];
    $tanggal_masuk = trim($inputData['tanggal_masuk'] ?? $existing['tanggal_masuk']);
    $gambar_lama   = $existing['gambar'];

    // Validasi input
    $errors = [];
    if (empty($nama_barang)) {
        $errors[] = "nama_barang tidak boleh kosong.";
    }
    if (empty($kategori)) {
        $errors[] = "kategori tidak boleh kosong.";
    }
    if ($jumlah === '' || !is_numeric($jumlah) || $jumlah < 0) {
        $errors[] = "jumlah harus berupa angka positif.";
    }
    if ($harga === '' || !is_numeric($harga) || $harga < 0) {
        $errors[] = "harga harus berupa angka positif.";
    }
    if (empty($tanggal_masuk)) {
        $errors[] = "tanggal_masuk tidak boleh kosong.";
    }

    if (!empty($errors)) {
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "Validasi gagal",
            "errors" => $errors
        ], JSON_PRETTY_PRINT);
        exit();
    }

    // Default gambar menggunakan gambar lama
    $gambar = $gambar_lama;

    // Mendukung pengunggahan berkas gambar baru secara opsional melalui multipart/form-data
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        $file_tmp_path = $_FILES['gambar']['tmp_name'];
        $file_name = $_FILES['gambar']['name'];
        $file_size = $_FILES['gambar']['size'];
        
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'webp'];
        $file_info = pathinfo($file_name);
        $file_ext = strtolower($file_info['extension']);
        
        if (!in_array($file_ext, $allowed_extensions)) {
            $errors[] = "Tipe file gambar baru tidak valid. Hanya JPG, PNG, dan WEBP yang diizinkan.";
        }
        if ($file_size > 2 * 1024 * 1024) {
            $errors[] = "Ukuran file gambar baru terlalu besar. Maksimal 2MB.";
        }

        if (empty($errors)) {
            $clean_name = preg_replace("/[^a-zA-Z0-9.]/", "_", $file_info['filename']);
            $nama_file_baru = uniqid() . "_" . $clean_name . "." . $file_ext;

            $target_dir = "../assets/img/";
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            if (move_uploaded_file($file_tmp_path, $target_dir . $nama_file_baru)) {
                // Hapus gambar lama jika ada
                if (!empty($gambar_lama) && file_exists($target_dir . $gambar_lama)) {
                    unlink($target_dir . $gambar_lama);
                }
                $gambar = $nama_file_baru;
            } else {
                $errors[] = "Gagal memindahkan file gambar baru.";
            }
        }
    }

    if (!empty($errors)) {
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "Validasi berkas gambar baru gagal",
            "errors" => $errors
        ], JSON_PRETTY_PRINT);
        exit();
    }

    // Memperbarui data ke database
    $updateData = [
        'nama_barang'   => $nama_barang,
        'kategori'      => $kategori,
        'jumlah'        => (int)$jumlah,
        'tanggal_masuk' => $tanggal_masuk,
        'harga'         => (float)$harga,
        'gambar'        => $gambar
    ];

    if ($barangModel->update($id, $updateData)) {
        http_response_code(200);
        echo json_encode([
            "status" => "success",
            "message" => "Data barang berhasil diperbarui!",
            "data" => array_merge(["id" => (int)$id, "kode_barang" => $existing['kode_barang']], $updateData)
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    } else {
        http_response_code(500);
        echo json_encode([
            "status" => "error",
            "message" => "Gagal memperbarui data barang di database."
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