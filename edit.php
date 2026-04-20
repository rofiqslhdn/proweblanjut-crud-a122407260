<?php
include 'koneksi.php';

$page_title = "Edit Barang";
$id = $_GET['id'] ?? null;
$errors = [];

if (!$id) {
    header("Location: index.php?page=data_barang");
    exit();
}

// Ambil data awal dari database
$stmt = $pdo->prepare("SELECT * FROM barang WHERE id = ?");
$stmt->execute([$id]);
$barang = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$barang) {    
    header("Location: index.php?page=data_barang");
    exit();
}

// Proses jika form dikirim (POST)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form (untuk sticky fields)
    $nama_barang   = $_POST['nama_barang'] ?? '';
    $kategori      = $_POST['kategori'] ?? '';
    $jumlah        = $_POST['jumlah'] ?? '';
    $harga         = $_POST['harga'] ?? '';
    $tanggal_masuk = $_POST['tanggal_masuk'] ?? '';
    
    // Validasi
    if (empty($nama_barang)) {
        $errors[] = "Nama barang tidak boleh kosong.";
    }
    if (!is_numeric($jumlah) || $jumlah < 0) {
        $errors[] = "Jumlah stok harus berupa angka positif.";
    }
    if (!is_numeric($harga) || $harga < 0) {
        $errors[] = "Harga harus berupa angka positif.";
    }

    // Jika validasi lolos
    if (empty($errors)) {
        $gambar = $barang['gambar']; // Default pakai gambar lama

        // --- Logika Upload Gambar ---
        if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
            $file_tmp_path = $_FILES['gambar']['tmp_name'];
            $file_name = $_FILES['gambar']['name'];
            $file_size = $_FILES['gambar']['size'];
            
            // Validasi tipe file (Hanya izinkan jpg, jpeg, png, webp)
            $allowed_extensions = ['jpg', 'png', 'webp'];
            $file_info = pathinfo($file_name);
            $file_ext = strtolower($file_info['extension']);
            
            if (!in_array($file_ext, $allowed_extensions)) {
                $errors[] = "Tipe file tidak valid. Hanya JPG, PNG, dan WEBP yang diizinkan.";
            }
            
            // Validasi ukuran (Misal: Maksimal 2MB)
            if ($file_size > 2 * 1024 * 1024) {
                $errors[] = "Ukuran file terlalu besar. Maksimal 2MB.";
            }

            // Jika tidak ada error pada file, lanjutkan proses
            if (empty($errors)) {
                // Buat nama file yang unik (Kombinasi uniqid dengan nama asli yang dibersihkan)
                $clean_name = preg_replace("/[^a-zA-Z0-9.]/", "_", $file_info['filename']);
                $nama_file_baru = uniqid() . "_" . $clean_name . "." . $file_ext;

                // Pindahkan file ke direktori permanen
                $target_dir = "assets/img/";
                if (move_uploaded_file($file_tmp_path, $target_dir . $nama_file_baru)) {
                    // Hapus file lama jika ada agar tidak memenuhi server
                    if ($barang['gambar'] && file_exists($target_dir . $barang['gambar'])) {
                        unlink($target_dir . $barang['gambar']);
                    }
                    // Simpan nama file unik tersebut untuk database
                    $gambar = $nama_file_baru;
                } else {
                    $errors[] = "Gagal memindahkan file ke folder tujuan.";
                }
            }
        }

        // Update Database
        $query = "UPDATE barang SET 
                    nama_barang = ?, 
                    kategori = ?, 
                    jumlah = ?, 
                    tanggal_masuk = ?, 
                    harga = ?, 
                    gambar = ? 
                  WHERE id = ?";
        
        $stmt = $pdo->prepare($query);
        if ($stmt->execute([$nama_barang, $kategori, $jumlah, $tanggal_masuk, $harga, $gambar, $id])) {
            header("Location: index.php?page=data_barang");
            exit();
        } else {
            $errors[] = "Gagal memperbarui data ke database.";
        }
    }
} else {
    // Jika akses awal (GET), isi variabel dengan data dari DB
    $nama_barang   = $barang['nama_barang'];
    $kategori      = $barang['kategori'];
    $jumlah        = $barang['jumlah'];
    $harga         = $barang['harga'];
    $tanggal_masuk = $barang['tanggal_masuk'];
}
?>

<?php include 'includes/header.php'; ?>

<div class="content-wrapper">

    <?php include 'includes/menu.php'; ?>

    <main class="main-content">
        <div class="page-header">
            <h2>Edit Barang: <?php echo htmlspecialchars($barang['kode_barang']); ?></h2>
        </div>
        
        <div class="content">
            <div class="card">
                <div class="card-body">    
                    <?php if (!empty($errors)): ?>
                        <div class="error-msg">
                            <ul style="margin-left: 20px;">
                                <?php foreach ($errors as $e): ?>
                                    <li><?php echo htmlspecialchars($e); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label>Kode Barang (Tidak dapat diubah)</label>
                            <input type="text" value="<?php echo htmlspecialchars($barang['kode_barang']); ?>" disabled>
                        </div>

                        <div class="form-group">
                            <label>Nama Barang</label>
                            <input type="text" name="nama_barang" value="<?php echo htmlspecialchars($nama_barang); ?>" required>
                        </div>

                        <div class="form-group">
                            <label>Kategori</label>
                            <select name="kategori" required>
                                <option value="Laptop" <?php echo ($kategori == 'Laptop') ? 'selected' : ''; ?>>Laptop</option>
                                <option value="Console" <?php echo ($kategori == 'Console') ? 'selected' : ''; ?>>Console</option>
                                <option value="Game" <?php echo ($kategori == 'Game') ? 'selected' : ''; ?>>Game</option>
                                <option value="Aksesoris" <?php echo ($kategori == 'Aksesoris') ? 'selected' : ''; ?>>Aksesoris</option>
                                <option value="Monitor" <?php echo ($kategori == 'Monitor') ? 'selected' : ''; ?>>Monitor</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Jumlah Stok</label>
                            <input type="number" name="jumlah" value="<?php echo htmlspecialchars($jumlah); ?>" required>
                        </div>

                        <div class="form-group">
                            <label>Harga (Rp)</label>
                            <input type="number" name="harga" value="<?php echo htmlspecialchars($harga); ?>" required>
                        </div>

                        <div class="form-group">
                            <label>Tanggal Masuk</label>
                            <input type="date" name="tanggal_masuk" value="<?php echo htmlspecialchars($tanggal_masuk); ?>" required>
                        </div>

                        <div class="form-group">
                            <label>Foto Produk</label>
                            <div class="image-preview-wrapper">
                                <?php if ($barang['gambar']): ?>
                                    <img src="assets/img/<?php echo $barang['gambar']; ?>" class="img-preview" alt="Preview">
                                <?php else: ?>
                                    <span class="no-image-text">Belum ada foto.</span>
                                <?php endif; ?>
                            </div>
                            <input type="file" name="gambar" accept="image/*">
                            <span class="help-text">Pilih file baru jika ingin mengganti foto.</span>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            <a href="index.php?page=data_barang" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </main>
</div>

<?php include 'includes/footer.php'; ?>