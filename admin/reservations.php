<?php
include '../includes/config.php';
include '../includes/auth.php';

if ($_SESSION['user']['role'] !== 'admin') {
    exit("Accès interdit");
}

$sql = "
SELECT 
    r.id,
    u.nom AS client,
    u.telephone,
    m.nom AS plat,
    m.prix,
    r.quantity,
    r.adresse_livraison,
    r.statut,
    r.date_reservation
FROM reservations r
JOIN users u ON r.user_id = u.id
JOIN menus m ON r.menu_id = m.id
ORDER BY r.date_reservation DESC
";

$reservations = $conn->query($sql)->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Réservations clients</title>
  
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../assets/style.css">

</head>
<body class="container mt-4">
    
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">

 <div class="container-fluid">
    <a class="navbar-brand" href="dashboard.php">🍽️ Admin Restaurant</a>

    <div class="collapse navbar-collapse">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link" href="dashboard.php">Dashboard</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="reservations.php">Commandes</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="../logout.php">Déconnexion</a>
        </li>
      </ul>
    </div>
  </div>
</nav>


<h2 class="mb-4">📦 Réservations clients</h2>

<?php if (count($reservations) === 0): ?>
    <div class="alert alert-info">Aucune réservation pour le moment.</div>
<?php endif; ?>

<?php foreach ($reservations as $r): ?>
  <div class="card mb-3">
    <div class="card-body">

      <h5 class="card-title">
        👤 <?= htmlspecialchars($r['client']) ?>
      </h5>

      <p class="card-text">
        📞 <?= htmlspecialchars($r['telephone']) ?><br>
        🍽️ <b><?= htmlspecialchars($r['plat']) ?></b><br>
        📦 Quantité : <?= (int) $r['quantity'] ?><br>
        💰 Total : <?= $r['prix'] * $r['quantity'] ?> €<br>
        📍 Adresse : <?= htmlspecialchars($r['adresse_livraison']) ?><br>
        📅 <?= $r['date_reservation'] ?><br>
        ⚙️ Statut : <b><?= htmlspecialchars($r['statut']) ?></b>
      </p>

      <form action="update_statut.php" method="POST" class="d-flex gap-2">
        <input type="hidden" name="id" value="<?= $r['id'] ?>">

        <select name="statut" class="form-select w-auto">
          <option value="en attente" <?= $r['statut'] === 'en attente' ? 'selected' : '' ?>>
            En attente
          </option>
          <option value="en préparation" <?= $r['statut'] === 'en préparation' ? 'selected' : '' ?>>
            En préparation
          </option>
          <option value="livré" <?= $r['statut'] === 'livré' ? 'selected' : '' ?>>
            Livré
          </option>
        </select>

        <button type="submit" class="btn btn-primary">
          Mettre à jour
        </button>
      </form>

    </div>
  </div>
<?php endforeach; ?>

</body>
</html>
