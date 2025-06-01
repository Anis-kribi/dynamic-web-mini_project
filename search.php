<?php
session_start();

// Optional: Check if user is admin
$isAdmin = isset($_SESSION['user_role']) && strtolower($_SESSION['user_role']) === 'admin';

require_once("../fragments_pages/header.php");
require_once("../fragments_pages/navbar.php");
require_once("../dbconnect.php");

$query = isset($_GET['query']) ? trim($_GET['query']) : '';

if (empty($query)) {
    header('Location: ../index.php');
    exit;
}

try {
    $sqlBooks = "SELECT * FROM books WHERE title LIKE :query1 OR description LIKE :query2";
    $stmtBooks = $pdo->prepare($sqlBooks);
    $stmtBooks->execute([
        ':query1' => "%$query%",
        ':query2' => "%$query%"
    ]);
    $bookResults = $stmtBooks->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur de base de données : " . $e->getMessage());
}
?>

<style>
.card {
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    transition: transform 0.2s;
}
.card:hover {
    transform: scale(1.03);
}
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

/* Home button styling */
.home-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    background-color: #0d6efd;
    color: white;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 500;
    margin-bottom: 20px;
    transition: background-color 0.3s ease;
}
.home-btn:hover {
    background-color: #0b5ed7;
    text-decoration: none;
    color: white;
}
.home-btn i {
    font-size: 1.2rem;
}
</style>

<div class="container">
    

    <h2>Résultats de recherche pour : "<?= htmlspecialchars($query) ?>"</h2>

    <div class="row">
        <?php if ($bookResults): ?>
            <?php $count = 1; ?>
            <?php foreach ($bookResults as $book): ?>
                <div class="col-md-6 col-lg-4 mb-4 d-flex" data-aos="zoom-in" data-aos-delay="<?= $count * 100 ?>">
                    <div class="card h-100 shadow-sm">
                        <img src="<?= htmlspecialchars($book['cover_image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($book['title']) ?>">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?= htmlspecialchars($book['title']) ?></h5>
                            <p class="card-text">
                                <strong>Auteur :</strong> <?= isset($book['name']) ? htmlspecialchars($book['author_name']) : 'Non spécifié' ?><br>
                                <strong>Année :</strong> <?= htmlspecialchars($book['publication_year']) ?><br>
                                <strong>Note :</strong> <?= htmlspecialchars($book['rating']) ?>/5<br>
                                <strong>Catégories :</strong> <?= isset($book['categories']) ? htmlspecialchars($book['categories']) : 'Non spécifié' ?><br>
                                <strong>Prix :</strong> <?= htmlspecialchars($book['price']) ?> DT 
                            </p>
                            <div class="mt-auto d-flex justify-content-between align-items-center">
                                <a href="../books/details.php?book_id=<?= $book['book_id'] ?>" class="btn btn-primary">Détails</a>
                                <a href="../books/acheter.php?book_id=<?= $book['book_id'] ?>" class="button" data-tooltip="Prix: -<?= htmlspecialchars($book['price']) ?> DT">
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
        <?php else: ?>
            <p>Aucun livre trouvé.</p>
        <?php endif; ?>
    </div>
</div>

<?php require_once("../fragments_pages/footer.php"); ?>
