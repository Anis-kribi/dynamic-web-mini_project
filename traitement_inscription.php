<?php
session_start();
require_once("../dbconnect.php");

// Show all PHP errors (good for debugging; remove in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $email = trim($_POST['email']);
    $mot_de_passe = $_POST['mot_de_passe'];
    $confirm_mot_de_passe = $_POST['confirm_mot_de_passe'];
    $role_utilisateur = $_POST['role_utilisateur'];

    // Basic validation
    if (empty($nom) || empty($prenom) || empty($email) || empty($mot_de_passe) || empty($confirm_mot_de_passe) || empty($role_utilisateur)) {
        header("Location: inscription.php?error=" . urlencode("Veuillez remplir tous les champs"));
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: inscription.php?error=" . urlencode("Format d'email invalide"));
        exit();
    }

    if ($mot_de_passe !== $confirm_mot_de_passe) {
        header("Location: inscription.php?error=" . urlencode("Les mots de passe ne correspondent pas"));
        exit();
    }

    try {
        // Check if email already exists
        $stmt = $pdo->prepare("SELECT user_id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            header("Location: inscription.php?error=" . urlencode("Email déjà utilisé"));
            exit();
        }

        // Generate username
        $username = strtolower($prenom . '.' . $nom);
        $full_name = $prenom . ' ' . $nom;

        // Hash password
        $mot_de_passe_hash = password_hash($mot_de_passe, PASSWORD_BCRYPT);

        // Insert user
        $stmt = $pdo->prepare("INSERT INTO users (username, password, role, created_at, full_name, email) VALUES (?, ?, ?, NOW(), ?, ?)");
        $success = $stmt->execute([$username, $mot_de_passe_hash, $role_utilisateur, $full_name, $email]);

        if ($success) {
            header("Location: connexion.php?success=" . urlencode("Inscription réussie. Vous pouvez maintenant vous connecter."));
            exit();
        } else {
            header("Location: inscription.php?error=" . urlencode("Erreur lors de l'inscription"));
            exit();
        }
    } catch (PDOException $e) {
        header("Location: inscription.php?error=" . urlencode("Erreur SQL : " . $e->getMessage()));
        exit();
    }
} else {
    header("Location: inscription.php?error=" . urlencode("Requête invalide"));
    exit();
}
?>
