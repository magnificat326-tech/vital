<?php
include '../includes/config.php';
include '../includes/auth.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'client') {
    exit("Accès interdit");
}

$user = $_SESSION['user'];

/* Jours autorisés */
$jours = ['Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi','Dimanche'];

/* Jour du jour en français */
$mapJour = [
    'Monday'    => 'Lundi',
    'Tuesday'   => 'Mardi',
    'Wednesday' => 'Mercredi',
    'Thursday'  => 'Jeudi',
    'Friday'    => 'Vendredi',
    'Saturday'  => 'Samedi',
    'Sunday'    => 'Dimanche'
];

$aujourdhui = $mapJour[date('l')];

/* Jour sélectionné */
$jour_selectionne = $_GET['jour'] ?? $aujourdhui;

/* Sécurité : jour invalide */
if (!in_array($jour_selectionne, $jours)) {
    $jour_selectionne = $aujourdhui;
}

/* Menus du jour */
$stmt = $conn->prepare("SELECT * FROM menus WHERE jour = ?");
$stmt->execute([$jour_selectionne]);
$menus = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Espace Client</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="dashboard.php">🍽️ Restaurant</a>

    <ul class="navbar-nav ms-auto">
      <li class="nav-item">
        <span class="nav-link">👋 <?= htmlspecialchars($user['nom']) ?></span>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="../logout.php">Déconnexion</a>
      </li>
    </ul>
  </div>
</nav>

<div class="container mt-4">

  <h3 class="mb-3">
    📅 Menu du jour : <strong><?= htmlspecialchars($jour_selectionne) ?></strong>
  </h3>

  <!-- 🔽 SÉLECTEUR JOUR -->
  <form method="GET" class="mb-4">
    <select name="jour" class="form-select w-25" onchange="this.form.submit()">
      <?php foreach ($jours as $jour): ?>
        <option value="<?= $jour ?>" <?= $jour === $jour_selectionne ? 'selected' : '' ?>>
          <?= $jour ?>
        </option>
      <?php endforeach; ?>
    </select>
  </form>

  <!-- ✅ MESSAGE SUCCÈS -->
  <?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success text-center">
      ✅ <strong>Commande passée avec succès !</strong><br>
      Merci pour votre confiance 🍽️
    </div>
  <?php endif; ?>

  <?php if (count($menus) === 0): ?>
    <div class="alert alert-info">
      Aucun menu disponible pour <?= htmlspecialchars($jour_selectionne) ?>.
    </div>
  <?php endif; ?>

  <div class="row">

  <?php foreach ($menus as $menu): ?>
    <div class="col-md-4 mb-4">
      <div class="card shadow-sm h-100">

        <?php if (!empty($menu['image'])): ?>
          <img 
            src="../<?= htmlspecialchars($menu['image']) ?>" 
            class="card-img-top menu-img"
            alt="<?= htmlspecialchars($menu['nom']) ?>"
          >
        <?php endif; ?>

        <div class="card-body d-flex flex-column">
          <h5 class="card-title"><?= htmlspecialchars($menu['nom']) ?></h5>
          <p class="card-text"><?= htmlspecialchars($menu['description']) ?></p>
          <p class="fw-bold"><?= htmlspecialchars($menu['prix']) ?> €</p>

          <form action="reserver.php" method="POST" class="mt-auto">
            <input type="hidden" name="menu_id" value="<?= $menu['id'] ?>">

            <div class="mb-2">
              <label class="form-label">Quantité</label>
              <input type="number" name="quantity" class="form-control" min="1" value="1" required>
            </div>

            <div class="mb-2">
              <label class="form-label">Adresse de livraison</label>
              <input type="text" name="adresse" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-success w-100">
              📦 Commander
            </button>
          </form>
        </div>

      </div>
    </div>
  <?php endforeach; ?>

  </div>

  <hr class="my-5">

<h4 class="mb-3">📦 Mes commandes</h4>

<?php
$stmt = $conn->prepare("
    SELECT r.*, m.nom 
    FROM reservations r
    JOIN menus m ON m.id = r.menu_id
    WHERE r.user_id = ?
    ORDER BY r.id DESC
");
$stmt->execute([$user['id']]);
$commandes = $stmt->fetchAll();
?>

<?php if (count($commandes) === 0): ?>
    <div class="alert alert-secondary">Aucune commande pour le moment.</div>
<?php else: ?>
<table class="table table-bordered">
    <thead class="table-dark">
        <tr>
            <th>Plat</th>
            <th>Quantité</th>
            <th>Adresse</th>
            <th>Statut</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($commandes as $c): ?>
        <tr>
            <td><?= htmlspecialchars($c['nom']) ?></td>
            <td><?= $c['quantity'] ?></td>
            <td><?= htmlspecialchars($c['adresse_livraison']) ?></td>
            <td>
                <?php
                $badge = match ($c['statut']) {
                    'En attente' => 'secondary',
                    'En préparation' => 'warning',
                    'En livraison' => 'info',
                    'Livrée' => 'success',
                    'Annulée' => 'danger',
                    default => 'dark'
                };
                ?>
                <span class="badge bg-<?= $badge ?>">
                    <?= htmlspecialchars($c['statut']) ?>
                </span>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>

</div>

</body>
</html>
