<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
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

    // Ambil parameter-parameter
    $kode_barang   = trim($inputData['kode_barang'] ?? '');
    $nama_barang   = trim($inputData['nama_barang'] ?? '');
    $kategori      = trim($inputData['kategori'] ?? 'Aksesoris');
    $jumlah        = $inputData['jumlah'] ?? '';
    $harga         = $inputData['harga'] ?? '';
    $tanggal_masuk = trim($inputData['tanggal_masuk'] ?? date('Y-m-d'));

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

    // Auto-generate kode barang jika kosong
    if (empty($kode_barang)) {
        $maxCode = $barangModel->getMaxKodeNumber();
        $nextNum = $maxCode + 1;
        $kode_barang = "GM" . str_pad($nextNum, 3, "0", STR_PAD_LEFT);
    }

    // Periksa apakah kode barang duplikat
    if ($barangModel->checkKodeExists($kode_barang)) {
        http_response_code(409);
        echo json_encode([
            "status" => "error",
            "message" => "Kode barang sudah terdaftar di sistem!"
        ], JSON_PRETTY_PRINT);
        exit();
    }

    // Mendukung pengunggahan berkas gambar secara opsional melalui multipart/form-data
    $gambar = "";
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        $file_tmp_path = $_FILES['gambar']['tmp_name'];
        $file_name = $_FILES['gambar']['name'];
        $file_size = $_FILES['gambar']['size'];
        
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'webp'];
        $file_info = pathinfo($file_name);
        $file_ext = strtolower($file_info['extension']);
        
        if (!in_array($file_ext, $allowed_extensions)) {
            $errors[] = "Tipe file gambar tidak valid. Hanya JPG, PNG, dan WEBP yang diizinkan.";
        }
        if ($file_size > 2 * 1024 * 1024) {
            $errors[] = "Ukuran file terlalu besar. Maksimal 2MB.";
        }

        if (empty($errors)) {
            $clean_name = preg_replace("/[^a-zA-Z0-9.]/", "_", $file_info['filename']);
            $gambar = uniqid() . "_" . $clean_name . "." . $file_ext;

            $target_dir = "../assets/img/";
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            if (!move_uploaded_file($file_tmp_path, $target_dir . $gambar)) {
                $gambar = "";
            }
        }
    }

    // Jika ada error pada gambar
    if (!empty($errors)) {
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "Validasi berkas gambar gagal",
            "errors" => $errors
        ], JSON_PRETTY_PRINT);
        exit();
    }

    // Simpan ke database
    $insertData = [
        'kode_barang'   => $kode_barang,
        'nama_barang'   => $nama_barang,
        'kategori'      => $kategori,
        'jumlah'        => (int)$jumlah,
        'tanggal_masuk' => $tanggal_masuk,
        'harga'         => (float)$harga,
        'gambar'        => $gambar
    ];

    if ($barangModel->insert($insertData)) {
        $newId = (int)$pdo->lastInsertId();
        http_response_code(201);
        echo json_encode([
            "status" => "success",
            "message" => "Barang baru berhasil ditambahkan!",
            "data" => array_merge(["id" => $newId], $insertData)
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    } else {
        http_response_code(500);
        echo json_encode([
            "status" => "error",
            "message" => "Terjadi kesalahan sistem saat menyimpan data barang."
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