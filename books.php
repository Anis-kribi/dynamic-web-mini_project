<?php

session_start();

// Remove the restriction so everyone can access (admin or not)

// Optional: You can still check if the user is an admin, if needed
$isAdmin = isset($_SESSION['user_role']) && strtolower($_SESSION['user_role']) === 'admin';

require_once("../fragments_pages/header.php");
require_once("../fragments_pages/navbar.php");
require_once("../dbconnect.php");  // Make sure $pdo is properly configured with ERRMODE_EXCEPTION

try {
    // Récupérer les livres avec les auteurs et les catégories
    $sql = "SELECT 
            b.book_id, 
            b.title, 
            b.price, 
            b.cover_image, 
            b.publication_year, 
            b.rating, 
            a.name AS author_name,
            GROUP_CONCAT(c.name ORDER BY c.name SEPARATOR ', ') AS categories
        FROM books b
        JOIN authors a ON b.author_id = a.author_id
        LEFT JOIN book_categories bc ON b.book_id = bc.book_id
        LEFT JOIN categories c ON bc.category_id = c.category_id
        GROUP BY b.book_id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Erreur de base de données : " . $e->getMessage();
}
?>


  <style>
  /* From Uiverse.io by JaydipPrajapati1910 */ 
  .button {
    --width: 100px;
    --height: 35px;
    --tooltip-height: 35px;
    --tooltip-width: 90px;
    --gap-between-tooltip-to-button: 18px;
    --button-color: #198754;
    --tooltip-color: #fff;
    width: var(--width);
    height: var(--height);
    background: var(--button-color);
    position: relative;
    text-align: center;
    border-radius: 0.45em;
    font-family: "Arial";
    transition: background 0.3s;
  }

  .button::before {
    position: absolute;
    content: attr(data-tooltip);
    width: var(--tooltip-width);
    height: var(--tooltip-height);
    background-color: #555;
    font-size: 0.9rem;
    color: #fff;
    border-radius: .25em;
    line-height: var(--tooltip-height);
    bottom: calc(var(--height) + var(--gap-between-tooltip-to-button) + 10px);
    left: calc(50% - var(--tooltip-width) / 2);
  }

  .button::after {
    position: absolute;
    content: '';
    width: 0;
    height: 0;
    border: 10px solid transparent;
    border-top-color: #555;
    left: calc(50% - 10px);
    bottom: calc(100% + var(--gap-between-tooltip-to-button) - 10px);
  }

  .button::after,
  .button::before {
    opacity: 0;
    visibility: hidden;
    transition: all 0.5s;
  }

  .text {
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .button-wrapper, .text, .icon {
    overflow: hidden;
    position: absolute;
    width: 100%;
    height: 100%;
    left: 0;
    color: #fff;
  }

  .text {
    top: 0;
  }

  .text, .icon {
    transition: top 0.5s;
  }

  .icon {
    top: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .icon svg {
    width: 24px;
    height: 24px;
  }

  .button:hover {
    background: #157347;
  }

  .button:hover .text {
    top: -100%;
  }

  .button:hover .icon {
    top: 0;
  }

  .button:hover:before,
  .button:hover:after {
    opacity: 1;
    visibility: visible;
  }

  .button:hover:after {
    bottom: calc(var(--height) + var(--gap-between-tooltip-to-button) - 20px);
  }

  .button:hover:before {
    bottom: calc(var(--height) + var(--gap-between-tooltip-to-button));
  }
  </style>

  <main class="main" style="background-color: #f1f1f1; padding: 40px 0;">
    <div style="background-color: #ffffff; padding: 30px; border-radius: 10px; box-shadow: 0 0 15px rgba(0,0,0,0.05); max-width: 1200px; margin: auto;">
      <!-- Page Title -->
      <div class="page-title" data-aos="fade">
        <div class="heading">
          <div class="container">
            <div class="row d-flex justify-content-center text-center">
              <div class="col-lg-8">
                <h1>Nos Livres</h1>
                <p class="mb-0">Explorez notre large collection de livres couvrant divers genres et auteurs réputés...</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Books Section -->
      <section id="books" class="section py-5">
        <div class="container">
          <div class="row">
            <?php 
            $count = 0;
            foreach ($books as $book): ?>
              <div class="col-md-6 col-lg-4 mb-4 d-flex" data-aos="zoom-in" data-aos-delay="<?= $count * 100 ?>">
                <div class="card h-100 shadow-sm">
                  <img src="<?= htmlspecialchars($book['cover_image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($book['title']) ?>">
                  <div class="card-body d-flex flex-column">
                    <h5 class="card-title"><?= htmlspecialchars($book['title']) ?></h5>
                    <p class="card-text">
                      <strong>Auteur :</strong> <?= htmlspecialchars($book['author_name']) ?><br>
                      <strong>Année :</strong> <?= htmlspecialchars($book['publication_year']) ?><br>
                      <strong>Note :</strong> <?= htmlspecialchars($book['rating']) ?>/5<br>
                      <strong>Catégories :</strong> <?= htmlspecialchars($book['categories']) ?><br>
                      <strong>Prix :</strong> <?= htmlspecialchars($book['price']) ?> DT 
                    </p>
                    <div class="mt-auto d-flex justify-content-between align-items-center">
                      <a href="../books/details.php?book_id=<?= $book['book_id'] ?>" class="btn btn-primary">Détails</a>
                      
                      <!-- New styled Buy button -->
                      <a href="../books/acheter.php?book_id=<?= $book['book_id'] ?>" 
                        class="button" 
                        data-tooltip="Prix: -<?= htmlspecialchars($book['price']) ?> DT">
                        <div class="button-wrapper">
                          <div class="text">Acheter</div>
                          <span class="icon">
                            <svg viewBox="0 0 16 16" fill="currentColor" height="16" width="16" xmlns="http://www.w3.org/2000/svg">
                              <path d="M0 2.5A.5.5 0 0 1 .5 2H2a.5.5 0 0 1 .485.379L2.89 4H14.5a.5.5 0 0 1 .485.621l-1.5 6A.5.5 0 0 1 13 11H4a.5.5 0 0 1-.485-.379L1.61 3H.5a.5.5 0 0 1-.5-.5zM3.14 5l1.25 5h8.22l1.25-5H3.14zM5 13a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0zm9-1a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0z"/>
                            </svg>
                          </span>
                        </div>
                      </a>
                    </div>
                  </div>
                </div>
              </div>
              <?php $count++; ?>
            <?php endforeach; ?>
          </div>
        </div>
      </section>
    </div>
  </main>

  <?php require_once("../fragments_pages/footer.php"); ?>
