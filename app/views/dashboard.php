<?php global $pdo; ?>
<div class="content-wrapper">
    <?php include 'menu.php'; ?>
    
    <main class="main-content">
        <div class="page-header">
            <h2>Dashboard</h2>
        </div>
        
        <div class="content">
            <div class="row mt-4">

                <div class="col-md-3 mb-4">
                    <div class="stat-card">
                        <div class="stat-icon" style="background-color:#FF9800;">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="stat-info">
                            <h3>Stok &lt; 10</h3>
                            <p class="stat-number"><?php echo htmlspecialchars($stok_rendah); ?></p>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-4">
                    <div class="stat-card">
                        <div class="stat-icon" style="background-color:#2196F3;">
                            <i class="fas fa-box"></i>
                        </div>
                        <div class="stat-info">
                            <h3>Total Barang</h3>
                            <p class="stat-number"><?php echo htmlspecialchars($total_barang); ?></p>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-4">
                    <div class="stat-card">
                        <div class="stat-icon" style="background-color:#4CAF50;">
                            <i class="fas fa-warehouse"></i>
                        </div>
                        <div class="stat-info">
                            <h3>Total Stok</h3>
                            <p class="stat-number"><?php echo htmlspecialchars($total_stok); ?></p>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-4">
                    <div class="stat-card">
                        <div class="stat-icon" style="background-color:#9C27B0;">
                            <i class="fas fa-coins"></i>
                        </div>
                        <div class="stat-info">
                            <h3>Nilai Inventory</h3>
                            <p class="stat-number">
                                Rp <?php echo number_format($total_nilai, 0, ',', '.'); ?>
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>
</div>