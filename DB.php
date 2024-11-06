<?php

class DB{

    public $pdo;

    public function __construct() {
        $dsn = "mysql:host=localhost;dbname=homework";
        $this->pdo = new PDO($dsn, username: "root", password: "1112");
        return$this->pdo;
    }
}
