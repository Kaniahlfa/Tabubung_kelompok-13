<?php
require_once 'Model.class.php';

class SpendingModel extends Model {
    public function insertSpending($tanggal, $jumlah, $kategori, $metode, $catatan) {
        $stmt = $this->conn->prepare("INSERT INTO spending (tanggal, jumlah, kategori, metode, catatan) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sdsss", $tanggal, $jumlah, $kategori, $metode, $catatan);
        return $stmt->execute();
    }
}
