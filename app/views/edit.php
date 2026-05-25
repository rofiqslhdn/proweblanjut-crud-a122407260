<?php global $pdo; ?>
<div class="content-wrapper">
    <?php include 'menu.php'; ?>

    <main class="main-content">
        <div class="page-header">
            <h2>Edit Barang: <?php echo htmlspecialchars($kode_barang); ?></h2>
        </div>
        
        <div class="content">
            <div class="card">
                <div class="card-header">
                    <h3>Form Edit Barang</h3>
                    <a href="index.php?page=data_barang" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
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

                    <form action="index.php?page=edit&id=<?php echo $id; ?>" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label>Kode Barang (Tidak dapat diubah)</label>
                            <input type="text" value="<?php echo htmlspecialchars($kode_barang); ?>" disabled>
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
                                <?php if (!empty($gambar_lama) && file_exists("../assets/img/" . $gambar_lama)): ?>
                                    <img src="../assets/img/<?php echo $gambar_lama; ?>" class="img-preview" alt="Preview">
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