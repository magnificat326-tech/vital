<?php
include 'includes/config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = trim($_POST['nom']);
    $tel = trim($_POST['telephone']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Vérifier si email existe déjà
    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->execute([$email]);

    if ($check->rowCount() > 0) {
        $error = "Cet email est déjà utilisé.";
    } else {
        $stmt = $conn->prepare(
            "INSERT INTO users (nom, telephone, email, password, role)
             VALUES (?, ?, ?, ?, 'client')"
        );
        $stmt->execute([$nom, $tel, $email, $password]);
        header("Location: login.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" href="assets/style.css">
</head>
<body style="background: linear-gradient(135deg, #1c1c1c, #343a40); min-height:100vh;">

<div class="container d-flex justify-content-center align-items-center" style="min-height:100vh;">
    <div class="card shadow-lg" style="width:100%; max-width:450px; border-radius:12px;">

        <div class="card-body p-4">
            <h3 class="text-center mb-4">📝 Inscription client</h3>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger text-center">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST">

                <div class="mb-3">
                    <label class="form-label">Nom complet</label>
                    <input type="text" name="nom" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Téléphone</label>
                    <input type="text" name="telephone" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>

                <div class="mb-4">
                    <label class="form-label">Mot de passe</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-dark btn-lg">
                        Créer mon compte
                    </button>
                </div>

            </form>

            <p class="text-center mt-3 mb-0">
                Déjà un compte ?
                <a href="login.php">Se connecter</a>
            </p>
        </div>

    </div>
</div>

</body>
</html>
