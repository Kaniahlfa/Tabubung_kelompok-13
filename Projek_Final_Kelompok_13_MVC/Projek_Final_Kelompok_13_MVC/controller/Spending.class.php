<?php
class Spending extends Controller {
    function input() {
        $this->view('spending.php');
    }

    function save() {
        $tanggal = $_POST['tanggal'];
        $jumlah = $_POST['jumlah'];
        $kategori = $_POST['kategori'];
        $metode = $_POST['metode'];
        $catatan = $_POST['catatan'];

        $model = $this->model('SpendingModel');
        $model->insertSpending($tanggal, $jumlah, $kategori, $metode, $catatan);

        header("Location: index.php?c=Transaction&m=index");
    }
}