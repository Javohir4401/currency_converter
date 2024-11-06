<?php

require 'DB.php';

class WorkDay {

    const required_work_hour_daily = 8;

    
// Foydalanuvchining so'nggi 10 kun ichidagi jami ish qarzdorligini hisoblaydi
function calculateDebtLast10Days($pdo, $name) {
    $required_seconds = required_work_hour_daily * 3600;
    
    $select_query = "
        SELECT SUM(GREATEST(0, :required_of - (TIMESTAMPDIFF(SECOND, arrived_at, left_at)))) AS total_debt 
        FROM daily 
        WHERE name = :name AND arrived_at >= DATE_SUB(CURDATE(), INTERVAL 10 DAY)";
    
    $stmt = $pdo->prepare($select_query);
    $stmt->bindParam(":name", $name);
    $stmt->bindParam(":required_of", $required_seconds, PDO::PARAM_INT);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    return $result['total_debt'] ?? 0;
    
    public $pdo;

    public function __construct() {
        $db = new DB();
        $this->pdo = $db->pdo;
    }

    public function store(string $name, string $arrived_at, string $left_at) {
        $arrived_at = new DateTime($arrived_at);
        $left_at = new DateTime($left_at);

        $diff = $arrived_at->diff($left_at);
        $hour = $diff->h;
        $minute = $diff->i;
        $second = $diff->s;
        $worked_seconds = ($hour * 3600) - ($minute * 60) + $second;

        $required_seconds = required_work_hour_daily * 3600;
        $total = $required_seconds - $worked_seconds;

        $insertQuery = "INSERT INTO time(name, arrived_at, left_at, worked_seconds, required_of)  
                        VALUES (:name, :arrived_at, :left_at, :worked_seconds, :required_of)";

        $stmt = $pdo->prepare($insertQuery);

        $stmt->bindParam(":name", $name);
        $stmt->bindValue(":arrived_at", $arrived_at->format("Y-m-d H:i"));
        $stmt->bindValue(":left_at", $left_at->format("Y-m-d H:i"));
        $stmt->bindParam(":worked_seconds", $worked_seconds);
        $stmt->bindParam(":required_of", $required_seconds);
        $stmt->execute();
        header('Location: asosiy.php');
    }


    public function getWorkDayList() {
        $select_query = "SELECT * FROM time";
        $next_stmt = $this->pdo->query($select_query);
        return $next_stmt->fetchAll();
    }
}

