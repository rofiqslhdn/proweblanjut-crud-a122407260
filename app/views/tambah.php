<?php global $pdo; ?>
<div class="content-wrapper">
    <?php include 'menu.php'; ?>

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

                    <form action="index.php?page=tambah" method="POST" enctype="multipart/form-data">
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