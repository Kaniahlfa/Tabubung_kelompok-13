<?php
class Controller {
    public function model($model) {
        $modelFile = 'model/' . $model . '.class.php';
        if (file_exists($modelFile)) {
            require_once $modelFile;
            return new $model;
        } else {
            die("Model $model tidak ditemukan.");
        }
    }

    public function view($view, $data = []) {
        extract($data); // agar elemen data bisa digunakan sebagai variabel
        require 'view/' . $view;
    }
}
