<?php

session_start();

// Remove the restriction so everyone can access (admin or not)

// Optional: You can still check if the user is an admin, if needed
$isAdmin = isset($_SESSION['user_role']) && strtolower($_SESSION['user_role']) === 'admin';

require_once("../fragments_pages/header.php");
require_once("../fragments_pages/navbar.php");
require_once("../dbconnect.php");  // Make sure $pdo is properly configured with ERRMODE_EXCEPTION

// Get all authors from the database
$sql = "SELECT author_id, name, cover_image_author FROM authors";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$authors = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="main" style="background-color: #f1f1f1; padding: 40px 0;">
  <div style="background-color: #ffffff; padding: 30px; border-radius: 10px; box-shadow: 0 0 15px rgba(0,0,0,0.05); max-width: 1200px; margin: auto;">

    <div class="page-title text-center mb-4">
      <h1>Nos Auteurs</h1>
      <p>Découvrez les auteurs talentueux derrière nos livres</p>
    </div>

    <div class="container">
      <div class="row">
        <?php foreach ($authors as $author): ?>
          <div class="col-md-6 col-lg-4 mb-4 d-flex">
            <div class="card shadow-sm w-100 position-relative">
              <!-- Favorite Icon -->
              <div class="favorite-icon" onclick="toggleFavorite(this)">
                <i class="bi bi-heart"></i>
              </div>

              <img src="<?= htmlspecialchars($author['cover_image_author']) ?>" class="card-img-top" alt="<?= htmlspecialchars($author['name']) ?>" style="height: 300px; object-fit: cover;">
              <div class="card-body text-center d-flex flex-column">
                <h5 class="card-title"><?= htmlspecialchars($author['name']) ?></h5>
                <p class="card-text">Auteur reconnu pour ses œuvres remarquables.</p>
                <a href="author_details.php?author_id=<?= $author['author_id'] ?>" class="btn btn-outline-primary mt-auto">Voir les détails</a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

  </div>
</main>

<!-- Heart Toggle Script -->
<script>
  function toggleFavorite(icon) {
    icon.classList.toggle("favorited");
    icon.querySelector('i').classList.toggle("bi-heart");
    icon.querySelector('i').classList.toggle("bi-heart-fill");
  }
</script>

<!-- Styling for Favorite Icon -->
<style>
  .favorite-icon {
    position: absolute;
    top: 10px;
    right: 15px;
    font-size: 1.5rem;
    cursor: pointer;
    z-index: 10;
    color: #bbb;
  }

  .favorite-icon.favorited,
  .favorite-icon.favorited i,
  .favorite-icon i.bi-heart-fill {
    color: red;
  }
</style>

<?php require_once("../fragments_pages/footer.php"); ?>
