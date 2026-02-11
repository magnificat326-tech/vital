<?php
include 'includes/config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user;

        if ($user['role'] === 'admin') {
            header("Location: admin/dashboard.php");
        } else {
            header("Location: client/dashboard.php");
        }
        exit;
    } else {
        $error = "Email ou mot de passe incorrect";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" href="assets/style.css">
</head>
<body style="background: linear-gradient(135deg, #1c1c1c, #343a40); min-height:100vh;">

<div class="container d-flex justify-content-center align-items-center" style="min-height:100vh;">
    <div class="card shadow-lg" style="width:100%; max-width:420px; border-radius:12px;">

        <div class="card-body p-4">
            <h3 class="text-center mb-4">🔐 Connexion</h3>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger text-center">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST">

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input 
                        type="email" 
                        name="email" 
                        class="form-control"
                        placeholder="exemple@email.com" 
                        required
                    >
                </div>

                <div class="mb-4">
                    <label class="form-label">Mot de passe</label>
                    <input 
                        type="password" 
                        name="password" 
                        class="form-control"
                        placeholder="••••••••"
                        required
                    >
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-dark btn-lg">
                        Se connecter
                    </button>
                </div>

            </form>

            <p class="text-center mt-3 mb-0">
                Pas de compte ?
                <a href="register.php">Créer un compte</a>
            </p>
        </div>

    </div>
</div>

</body>
</html>
