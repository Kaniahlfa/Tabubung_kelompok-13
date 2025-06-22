<?php
class BudgetController extends Controller {

    // Menampilkan daftar budget (dashboard)
    public function index() {
        $budgetModel = $this->model('BudgetModel');
        $allBudgets = [];
        $totalRemainingBudget = 0; // Mengembalikan inisialisasi
        $error = null;

        if ($budgetModel) {
            $allBudgets = $budgetModel->getAllBudgets(); // Memanggil getAllBudgets yang sudah join expenses
            $totalRemainingBudget = $budgetModel->getTotalRemainingBudget(); // Mengembalikan panggilan ini
        } else {
            $error = 'Gagal memuat Budget Model. Periksa log server.';
        }
        $this->view('index.php', [
            'budgets' => $allBudgets,
            'pageTitle' => 'Dashboard Budget',
            'error' => $error,
            'totalRemainingBudget' => $totalRemainingBudget // Meneruskan nilai yang dihitung
        ]);
    }

    // Menampilkan form untuk menambah budget baru
    public function add() {
        $this->view('add-budget.php', ['pageTitle' => 'Tambah Budget Baru']);
    }

    // Menyimpan budget baru dari data POST (JSON)
    public function store() {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['status' => 'error', 'message' => 'Metode tidak diizinkan. Hanya POST yang diterima.']);
            return;
        }

        $contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
        if (strpos(strtolower($contentType), 'application/json') === false) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Content-Type harus application/json.']);
            return;
        }

        $content = trim(file_get_contents("php://input"));
        $data = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Data JSON tidak valid. Error: ' . json_last_error_msg()]);
            return;
        }

        if (empty($data['category']) || !isset($data['amount']) || !is_numeric($data['amount']) || (float)$data['amount'] <= 0) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Data tidak lengkap. Kategori dan Jumlah (>0) wajib.']);
            return;
        }

        $budgetModel = $this->model('BudgetModel');
        if (!$budgetModel) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Gagal memuat Budget Model (internal server error).']);
            return;
        }

        $success = $budgetModel->create($data);
        if ($success) {
            echo json_encode(['status' => 'success', 'message' => 'Budget berhasil disimpan!']);
        } else {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan budget ke database. Periksa log server.']);
        }
    }

    // Method untuk menampilkan halaman pilih kategori
    public function selectCategory() {
        $existingCategories = [
            ['icon' => 'bi-cup-hot-fill', 'name' => 'Makanan & Minuman', 'id' => 'food'],
            ['icon' => 'bi-bag-heart-fill', 'name' => 'Belanja Pribadi', 'id' => 'personal_items'],
            ['icon' => 'bi-house-heart-fill', 'name' => 'Layanan Rumah', 'id' => 'home_service'],
            ['icon' => 'bi-truck-front-fill', 'name' => 'Transportasi', 'id' => 'transportation'],
            ['icon' => 'bi-cart-fill', 'name' => 'Belanja Umum', 'id' => 'shopping'],
            ['icon' => 'bi-book-fill', 'name' => 'Pendidikan', 'id' => 'education'],
            ['icon' => 'bi-film', 'name' => 'Hiburan', 'id' => 'entertainment']
        ];

        $dataForView = [
            'existingCategoriesData' => $existingCategories,
            'addBudgetPageUrl' => BASE_URL . 'budget/add',
            'pageTitle' => 'Pilih atau Buat Kategori'
        ];
        $this->view('select-category.php', $dataForView);
    }

    // Mengembalikan method untuk menambahkan pengeluaran
    public function addExpense() {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['status' => 'error', 'message' => 'Metode tidak diizinkan. Hanya POST yang diterima.']);
            return;
        }
        $contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
        if (strpos(strtolower($contentType), 'application/json') === false) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Content-Type harus application/json.']);
            return;
        }
        $content = trim(file_get_contents("php://input"));
        $data = json_decode($content, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Data JSON tidak valid. Error: ' . json_last_error_msg()]);
            return;
        }

        if (empty($data['budget_id']) || !isset($data['amount']) || !is_numeric($data['amount']) || (float)$data['amount'] <= 0) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'ID Budget dan Jumlah Pengeluaran (>0) wajib.']);
            return;
        }

        $budgetModel = $this->model('BudgetModel');
        if (!$budgetModel) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Gagal memuat Budget Model (internal server error).']);
            return;
        }

        $budget_id = (int)$data['budget_id'];
        $amount = (float)$data['amount'];
        $description = isset($data['description']) ? $data['description'] : null;

        $success = $budgetModel->addExpense($budget_id, $amount, $description);
        if ($success) {
            echo json_encode(['status' => 'success', 'message' => 'Pengeluaran berhasil dicatat!']);
        } else {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Gagal mencatat pengeluaran ke database. Periksa log server.']);
        }
    }

    // Mengembalikan method untuk menghapus budget berdasarkan ID
    public function delete($id = null) {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['status' => 'error', 'message' => 'Metode tidak diizinkan. Hanya POST yang diterima untuk penghapusan.']);
            return;
        }

        if ($id === null) {
            $content = trim(file_get_contents("php://input"));
            $data = json_decode($content, true);
            $id = isset($data['id']) ? (int)$data['id'] : null;
        } else {
            $id = (int)$id;
        }

        if ($id <= 0) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'ID budget tidak valid untuk penghapusan.']);
            return;
        }

        $budgetModel = $this->model('BudgetModel');
        if (!$budgetModel) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Gagal memuat Budget Model (internal server error).']);
            return;
        }

        $success = $budgetModel->deleteBudget($id);
        if ($success) {
            echo json_encode(['status' => 'success', 'message' => 'Budget berhasil dihapus!']);
        } else {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus budget dari database. Periksa log server.']);
        }
    }
}
?>