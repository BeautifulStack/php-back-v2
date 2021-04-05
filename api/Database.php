<?php

require_once "config/config.php";

class Database
{
    public PDO $conn;

    public function __construct()
    {
        try {
            $this->conn = new PDO("mysql:host=" . AUTH_DB_HOST . ";port=" . AUTH_DB_PORT . ";dbname=" . AUTH_DB_NAME . ";charset=utf8",
                AUTH_DB_USER,
                AUTH_DB_PASS,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        } catch (PDOException $e) {
            // Database connection error
            header("Content-Type: application/json");
            echo json_encode(array("errors" => [
                    $e->getMessage()
                ])
            );

            exit();
        }
    }
}