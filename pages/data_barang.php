<?php
include 'koneksi.php';

// Menggunakan Prepared Statement untuk mengambil data
$stmt = $pdo->prepare("SELECT * FROM barang ORDER BY id DESC");
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="card">

    <div class="card-header">
        <h3>DATA BARANG GAMING</h3>

        <div class="card-actions">
            <a href="tambah.php" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Barang
            </a>
        </div>
    </div>

    <div class="card-body">

        <div class="table-container">

            <table class="data-table">

                <thead>
                    <tr>
                        <th>No</th>
                        <th>Gambar</th> <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Kategori</th>
                        <th>Stok</th>
                        <th>Tanggal Masuk</th>
                        <th>Harga</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (count($result) > 0): ?>

                        <?php $no = 1;
                        foreach ($result as $row): ?>

                            <tr>

                                <td><?php echo $no++; ?></td>

                                <td class="img-column">
                                    <?php if (!empty($row['gambar']) && file_exists("assets/img/" . $row['gambar'])): ?>
                                        <img src="assets/img/<?php echo $row['gambar']; ?>" 
                                             alt="Produk" 
                                             class="img-thumbnail-custom">
                                    <?php else: ?>
                                        <div class="img-placeholder">
                                            <i class="fas fa-image"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <strong>
                                        <?php echo htmlspecialchars($row['kode_barang']); ?>
                                    </strong>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($row['nama_barang']); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($row['kategori']); ?>
                                </td>

                                <td class="cell-stock">
                                    <span class="stock-pill <?php echo $row['jumlah'] > 10 ? 'is-ok' : ($row['jumlah'] > 0 ? 'is-warn' : 'is-low'); ?>">
                                        <?php echo (int)$row['jumlah']; ?> unit
                                    </span>
                                </td>

                                <td>
                                    <?php echo date('d M Y', strtotime($row['tanggal_masuk'])); ?>
                                </td>

                                <td>
                                    <span class="text-primary">
                                        Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?>
                                    </span>
                                </td>

                                <td class="action-buttons">

                                    <a href="edit.php?id=<?php echo $row['id']; ?>"
                                        class="btn-action btn-edit"
                                        title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <a href="hapus.php?id=<?php echo $row['id']; ?>"
                                        class="btn-action btn-delete"
                                        title="Hapus"
                                        onclick="return confirm('Yakin hapus barang ini?')">
                                        <i class="fas fa-trash"></i>
                                    </a>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>
                        <tr>
                            <td colspan="9" class="text-center">

                                <div class="empty-state">

                                    <i class="fas fa-gamepad fa-3x"></i>

                                    <h4>Belum ada data barang gaming</h4>

                                    <p>Tambahkan console, game, atau aksesoris pertama</p>

                                    <a href="tambah.php" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> Tambah Barang
                                    </a>

                                </div>

                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>