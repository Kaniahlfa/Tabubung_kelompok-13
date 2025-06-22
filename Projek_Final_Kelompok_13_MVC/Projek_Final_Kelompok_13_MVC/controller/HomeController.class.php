<?php
// Controller/HomeController.class.php

class HomeController extends Controller { // Sesuaikan nama kelas menjadi HomeController
    function index() {
        // Arahkan ke BudgetController->index() untuk menampilkan daftar budget
        $budgetController = new BudgetController();
        $budgetController->index();
    }
}
?>