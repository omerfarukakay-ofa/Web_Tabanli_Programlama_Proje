<?php

class Database {
    private $host = "localhost";
    private $user = "root";
    private $pass = "";
    private $name = "lol_tracker";
    public $conn;

    public function __construct() {
        $local = __DIR__ . '/config.local.php';
        if (file_exists($local)) {
            $c = require $local;
            $this->host = $c['host'];
            $this->user = $c['user'];
            $this->pass = $c['pass'];
            $this->name = $c['name'];
        }
        $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->name);
        if ($this->conn->connect_error) {
            die("Baglanti hatasi: " . $this->conn->connect_error);
        }
        $this->conn->set_charset("utf8mb4");
    }

    public function getConnection() {
        return $this->conn;
    }
}
