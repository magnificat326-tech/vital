<?php
include '../includes/config.php';
include '../includes/auth.php';

if ($_SESSION['user']['role'] !== 'admin') {
    exit("Accès interdit");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- CSS perso -->
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

<!-- NAVBAR ADMIN -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="dashboard.php">⚙️ Admin – Restaurant</a>

    <div class="collapse navbar-collapse">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <span class="nav-link">👋 Admin</span>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="../logout.php">Déconnexion</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- CONTENU -->
<div class="container mt-5">

    <h2 class="mb-4 text-center">Tableau de bord Administrateur</h2>

    <div class="row justify-content-center">

        <!-- AJOUT MENU -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <h4>➕ Ajouter un menu</h4>
                    <p class="text-muted">
                        Ajouter de nouveaux plats au menu du restaurant.
                    </p>
                    <a href="add_menu.php" class="btn btn-dark w-100">
                        Ajouter
                    </a>
                </div>
            </div>
        </div>

        <!-- VOIR RÉSERVATIONS -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <h4>📦 Réservations</h4>
                    <p class="text-muted">
                        Consulter les commandes et livraisons clients.
                    </p>
                    <a href="reservations.php" class="btn btn-primary w-100">
                        Voir
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>

</body>
</html>
