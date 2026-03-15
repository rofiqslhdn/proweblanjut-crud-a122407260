<?php
include 'koneksi.php';

$page_title = "Edit Barang";

// ambil id dari url
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// ambil data barang
$stmt = $pdo->prepare("SELECT * FROM barang WHERE id = ?");
$stmt->execute([$id]);
$barang = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$barang) {

    $_SESSION['pesan'] = "Barang tidak ditemukan!";
    $_SESSION['tipe']  = "error";

    header("Location: index.php?page=data_barang");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $nama_barang   = $_POST['nama_barang'];
    $kategori      = $_POST['kategori'];
    $jumlah        = $_POST['jumlah'];
    $harga         = $_POST['harga'];
    $tanggal_masuk = $_POST['tanggal_masuk'];

    $query = "
        UPDATE barang SET
            nama_barang   = ?,
            kategori      = ?,
            jumlah        = ?,
            tanggal_masuk = ?,
            harga         = ?
        WHERE id = ?
    ";

    $stmt = $pdo->prepare($query);

    if ($stmt->execute([
        $nama_barang,
        $kategori,
        $jumlah,
        $tanggal_masuk,
        $harga,
        $id
    ])) {

        // pesan sukses dihapus
        header("Location: index.php?page=data_barang");
        exit();
    } else {

        $_SESSION['pesan'] = "Gagal memperbarui barang!";
        $_SESSION['tipe']  = "error";
    }
}
?>

<?php include 'includes/header.php'; ?>

<div class="content-wrapper">

    <?php include 'includes/menu.php'; ?>

    <main class="main-content">
        <div class="page-header">
            <h2>Edit Barang Gaming</h2>
        </div>
        <div class="content">
            <div class="card">
                <div class="card-header">
                    <h3>Edit Data Barang</h3>

                    <a href="index.php?page=data_barang" class="btn btn-secondary">
                        Kembali
                    </a>
                </div>

                <div class="card-body">

                    <form method="POST">

                        <div class="form-group">
                            <label>Kode Barang</label>

                            <input
                                type="text"
                                value="<?php echo htmlspecialchars($barang['kode_barang']); ?>"
                                disabled>
                        </div>

                        <div class="form-group">
                            <label>Nama Barang</label>

                            <input
                                type="text"
                                name="nama_barang"
                                value="<?php echo htmlspecialchars($barang['nama_barang']); ?>"
                                required>
                        </div>

                        <div class="form-group">
                            <label>Kategori</label>

                            <select name="kategori" required>

                                <option value="Console"
                                    <?php echo $barang['kategori'] == 'Console' ? 'selected' : ''; ?>>
                                    Console
                                </option>

                                <option value="Game"
                                    <?php echo $barang['kategori'] == 'Game' ? 'selected' : ''; ?>>
                                    Game
                                </option>

                                <option value="Aksesoris"
                                    <?php echo $barang['kategori'] == 'Aksesoris' ? 'selected' : ''; ?>>
                                    Aksesoris
                                </option>

                            </select>
                        </div>

                        <div class="form-group">
                            <label>Jumlah Stok</label>

                            <input
                                type="number"
                                name="jumlah"
                                min="0"
                                value="<?php echo $barang['jumlah']; ?>"
                                required>
                        </div>

                        <div class="form-group">
                            <label>Tanggal Masuk</label>

                            <input
                                type="date"
                                name="tanggal_masuk"
                                value="<?php echo $barang['tanggal_masuk']; ?>"
                                required>
                        </div>

                        <div class="form-group">
                            <label>Harga</label>

                            <input
                                type="number"
                                name="harga"
                                min="0"
                                value="<?php echo $barang['harga']; ?>"
                                required>
                        </div>

                        <div class="form-actions">

                            <button type="submit" class="btn btn-primary">
                                Simpan Perubahan
                            </button>

                            <button type="reset" class="btn btn-secondary">
                                Reset
                            </button>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
</div>

<?php include 'includes/footer.php'; ?>