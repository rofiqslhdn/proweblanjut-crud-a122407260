<?php

class BarangController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Menampilkan halaman daftar barang
    public function index() {
        include_once __DIR__ . '/../models/Barang.php';
        $barangModel = new Barang($this->db);
        $result = $barangModel->getAll();

        $page_title = "Data Barang";

        include __DIR__ . '/../views/header.php';
        include __DIR__ . '/../views/data_barang.php';
        include __DIR__ . '/../views/footer.php';
    }

    // Menambah barang baru
    public function tambah() {
        $kode_barang   = '';
        $nama_barang   = '';
        $kategori      = '';
        $jumlah        = '';
        $harga         = '';
        $tanggal_masuk = '';
        $errors        = [];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $kode_barang   = trim($_POST['kode_barang'] ?? '');
            $nama_barang   = trim($_POST['nama_barang'] ?? '');
            $kategori      = trim($_POST['kategori'] ?? '');
            $jumlah        = $_POST['jumlah'] ?? '';
            $harga         = $_POST['harga'] ?? '';
            $tanggal_masuk = $_POST['tanggal_masuk'] ?? '';

            // Validasi input
            if (empty($nama_barang)) {
                $errors[] = "Nama barang tidak boleh kosong.";
            }
            if (empty($kategori)) {
                $errors[] = "Silakan pilih kategori.";
            }
            if (!is_numeric($jumlah) || $jumlah < 0) {
                $errors[] = "Jumlah stok harus berupa angka positif.";
            }
            if (!is_numeric($harga) || $harga < 0) {
                $errors[] = "Harga harus berupa angka positif.";
            }
            if (empty($tanggal_masuk)) {
                $errors[] = "Tanggal masuk harus diisi.";
            }

            if (empty($errors)) {
                include_once __DIR__ . '/../models/Barang.php';
                $barangModel = new Barang($this->db);

                // Auto-generate kode barang jika kosong
                if (empty($kode_barang)) {
                    $maxCode = $barangModel->getMaxKodeNumber();
                    $nextNum = $maxCode + 1;
                    $kode_barang = "GM" . str_pad($nextNum, 3, "0", STR_PAD_LEFT);
                }

                // Periksa apakah kode barang duplikat
                if ($barangModel->checkKodeExists($kode_barang)) {
                    $errors[] = "Kode barang sudah terdaftar di sistem!";
                } else {
                    // Proses file unggahan gambar
                    $gambar = "";
                    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
                        $file_tmp_path = $_FILES['gambar']['tmp_name'];
                        $file_name = $_FILES['gambar']['name'];
                        $file_size = $_FILES['gambar']['size'];
                        
                        $allowed_extensions = ['jpg', 'jpeg', 'png', 'webp'];
                        $file_info = pathinfo($file_name);
                        $file_ext = strtolower($file_info['extension']);
                        
                        if (!in_array($file_ext, $allowed_extensions)) {
                            $errors[] = "Tipe file tidak valid. Hanya JPG, PNG, dan WEBP yang diizinkan.";
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
                                $errors[] = "Gagal memindahkan file gambar.";
                            }
                        }
                    }

                    // Simpan ke DB
                    if (empty($errors)) {
                        $insertData = [
                            'kode_barang'   => $kode_barang,
                            'nama_barang'   => $nama_barang,
                            'kategori'      => $kategori,
                            'jumlah'        => $jumlah,
                            'tanggal_masuk' => $tanggal_masuk,
                            'harga'         => $harga,
                            'gambar'        => $gambar
                        ];

                        if ($barangModel->insert($insertData)) {
                            $_SESSION['pesan'] = "Barang baru berhasil ditambahkan!";
                            $_SESSION['tipe']  = "success";
                            header('Location: index.php?page=data_barang');
                            exit();
                        } else {
                            $errors[] = "Terjadi kesalahan sistem saat menyimpan data.";
                        }
                    }
                }
            }
        }

        $page_title = "Tambah Barang";
        include __DIR__ . '/../views/header.php';
        include __DIR__ . '/../views/tambah.php';
        include __DIR__ . '/../views/footer.php';
    }

    // Mengedit barang yang sudah ada
    public function edit() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: index.php?page=data_barang');
            exit();
        }

        include_once __DIR__ . '/../models/Barang.php';
        $barangModel = new Barang($this->db);
        $barang = $barangModel->getById($id);

        if (!$barang) {
            header('Location: index.php?page=data_barang');
            exit();
        }

        // Ambil data untuk form
        $kode_barang   = $barang['kode_barang'];
        $nama_barang   = $barang['nama_barang'];
        $kategori      = $barang['kategori'];
        $jumlah        = $barang['jumlah'];
        $harga         = $barang['harga'];
        $tanggal_masuk = $barang['tanggal_masuk'];
        $gambar_lama   = $barang['gambar'];
        $errors        = [];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nama_barang   = trim($_POST['nama_barang'] ?? '');
            $kategori      = trim($_POST['kategori'] ?? '');
            $jumlah        = $_POST['jumlah'] ?? '';
            $harga         = $_POST['harga'] ?? '';
            $tanggal_masuk = $_POST['tanggal_masuk'] ?? '';

            // Validasi
            if (empty($nama_barang)) {
                $errors[] = "Nama barang tidak boleh kosong.";
            }
            if (empty($kategori)) {
                $errors[] = "Silakan pilih kategori.";
            }
            if (!is_numeric($jumlah) || $jumlah < 0) {
                $errors[] = "Jumlah stok harus berupa angka positif.";
            }
            if (!is_numeric($harga) || $harga < 0) {
                $errors[] = "Harga harus berupa angka positif.";
            }
            if (empty($tanggal_masuk)) {
                $errors[] = "Tanggal masuk harus diisi.";
            }

            if (empty($errors)) {
                $gambar = $gambar_lama; // Default menggunakan gambar lama

                // Proses unggah gambar baru jika diunggah
                if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
                    $file_tmp_path = $_FILES['gambar']['tmp_name'];
                    $file_name = $_FILES['gambar']['name'];
                    $file_size = $_FILES['gambar']['size'];
                    
                    $allowed_extensions = ['jpg', 'jpeg', 'png', 'webp'];
                    $file_info = pathinfo($file_name);
                    $file_ext = strtolower($file_info['extension']);
                    
                    if (!in_array($file_ext, $allowed_extensions)) {
                        $errors[] = "Tipe file tidak valid. Hanya JPG, PNG, dan WEBP yang diizinkan.";
                    }
                    if ($file_size > 2 * 1024 * 1024) {
                        $errors[] = "Ukuran file terlalu besar. Maksimal 2MB.";
                    }

                    if (empty($errors)) {
                        $clean_name = preg_replace("/[^a-zA-Z0-9.]/", "_", $file_info['filename']);
                        $nama_file_baru = uniqid() . "_" . $clean_name . "." . $file_ext;

                        $target_dir = "../assets/img/";
                        if (move_uploaded_file($file_tmp_path, $target_dir . $nama_file_baru)) {
                            // Hapus gambar lama agar menghemat ruang disk
                            if (!empty($gambar_lama) && file_exists($target_dir . $gambar_lama)) {
                                unlink($target_dir . $gambar_lama);
                            }
                            $gambar = $nama_file_baru;
                        } else {
                            $errors[] = "Gagal memindahkan berkas gambar baru.";
                        }
                    }
                }

                // Update data di database
                if (empty($errors)) {
                    $updateData = [
                        'nama_barang'   => $nama_barang,
                        'kategori'      => $kategori,
                        'jumlah'        => $jumlah,
                        'tanggal_masuk' => $tanggal_masuk,
                        'harga'         => $harga,
                        'gambar'        => $gambar
                    ];

                    if ($barangModel->update($id, $updateData)) {
                        $_SESSION['pesan'] = "Data barang berhasil diperbarui!";
                        $_SESSION['tipe']  = "success";
                        header('Location: index.php?page=data_barang');
                        exit();
                    } else {
                        $errors[] = "Gagal memperbarui data ke database.";
                    }
                }
            }
        }

        $page_title = "Edit Barang";
        include __DIR__ . '/../views/header.php';
        include __DIR__ . '/../views/edit.php';
        include __DIR__ . '/../views/footer.php';
    }

    // Menghapus data barang
    public function hapus() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: index.php?page=data_barang');
            exit();
        }

        include_once __DIR__ . '/../models/Barang.php';
        $barangModel = new Barang($this->db);
        $barang = $barangModel->getById($id);

        if ($barang) {
            // Hapus gambar produk di folder fisik
            if (!empty($barang['gambar'])) {
                $target_file = "../assets/img/" . $barang['gambar'];
                if (file_exists($target_file)) {
                    unlink($target_file);
                }
            }

            // Hapus baris data di database
            if ($barangModel->delete($id)) {
                $_SESSION['pesan'] = "Barang berhasil dihapus!";
                $_SESSION['tipe']  = "success";
            } else {
                $_SESSION['pesan'] = "Gagal menghapus barang.";
                $_SESSION['tipe']  = "danger";
            }
        }

        header('Location: index.php?page=data_barang');
        exit();
    }
}