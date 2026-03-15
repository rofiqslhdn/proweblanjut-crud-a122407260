<?php
include 'koneksi.php';

$page_title = "Tambah Barang";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $kode_barang   = $_POST['kode_barang'];
    $nama_barang   = $_POST['nama_barang'];
    $kategori      = $_POST['kategori'];
    $jumlah        = $_POST['jumlah'];
    $harga         = $_POST['harga'];
    $tanggal_masuk = $_POST['tanggal_masuk'];

    // Generate kode barang otomatis jika kosong
    if (empty($kode_barang)) {
        $prefix = "GM";

        $stmt = $pdo->prepare("SELECT MAX(SUBSTRING(kode_barang,3)) as max_code FROM barang WHERE kode_barang LIKE 'GM%'");
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $next = ($row['max_code'] ?? 0) + 1;
        $kode_barang = $prefix . str_pad($next, 3, "0", STR_PAD_LEFT);
    }

    // cek kode barang
    $stmt = $pdo->prepare("SELECT id FROM barang WHERE kode_barang = ?");
    $stmt->execute([$kode_barang]);

    if ($stmt->rowCount() > 0) {

        $_SESSION['pesan'] = "Kode barang sudah ada!";
        $_SESSION['tipe'] = "error";

    } else {

        $query = "INSERT INTO barang 
                (kode_barang,nama_barang,kategori,jumlah,tanggal_masuk,harga)
                VALUES (?,?,?,?,?,?)";

        $stmt = $pdo->prepare($query);

        if ($stmt->execute([$kode_barang, $nama_barang, $kategori, $jumlah, $tanggal_masuk, $harga])) {

            // pesan sukses dihapus
            header("Location: index.php?page=data_barang");
            exit();

        } else {

            $_SESSION['pesan'] = "Gagal menambahkan barang";
            $_SESSION['tipe'] = "error";
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

            <div class="card">
                <div class="card-body">
                    <form method="POST">
                        <div class="form-group">
                            <label>Kode Barang</label>
                            <input type="text" name="kode_barang" placeholder="Kosongkan untuk otomatis (GM001)">
                        </div>

                        <div class="form-group">
                            <label>Nama Barang</label>
                            <input type="text" name="nama_barang" required>
                        </div>

                        <div class="form-group">
                            <label>Kategori</label>
                            <select name="kategori" required>
                                <option value="">Pilih Kategori</option>
                                <option value="Console">Console</option>
                                <option value="Game">Game</option>
                                <option value="Aksesoris">Aksesoris</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Jumlah Stok</label>
                            <input type="number" name="jumlah" min="0" required>
                        </div>

                        <div class="form-group">
                            <label>Tanggal Masuk</label>
                            <input type="date" name="tanggal_masuk" required>
                        </div>

                        <div class="form-group">
                            <label>Harga</label>
                            <input type="number" name="harga" min="0" required>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href="index.php?page=data_barang" class="btn btn-secondary">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </main>
</div>

<?php include 'includes/footer.php'; ?>