<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

try {
    $conn = new PDO(
        "mysql:host=localhost;dbname=restaurant;charset=utf8",
        "root",
        ""
    );
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur connexion DB : " . $e->getMessage());
}
