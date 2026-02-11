<?php
include '../includes/config.php';
include '../includes/auth.php';

if ($_SESSION['user']['role'] !== 'admin') {
    exit("Accès interdit");
}

$id = (int) $_POST['id'];
$statut = $_POST['statut'];

$stmt = $conn->prepare("UPDATE reservations SET statut = ? WHERE id = ?");
$stmt->execute([$statut, $id]);

header("Location: reservations.php");
exit;
