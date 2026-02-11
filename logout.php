<?php
session_start();

// Supprimer toutes les variables de session
session_unset();

// Détruire la session
session_destroy();

// Redirection vers l'accueil
header("Location: index.php");
exit;
