<?php
class Transaction extends Controller {
    public function index() {
        $model = $this->model('TransactionModel');
        $data = $model->getAllTransactions(); // mengambil data dari model
        $this->view('transaction.php', ['data' => $data]);
    }
    public function deleteAll() {
    $model = $this->model('TransactionModel');
    $model->deleteAllData();
    header("Location: index.php?c=Transaction&m=index");
    }
}
