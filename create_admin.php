<?php
include 'includes/config.php';

$nom = "Administrateur";
$tel = "0000000000";
$email = "admin@restaurant.com";
$password = password_hash("admin123", PASSWORD_DEFAULT);

$stmt = $conn->prepare(
  "INSERT INTO users (nom, telephone, email, password, role)
   VALUES (?, ?, ?, ?, 'admin')"
);
$stmt->execute([$nom, $tel, $email, $password]);

echo "ADMIN CRÉÉ AVEC SUCCÈS";
