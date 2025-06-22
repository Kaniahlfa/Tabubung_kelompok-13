<?php

class BudgetModel extends Model {
    private $db;

    public function __construct() {
        $servername = "localhost";
        $username = "root";
        $password = ""; 
        $dbname = "aplikasi_budget";

        $this->db = @new mysqli($servername, $username, $password, $dbname);

        if ($this->db->connect_error) {
            error_log("Koneksi DB GAGAL di BudgetModel: " . $this->db->connect_error);
            $this->db = null;
        }
    }

    private function checkConnection() {
        if ($this->db === null) {
            error_log("Database connection is not established.");
            return false;
        }
        return true;
    }

    public function create($data) {
        if (!$this->checkConnection()) return false;

        $sql = "INSERT INTO budgets (category, amount, period, account, repeat_budget) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);

        if ($stmt === false) {
            error_log("BudgetModel Prepare create error: " . $this->db->error);
            return false;
        }

        $category = isset($data['category']) ? $data['category'] : 'Lainnya';
        $amount = isset($data['amount']) ? (float)$data['amount'] : 0;
        $period = isset($data['period']) ? $data['period'] : 'Tidak Ada';
        $account = isset($data['account']) ? $data['account'] : 'Default';
        $repeat_int = isset($data['repeat']) && $data['repeat'] ? 1 : 0;

        $stmt->bind_param("sdssi", $category, $amount, $period, $account, $repeat_int);

        if ($stmt->execute()) {
            $stmt->close();
            return true;
        } else {
            error_log("BudgetModel Execute create error: " . $stmt->error);
            $stmt->close();
            return false;
        }
    }

    // Mengembalikan method ini untuk melibatkan tabel expenses
    public function getAllBudgets() {
        if (!$this->checkConnection()) return [];
        $budgets = [];
        $sql = "SELECT b.id, b.category, b.amount, b.period, b.account, b.repeat_budget, b.created_at,
                       COALESCE(SUM(e.amount), 0) as total_spent
                FROM budgets b
                LEFT JOIN expenses e ON b.id = e.budget_id
                GROUP BY b.id
                ORDER BY b.created_at DESC";
        $result = $this->db->query($sql);
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $budgets[] = $row;
            }
            $result->free();
        } else {
            error_log("BudgetModel getAllBudgets query error: " . $this->db->error);
        }
        return $budgets;
    }

    // Mengembalikan method untuk menambahkan pengeluaran
    public function addExpense($budget_id, $amount, $description = null) {
        if (!$this->checkConnection()) return false;
        $sql = "INSERT INTO expenses (budget_id, amount, description) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        if ($stmt === false) {
            error_log("BudgetModel Prepare addExpense error: " . $this->db->error);
            return false;
        }
        $stmt->bind_param("ids", $budget_id, $amount, $description);
        if ($stmt->execute()) {
            $stmt->close();
            return true;
        } else {
            error_log("BudgetModel Execute addExpense error: " . $stmt->error);
            $stmt->close();
            return false;
        }
    }

    // Mengembalikan method untuk mendapatkan total sisa uang dari seluruh budget
    public function getTotalRemainingBudget() {
        if (!$this->checkConnection()) return 0;
        $sql = "SELECT SUM(b.amount - COALESCE(e.total_spent, 0)) as total_remaining
                FROM budgets b
                LEFT JOIN (SELECT budget_id, SUM(amount) as total_spent FROM expenses GROUP BY budget_id) e
                ON b.id = e.budget_id";
        $result = $this->db->query($sql);
        if ($result) {
            if ($row = $result->fetch_assoc()) {
                return (float)$row['total_remaining'];
            }
            $result->free();
        } else {
            error_log("BudgetModel getTotalRemainingBudget query error: " . $this->db->error);
        }
        return 0;
    }

    // Method untuk menghapus budget berdasarkan ID (akan menghapus expenses terkait karena FOREIGN KEY)
    public function deleteBudget($id) {
        if (!$this->checkConnection()) return false;
        $sql = "DELETE FROM budgets WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        if ($stmt === false) {
            error_log("BudgetModel Prepare deleteBudget error: " . $this->db->error);
            return false;
        }
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $stmt->close();
            return true;
        } else {
            error_log("BudgetModel Execute deleteBudget error: " . $stmt->error);
            $stmt->close();
            return false;
        }
    }

    public function __destruct() {
        if ($this->db) {
            $this->db->close();
        }
    }
}
?>