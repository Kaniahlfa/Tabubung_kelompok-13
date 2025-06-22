<?php
require_once 'Model.class.php';

class TransactionModel extends Model {

    public function getAllTransactions() {
        $data = [];

        // Ambil data income
        $incomeQuery = "SELECT tanggal, kategori, metode, jumlah FROM income";
        $incomeResult = $this->conn->query($incomeQuery);
        while ($row = $incomeResult->fetch_assoc()) {
            $row['tipe'] = 'Income';
            $data[] = $row;
        }

        // Ambil data spending
        $spendingQuery = "SELECT tanggal, kategori, metode, jumlah FROM spending";
        $spendingResult = $this->conn->query($spendingQuery);
        while ($row = $spendingResult->fetch_assoc()) {
            $row['tipe'] = 'Spending';
            $data[] = $row;
        }

        // Urutkan berdasarkan tanggal descending
        usort($data, function($a, $b) {
            return strtotime($b['tanggal']) - strtotime($a['tanggal']);
        });

        return $data;
    }

    public function deleteAllData() {
        // Hapus semua data dari tabel income dan spending
        $this->conn->query("DELETE FROM income");
        $this->conn->query("DELETE FROM spending");
    }

}
