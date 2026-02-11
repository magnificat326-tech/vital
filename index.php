<?php
session_start();
require_once 'includes/config.php';

/* =========================
   MENUS DE LA SEMAINE
========================= */
$jourSelectionne = $_GET['jour'] ?? '';

$menusJour = [];
if ($jourSelectionne) {
    $stmt = $conn->prepare("SELECT * FROM menus WHERE jour = ?");
    $stmt->execute([$jourSelectionne]);
    $menusJour = $stmt->fetchAll();
}

/* =========================
   TOUS LES MENUS (VITRINE)
========================= */
$menus = $conn->query("SELECT * FROM menus")->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Restaurant – Accueil</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="index.php">🍽️ Restaurant</a>

    <ul class="navbar-nav ms-auto">
      <?php if (isset($_SESSION['user'])): ?>
          <?php if ($_SESSION['user']['role'] === 'admin'): ?>
              <li class="nav-item">
                  <a class="nav-link" href="admin/dashboard.php">Espace Admin</a>
              </li>
          <?php else: ?>
              <li class="nav-item">
                  <a class="nav-link" href="client/dashboard.php">Espace Client</a>
              </li>
          <?php endif; ?>
          <li class="nav-item">
              <a class="nav-link" href="logout.php">Déconnexion</a>
          </li>
      <?php else: ?>
          <li class="nav-item">
              <a class="nav-link" href="login.php">Connexion</a>
          </li>
          <li class="nav-item">
              <a class="nav-link" href="register.php">Inscription</a>
          </li>
      <?php endif; ?>
    </ul>
  </div>
</nav>

<!-- CONTENU -->
<div class="container mt-4">

    <h1 class="text-center mb-2">🍽️ Bienvenue dans notre restaurant</h1>
    <p class="text-center text-muted">
        Découvrez nos menus de la semaine et nos plats disponibles.
    </p>

    <!-- =========================
         MENUS DE LA SEMAINE
    ========================= -->
    <hr>
    <h3 class="mb-3">📅 Menus de la semaine</h3>

    <form method="GET" class="mb-4" style="max-width:300px;">
        <select name="jour" class="form-select" onchange="this.form.submit()">
            <option value="">-- Choisir un jour --</option>
            <?php
            $jours = ["Lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi","Dimanche"];
            foreach ($jours as $jour):
            ?>
                <option value="<?= $jour ?>" <?= ($jour === $jourSelectionne) ? 'selected' : '' ?>>
                    <?= $jour ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>

    <?php if ($jourSelectionne): ?>
        <h5 class="mb-3">Menus du <?= htmlspecialchars($jourSelectionne) ?></h5>

        <?php if (count($menusJour) === 0): ?>
            <div class="alert alert-warning">
                Aucun menu prévu pour ce jour.
            </div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($menusJour as $menu): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 shadow-sm">

                            <?php if (!empty($menu['image'])): ?>
                                <img 
                                    src="<?= htmlspecialchars($menu['image']) ?>" 
                                    class="card-img-top menu-img"
                                    alt="<?= htmlspecialchars($menu['nom']) ?>"
                                >
                            <?php endif; ?>

                            <div class="card-body">
                                <h5><?= htmlspecialchars($menu['nom']) ?></h5>
                                <p><?= htmlspecialchars($menu['description']) ?></p>
                                <p class="fw-bold"><?= htmlspecialchars($menu['prix']) ?> €</p>

                                <small class="text-muted">
                                    🔒 Connectez-vous pour commander
                                </small>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <!-- =========================
         TOUS LES MENUS
    ========================= -->
    <hr>
    <h3 class="mb-4">📋 Tous nos menus</h3>

    <div class="row">
        <?php foreach ($menus as $menu): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">

                    <?php if (!empty($menu['image'])): ?>
                        <img 
                            src="<?= htmlspecialchars($menu['image']) ?>" 
                            class="card-img-top menu-img"
                            alt="<?= htmlspecialchars($menu['nom']) ?>"
                        >
                    <?php endif; ?>

                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($menu['nom']) ?></h5>
                        <p class="card-text"><?= htmlspecialchars($menu['description']) ?></p>
                        <p class="fw-bold"><?= htmlspecialchars($menu['prix']) ?> €</p>
                    </div>

                    <div class="card-footer text-center bg-white">
                        <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'client'): ?>
                            <a href="client/dashboard.php" class="btn btn-dark">
                                📦 Commander
                            </a>
                        <?php else: ?>
                            <button class="btn btn-secondary" disabled>
                                🔒 Connectez-vous pour commander
                            </button>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
        <?php endforeach; ?>
    </div>

</div>

</body>
</html>
