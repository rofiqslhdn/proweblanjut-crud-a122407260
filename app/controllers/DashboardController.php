<?php

class DashboardController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function index() {
        include_once __DIR__ . '/../models/Barang.php';
        $barangModel = new Barang($this->db);

        $stok_rendah  = $barangModel->countStokRendah();
        $total_barang = $barangModel->countTotalBarang();
        $total_stok   = $barangModel->sumTotalStok();
        $total_nilai  = $barangModel->sumNilaiInventory();

        $page_title = "Dashboard";
        
        include __DIR__ . '/../views/header.php';
        include __DIR__ . '/../views/dashboard.php';
        include __DIR__ . '/../views/footer.php';
    }
}