<?php
session_start();

// Remove the restriction so everyone can access (admin or not)

// Optional: You can still check if the user is an admin, if needed
$isAdmin = isset($_SESSION['user_role']) && strtolower($_SESSION['user_role']) === 'admin';

require_once("../fragments_pages/header.php");
require_once("../fragments_pages/navbar.php");
require_once("../dbconnect.php");  // Make sure $pdo is properly configured with ERRMODE_EXCEPTION

// Get the author ID from the URL
if (!isset($_GET['author_id']) || !is_numeric($_GET['author_id'])) {
    echo "<p class='text-center text-danger'>Auteur non trouvé.</p>";
    require_once("../fragments_pages/footer.php");
    exit;
}

$author_id = (int) $_GET['author_id'];

// Fetch author details
$author_stmt = $pdo->prepare("SELECT * FROM authors WHERE author_id = ?");
$author_stmt->execute([$author_id]);
$author = $author_stmt->fetch(PDO::FETCH_ASSOC);

if (!$author) {
    echo "<p class='text-center text-danger'>Auteur introuvable.</p>";
    require_once("../fragments_pages/footer.php");
    exit;
}

// Fetch books by the author
$book_stmt = $pdo->prepare("
    SELECT b.book_id, b.title, b.cover_image, b.price, b.rating, b.publication_year
    FROM books b
    WHERE b.author_id = ?
");
$book_stmt->execute([$author_id]);
$books = $book_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="main" style="background-color: #f9f9f9; padding: 50px 0;">
  <div class="container">
    <!-- Navigation Icons -->
    <div class="d-flex justify-content-start mb-4">
      <a href="javascript:history.back()" class="btn btn-outline-secondary me-3">
        <i class="bi bi-arrow-left-circle"></i> Retour
      </a>
      <a href="../index.php" class="btn btn-outline-primary">
        <i class="bi bi-house-door-fill"></i> Accueil
      </a>
    </div>

    <!-- Author Profile Section -->
    <div class="row bg-white p-4 shadow rounded mb-5">
      <div class="col-md-4 text-center">
        <img src="<?= htmlspecialchars($author['cover_image_author']) ?>" alt="<?= htmlspecialchars($author['name']) ?>" class="img-fluid rounded" style="max-height: 350px; object-fit: cover;">
      </div>
      <div class="col-md-8">
        <h2 class="text-primary"><?= htmlspecialchars($author['name']) ?></h2>
        <?php if (!empty($author['author_introduction'])): ?>
          <p><strong><?= htmlspecialchars($author['author_introduction']) ?></strong></p>
        <?php endif; ?>
        <?php if (!empty($author['biography'])): ?>
          <p class="text-muted"><?= nl2br(htmlspecialchars($author['biography'])) ?></p>
        <?php endif; ?>
      </div>
    </div>

    <!-- Author's Books Section -->
    <h3 class="mb-4 text-dark">Livres de <?= htmlspecialchars($author['name']) ?></h3>
    <div class="row">
      <?php if (count($books) > 0): ?>
        <?php foreach ($books as $book): ?>
          <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100 shadow-sm">
              <img src="<?= htmlspecialchars($book['cover_image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($book['title']) ?>" style="height: 300px; object-fit: cover;">
              <div class="card-body d-flex flex-column">
                <h5 class="card-title"><?= htmlspecialchars($book['title']) ?></h5>
                <p class="card-text text-dark">
                  <strong>Année :</strong> <?= htmlspecialchars($book['publication_year']) ?><br>
                  <strong>Note :</strong> <?= htmlspecialchars($book['rating']) ?>/5<br>
                  <strong>Prix :</strong> <?= htmlspecialchars($book['price']) ?> DT
                </p>
                <div class="d-flex justify-content-between align-items-center mt-auto">
                  <a href="../books/details.php?book_id=<?= $book['book_id'] ?>" class="btn btn-primary">Voir détails</a>
                  <a href="../favorites/add.php?book_id=<?= $book['book_id'] ?>" class="btn btn-outline-danger" title="Ajouter aux favoris">❤</a>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p class="text-muted">Aucun livre trouvé pour cet auteur.</p>
      <?php endif; ?>
    </div>
  </div>
</main>

<?php require_once("../fragments_pages/footer.php"); ?>
