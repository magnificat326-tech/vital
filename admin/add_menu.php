<?php
include '../includes/config.php';
include '../includes/auth.php';

if ($_SESSION['user']['role'] !== 'admin') {
    exit("Accès interdit");
}

$jours = ['Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi','Dimanche'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nom = trim($_POST['nom']);
    $description = trim($_POST['description']);
    $prix = $_POST['prix'];
    $jour = $_POST['jour']; // ✅ AJOUT

    // Gestion image
    $imageName = $_FILES['image']['name'];
    $tmpName = $_FILES['image']['tmp_name'];
    $imagePath = "uploads/" . uniqid() . "_" . basename($imageName);

    if (!move_uploaded_file($tmpName, "../" . $imagePath)) {
        $error = "Erreur lors de l'upload de l'image";
    } else {
        $stmt = $conn->prepare(
            "INSERT INTO menus (nom, description, prix, image, jour)
             VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->execute([$nom, $description, $prix, $imagePath, $jour]);
        $success = "✅ Menu ajouté avec succès pour $jour";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un menu</title>

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
          <a class="nav-link" href="dashboard.php">Dashboard</a>
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

    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="card shadow-sm">
                <div class="card-body">

                    <h3 class="mb-4 text-center">➕ Ajouter un plat</h3>

                    <?php if (!empty($success)): ?>
                        <div class="alert alert-success text-center">
                            <?= htmlspecialchars($success) ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger text-center">
                            <?= htmlspecialchars($error) ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" enctype="multipart/form-data">

                        <div class="mb-3">
                            <label class="form-label">Nom du plat</label>
                            <input type="text" name="nom" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="3"></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Prix (€)</label>
                            <input type="number" step="0.01" name="prix" class="form-control" required>
                        </div>

                        <!-- ✅ AJOUT MENU DU JOUR -->
                        <div class="mb-3">
                            <label class="form-label">Jour du menu</label>
                            <select name="jour" class="form-select" required>
                                <?php foreach ($jours as $j): ?>
                                    <option value="<?= $j ?>"><?= $j ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Image du plat</label>
                            <input type="file" name="image" class="form-control" accept="image/*" required>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-dark btn-lg">
                                Ajouter le menu
                            </button>
                        </div>

                    </form>

                </div>
            </div>

            <div class="text-center mt-3">
                <a href="dashboard.php">⬅ Retour au dashboard</a>
            </div>

        </div>
    </div>

</div>

</body>
</html>
