<?php
class Model {
    protected $conn;

    public function __construct() {
        $host = 'localhost';
        $user = 'root';
        $pass = ''; 
        $dbname = 'finance_app';

        $this->conn = new mysqli($host, $user, $pass, $dbname);

        if ($this->conn->connect_error) {
            die('Koneksi gagal: ' . $this->conn->connect_error);
        }
    }
}
