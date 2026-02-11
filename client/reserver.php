<?php
include '../includes/config.php';
include '../includes/auth.php';

// 🔐 Sécurité session
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'client') {
    header("Location: ../login.php");
    exit;
}

// 🔎 Vérification POST
if (
    empty($_POST['menu_id']) ||
    empty($_POST['quantity']) ||
    empty($_POST['adresse'])
) {
    die("❌ Données POST manquantes");
}

$user_id = (int) $_SESSION['user']['id'];
$menu_id = (int) $_POST['menu_id'];
$quantity = (int) $_POST['quantity'];
$adresse = trim($_POST['adresse']);

if ($menu_id <= 0 || $quantity <= 0 || strlen($adresse) < 5) {
    die("❌ Données invalides");
}

try {
 
    $stmt = $conn->prepare("
    INSERT INTO reservations (user_id, menu_id, quantity, adresse_livraison, statut)
    VALUES (?, ?, ?, ?, 'En attente')
");

$stmt->execute([$user_id, $menu_id, $quantity, $adresse]);


} catch (PDOException $e) {
    die("❌ ERREUR SQL : " . $e->getMessage());
}

// ✅ Succès
header("Location: dashboard.php?success=1");
exit;
