<?php
require_once 'Model.class.php';

class IncomeModel extends Model {
    public function insertIncome($tanggal, $jumlah, $kategori, $metode, $catatan) {
        $stmt = $this->conn->prepare("INSERT INTO income (tanggal, jumlah, kategori, metode, catatan) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sdsss", $tanggal, $jumlah, $kategori, $metode, $catatan);
        return $stmt->execute();
    }
}
