<?php

session_start();

// Remove the restriction so everyone can access (admin or not)

// Optional: You can still check if the user is an admin, if needed
$isAdmin = isset($_SESSION['user_role']) && strtolower($_SESSION['user_role']) === 'admin';

require_once("../fragments_pages/header.php");
require_once("../fragments_pages/navbar.php");
require_once("../dbconnect.php");  // Make sure $pdo is properly configured with ERRMODE_EXCEPTION

if (!isset($_GET['book_id']) || !is_numeric($_GET['book_id'])) {
    echo "<div class='container my-5'><div class='alert alert-danger text-center'>Aucun livre valide sélectionné pour l'achat.</div></div>";
    require_once("../fragments_pages/footer.php");
    exit;
}

$book_id = (int) $_GET['book_id'];
?>

<main class="main" style="background-color: #f1f1f1; padding: 40px 0;">
  <div class="container">
    <div class="bg-white p-5 rounded shadow text-center">
      <h1 class="text-success mb-4">✅ Achat confirmé</h1>
      <p class="lead">Merci pour votre achat du livre !</p>
      <a href="../index.php" class="btn btn-primary mt-3 me-2">
        <i class="bi bi-house-door-fill"></i> Retour à l'accueil
      </a>
      <a href="../books/books.php" class="btn btn-secondary mt-3">
        <i class="bi bi-arrow-left-circle"></i> Voir d'autres livres
      </a>
    </div>
  </div>
</main>

<?php
require_once("../fragments_pages/footer.php");
ob_end_flush();
?>
