<?php
session_start();
require_once("../fragments_pages/header.php");
require_once("../fragments_pages/navbar.php");
require_once("../dbconnect.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Vérification du reCAPTCHA
    // Récupérer la réponse reCAPTCHA envoyée par le formulaire
$recaptcha = $_POST['g-recaptcha-response'] ?? '';

if (empty($recaptcha)) {
    header("Location: ../connexion/connexion.php?error=Veuillez vérifier le reCAPTCHA.");
    exit();
}

$secretKey = "6Lew5UgrAAAAAHv6VgjjaYypTuqBJ4U-F1zXs7QS";  // Clé secrète

// Vérification avec cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    'secret' => $secretKey,
    'response' => $recaptcha,
    'remoteip' => $_SERVER['REMOTE_ADDR']
]));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

$responseKeys = json_decode($response, true);

if (!$responseKeys['success']) {
    header("Location: ../connexion/connexion.php?error=Échec de la vérification reCAPTCHA.");
    exit();
}


    // Vérification des champs email et password
    if (empty($email) || empty($password)) {
        header("Location: ../connexion/connexion.php?error=Veuillez remplir tous les champs.");
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: ../connexion/connexion.php?error=Format d'email invalide.");
        exit();
    }

    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            if (password_verify($password, $user['password'])) {
                // Session
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['full_name'] = $user['full_name'];

                // Redirection selon rôle
                if ($user['role'] === 'admin') {
                    header("Location: ../admin/dashboard.php");
                } else {
                    header("Location: ../index.php");
                }
                exit();
            } else {
                header("Location: ../connexion/connexion.php?error=Mot de passe incorrect.");
                exit();
            }
        } else {
            header("Location: ../connexion/connexion.php?error=Aucun utilisateur trouvé.");
            exit();
        }
    } catch (PDOException $e) {
        error_log("Erreur lors de la connexion: " . $e->getMessage());
        header("Location: ../connexion/connexion.php?error=Erreur interne.");
        exit();
    }
} else {
    header("Location: ../connexion/connexion.php");
    exit();
}
