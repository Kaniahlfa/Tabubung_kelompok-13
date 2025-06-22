<?php
// Controller/Controller.class.php
class Controller {
    function model($model) {
        // Path disesuaikan dari root proyek
        $modelBaseFile = __DIR__ . "/../model/Model.class.php";
        $modelSpecificFile = __DIR__ . "/../model/" . $model . ".class.php";

        // Model.class.php harus selalu ada
        if (file_exists($modelBaseFile)) {
            require_once($modelBaseFile);
        } else {
            error_log("Base model file tidak ditemukan: " . $modelBaseFile);
            throw new Exception("Fatal Error: Base model file not found at " . $modelBaseFile);
        }

        if (file_exists($modelSpecificFile)) {
            require_once($modelSpecificFile);
            if (class_exists($model)) {
                return new $model;
            } else {
                error_log("Class $model tidak ditemukan di $modelSpecificFile");
                return null;
            }
        } else {
            error_log("File model spesifik tidak ditemukan: " . $modelSpecificFile);
            return null;
        }
    }

    function view($viewPath, $data = []) {
        foreach($data as $key => $value) {
            $$key = $value;
        }
        // Path disesuaikan dari root proyek
        $viewFile = __DIR__ . "/../view/" . $viewPath;
        if (file_exists($viewFile)) {
            include($viewFile);
        } else {
            error_log("File view tidak ditemukan: " . $viewFile);
            echo "Error: Tampilan '$viewPath' tidak ditemukan.";
        }
    }
}
?>