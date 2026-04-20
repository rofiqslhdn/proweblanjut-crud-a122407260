<?php
include 'koneksi.php';

$page_title = "Tambah Barang";
$errors = [];

// Inisialisasi variabel untuk sticky fields
$kode_barang   = '';
$nama_barang   = '';
$kategori      = '';
$jumlah        = '';
$harga         = '';
$tanggal_masuk = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $kode_barang   = trim($_POST['kode_barang'] ?? '');
    $nama_barang   = $_POST['nama_barang'] ?? '';
    $kategori      = $_POST['kategori'] ?? '';
    $jumlah        = $_POST['jumlah'] ?? '';
    $harga         = $_POST['harga'] ?? '';
    $tanggal_masuk = $_POST['tanggal_masuk'] ?? '';

    // Validasi Server
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

    // Jika Validasi Lolos
    if (empty($errors)) {
        
        // Generate kode barang otomatis jika kosong
        if (empty($kode_barang)) {
            $prefix = "GM";
            // Prepared Statement untuk mengambil kode terbesar
            $stmt = $pdo->prepare("SELECT MAX(SUBSTRING(kode_barang,3)) as max_code FROM barang WHERE kode_barang LIKE 'GM%'");
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $next = ($row['max_code'] ?? 0) + 1;
            $kode_barang = $prefix . str_pad($next, 3, "0", STR_PAD_LEFT);
        }

        // Cek apakah kode barang sudah ada (Prepared Statement)
        $stmt = $pdo->prepare("SELECT id FROM barang WHERE kode_barang = ?");
        $stmt->execute([$kode_barang]);

        if ($stmt->rowCount() > 0) {
            $errors[] = "Kode barang sudah terdaftar di sistem!";
        } else {
            // Proses Upload Gambar
            $gambar = "";
            if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
                $file_tmp_path = $_FILES['gambar']['tmp_name'];
                $file_name = $_FILES['gambar']['name'];
                $file_size = $_FILES['gambar']['size'];
                
                // Validasi tipe file dan ukuran (Contoh: Max 2MB)
                $allowed_extensions = ['jpg', 'jpeg', 'png', 'webp'];
                $file_info = pathinfo($file_name);
                $file_ext = strtolower($file_info['extension']);
                
                if (!in_array($file_ext, $allowed_extensions)) {
                    $errors[] = "Tipe file tidak valid. Hanya JPG, PNG, dan WEBP yang diizinkan.";
                }
                
                if ($file_size > 2 * 1024 * 1024) { // Batas 2MB
                    $errors[] = "Ukuran file terlalu besar. Maksimal adalah 2MB.";
                }

                // Jika tidak ada error pada file, lanjutkan pemindahan
                if (empty($errors)) {
                    // Buat nama file yang unik (uniqid + nama asli yang dibersihkan)
                    $clean_name = preg_replace("/[^a-zA-Z0-9.]/", "_", $file_info['filename']);
                    $gambar = uniqid() . "_" . $clean_name . "." . $file_ext;

                    // Pindahkan file ke direktori permanen
                    $target_dir = "assets/img/";
                    if (!move_uploaded_file($file_tmp_path, $target_dir . $gambar)) {
                        $errors[] = "Gagal memindahkan file ke folder tujuan.";
                    }
                }
            }

            // Simpan ke Database (Prepared Statement)
            if (empty($errors)) {
                $query = "INSERT INTO barang 
                        (kode_barang, nama_barang, kategori, jumlah, tanggal_masuk, harga, gambar)
                        VALUES (?, ?, ?, ?, ?, ?, ?)";

                $stmt = $pdo->prepare($query);

                if ($stmt->execute([$kode_barang, $nama_barang, $kategori, $jumlah, $tanggal_masuk, $harga, $gambar])) {
                    header("Location: index.php?page=data_barang");
                    exit();
                } else {
                    $errors[] = "Terjadi kesalahan sistem saat menyimpan data.";
                }
            }
        }
    }
}
?>

<?php include 'includes/header.php'; ?>

<div class="content-wrapper">
    <?php include 'includes/menu.php'; ?>

    <main class="main-content">
        <div class="page-header">
            <h2>Tambah Barang Gaming</h2>
        </div>

        <div class="content">
            <div class="card">
                <div class="card-header">
                    <h3>Form Tambah Barang</h3>
                    <a href="index.php?page=data_barang" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>

                <div class="card-body">
                    <?php if (!empty($errors)): ?>
                        <div class="error-msg">
                            <ul>
                                <?php foreach ($errors as $error): ?>
                                    <li><?php echo htmlspecialchars($error); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label>Kode Barang</label>
                            <input type="text" name="kode_barang" value="<?php echo htmlspecialchars($kode_barang); ?>" placeholder="Kosongkan untuk otomatis (GM001)">
                        </div>

                        <div class="form-group">
                            <label>Nama Barang</label>
                            <input type="text" name="nama_barang" value="<?php echo htmlspecialchars($nama_barang); ?>" required>
                        </div>

                        <div class="form-group">
                            <label>Kategori</label>
                            <select name="kategori" required>
                                <option value="">Pilih Kategori</option>
                                <option value="Laptop" <?php echo ($kategori == 'Laptop') ? 'selected' : ''; ?>>Laptop</option>
                                <option value="Console" <?php echo ($kategori == 'Console') ? 'selected' : ''; ?>>Console</option>
                                <option value="Game" <?php echo ($kategori == 'Game') ? 'selected' : ''; ?>>Game</option>
                                <option value="Aksesoris" <?php echo ($kategori == 'Aksesoris') ? 'selected' : ''; ?>>Aksesoris</option>
                                <option value="Monitor" <?php echo ($kategori == 'Monitor') ? 'selected' : ''; ?>>Monitor</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Jumlah Stok</label>
                            <input type="number" name="jumlah" min="0" value="<?php echo htmlspecialchars($jumlah); ?>" required>
                        </div>

                        <div class="form-group">
                            <label>Tanggal Masuk</label>
                            <input type="date" name="tanggal_masuk" value="<?php echo htmlspecialchars($tanggal_masuk); ?>" required>
                        </div>

                        <div class="form-group">
                            <label>Harga (Rp)</label>
                            <input type="number" name="harga" min="0" value="<?php echo htmlspecialchars($harga); ?>" required>
                        </div>

                        <div class="form-group">
                            <label>Foto Produk</label>
                            <input type="file" name="gambar" accept="image/*">
                            <span class="help-text">Format didukung: JPG, PNG, WEBP.</span>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan Barang
                            </button>
                            <button type="reset" class="btn btn-secondary">Reset</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
</div>

<?php include 'includes/footer.php'; ?>