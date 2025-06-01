<?php
session_start();
require_once("../dbconnect.php");

$isAdmin = isset($_SESSION['user_role']) && strtolower($_SESSION['user_role']) === 'admin';
$user_id = $_SESSION['user_id'] ?? null;

if (!isset($_GET['book_id'])) {
    echo "<div class='alert alert-danger'>Livre non spécifié.</div>";
    exit;
}

$book_id = $_GET['book_id'];

// Handle favorite toggle
if (isset($_GET['toggle_favorite']) && $user_id) {
    $check_sql = "SELECT * FROM favorites WHERE user_id = :user_id AND book_id = :book_id";
    $check_stmt = $pdo->prepare($check_sql);
    $check_stmt->execute(['user_id' => $user_id, 'book_id' => $book_id]);

    if ($check_stmt->rowCount() > 0) {
        $delete_sql = "DELETE FROM favorites WHERE user_id = :user_id AND book_id = :book_id";
        $delete_stmt = $pdo->prepare($delete_sql);
        $delete_stmt->execute(['user_id' => $user_id, 'book_id' => $book_id]);
        $_SESSION['message'] = ['success' => "Retiré des favoris."];
    } else {
        $insert_sql = "INSERT INTO favorites (user_id, book_id) VALUES (:user_id, :book_id)";
        $insert_stmt = $pdo->prepare($insert_sql);
        $insert_stmt->execute(['user_id' => $user_id, 'book_id' => $book_id]);
        $_SESSION['message'] = ['success' => "Ajouté aux favoris."];
    }

    header("Location: details.php?book_id=$book_id");
    exit;
}

// Fetch book details
$sql = "
    SELECT 
        b.*, 
        a.name AS author_name, 
        a.cover_image_author AS author_image, 
        a.biography AS author_bio, 
        GROUP_CONCAT(c.name ORDER BY c.name SEPARATOR ', ') AS categories
    FROM books b
    JOIN authors a ON b.author_id = a.author_id
    LEFT JOIN book_categories bc ON b.book_id = bc.book_id
    LEFT JOIN categories c ON bc.category_id = c.category_id
    WHERE b.book_id = :book_id
    GROUP BY b.book_id
";
$stmt = $pdo->prepare($sql);
$stmt->execute(['book_id' => $book_id]);
$book = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$book) {
    echo "<div class='alert alert-warning'>Livre introuvable.</div>";
    exit;
}

// Check if book is already in favorites
$isFavorite = false;
if ($user_id) {
    $fav_sql = "SELECT 1 FROM favorites WHERE user_id = :user_id AND book_id = :book_id";
    $fav_stmt = $pdo->prepare($fav_sql);
    $fav_stmt->execute(['user_id' => $user_id, 'book_id' => $book_id]);
    $isFavorite = $fav_stmt->fetchColumn();
}

// Handle rating submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['rating'])) {
        if (!$user_id) {
            $_SESSION['message'] = ['danger' => "Vous devez être connecté pour noter."];
            header("Location: details.php?book_id=$book_id");
            exit;
        }
        $rating = (int)$_POST['rating'];
        if ($rating >= 1 && $rating <= 10) {
            $check_sql = "SELECT * FROM book_ratings WHERE book_id = :book_id AND user_id = :user_id";
            $check_stmt = $pdo->prepare($check_sql);
            $check_stmt->execute(['book_id' => $book_id, 'user_id' => $user_id]);
            if ($check_stmt->rowCount() > 0) {
                $update_sql = "UPDATE book_ratings SET rating = :rating WHERE book_id = :book_id AND user_id = :user_id";
                $update_stmt = $pdo->prepare($update_sql);
                $update_stmt->execute(['rating' => $rating, 'book_id' => $book_id, 'user_id' => $user_id]);
            } else {
                $insert_sql = "INSERT INTO book_ratings (book_id, user_id, rating) VALUES (:book_id, :user_id, :rating)";
                $insert_stmt = $pdo->prepare($insert_sql);
                $insert_stmt->execute(['book_id' => $book_id, 'user_id' => $user_id, 'rating' => $rating]);
            }
            $_SESSION['message'] = ['success' => "Votre évaluation a été enregistrée."];
        } else {
            $_SESSION['message'] = ['danger' => "Note invalide. La note doit être comprise entre 1 et 10."];
        }
        header("Location: details.php?book_id=$book_id");
        exit;
    }

    // Handle comment submission
    if (isset($_POST['comment'])) {
        if (!$user_id) {
            $_SESSION['message'] = ['danger' => "Vous devez être connecté pour commenter."];
            header("Location: details.php?book_id=$book_id");
            exit;
        }
        $comment = trim($_POST['comment']);
        if (!empty($comment)) {
            $comment_sql = "INSERT INTO comments (book_id, user_id, content, created_at) VALUES (:book_id, :user_id, :content, NOW())";
            $comment_stmt = $pdo->prepare($comment_sql);
            $comment_stmt->execute([
                'book_id' => $book_id,
                'user_id' => $user_id,
                'content' => $comment
            ]);
            $_SESSION['message'] = ['success' => "Votre commentaire a été ajouté."];
        } else {
            $_SESSION['message'] = ['danger' => "Veuillez entrer un commentaire."];
        }
        header("Location: details.php?book_id=$book_id");
        exit;
    }
}

// Average rating
$rating_avg_sql = "SELECT AVG(rating) AS avg_rating FROM book_ratings WHERE book_id = :book_id";
$rating_avg_stmt = $pdo->prepare($rating_avg_sql);
$rating_avg_stmt->execute(['book_id' => $book_id]);
$avg_rating = $rating_avg_stmt->fetchColumn();
$avg_rating = $avg_rating ? round($avg_rating, 1) : null;

// Comments
$comments_sql = "
    SELECT c.*, u.username 
    FROM comments c
    JOIN users u ON c.user_id = u.user_id
    WHERE c.book_id = :book_id
    ORDER BY c.created_at DESC
";
$comments_stmt = $pdo->prepare($comments_sql);
$comments_stmt->execute(['book_id' => $book_id]);
$comments = $comments_stmt->fetchAll(PDO::FETCH_ASSOC);

// Messages
$message = $_SESSION['message'] ?? null;
unset($_SESSION['message']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détails du livre</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .star-rating input[type="radio"] {
            display: none;
        }
        .star-rating label {
            font-size: 1.5rem;
            color: #ccc;
            cursor: pointer;
        }
        .star-rating input[type="radio"]:checked ~ label {
            color: gold;
        }
        .star-rating label:hover,
        .star-rating label:hover ~ label {
            color: gold;
        }
    </style>
</head>
<body>
<div class="container py-5">

    <?php if ($message): ?>
        <?php foreach ($message as $type => $msg): ?>
            <div class="alert alert-<?= htmlspecialchars($type) ?>"><?= htmlspecialchars($msg) ?></div>
        <?php endforeach; ?>
    <?php endif; ?>

    <div class="card mb-4 shadow-sm">
        <div class="row g-0">
            <div class="col-md-4 text-center">
                <img src="<?= htmlspecialchars($book['cover_image']) ?>" class="img-fluid rounded-start p-3" alt="Couverture du livre">
            </div>
            <div class="col-md-8">
                <div class="card-body">
                    <h3 class="card-title"><?= htmlspecialchars($book['title']) ?></h3>
                    <p><strong>Auteur :</strong> <?= htmlspecialchars($book['author_name']) ?></p>
                    <p><strong>Année :</strong> <?= htmlspecialchars($book['publication_year']) ?></p>
                    <p><strong>Catégories :</strong> <?= htmlspecialchars($book['categories']) ?></p>
                    <p><strong>Description :</strong> <?= nl2br(htmlspecialchars($book['description'])) ?></p>
                    <p><strong>Prix :</strong> <?= htmlspecialchars($book['price']) ?> DT</p>
                    <p><strong>Note moyenne :</strong> <?= $avg_rating !== null ? $avg_rating . "/10" : "Pas encore noté" ?></p>

                    <?php if ($user_id): ?>
                        <a href="?book_id=<?= $book_id ?>&toggle_favorite=1" class="btn <?= $isFavorite ? 'btn-danger' : 'btn-warning' ?>">
                            <i class="bi bi-heart-fill"></i> <?= $isFavorite ? 'Retirer des favoris' : 'Ajouter aux favoris' ?>
                        </a>
                    <?php else: ?>
                        <a href="#" class="btn btn-warning disabled" title="Connectez-vous pour ajouter aux favoris">
                            <i class="bi bi-heart-fill"></i> Ajouter aux favoris
                        </a>
                    <?php endif; ?>

                    <a href="../books/acheter.php?book_id=<?= urlencode($book['book_id']) ?>" class="btn btn-success">Acheter</a>

                    <div class="card mt-4">
                        <div class="row g-0">
                            <div class="col-md-4 text-center">
                                <img src="<?= htmlspecialchars($book['author_image']) ?>" class="img-fluid rounded-start p-3" alt="Image de l'auteur">
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h4><?= htmlspecialchars($book['author_name']) ?></h4>
                                    <p><?= nl2br(htmlspecialchars($book['author_bio'])) ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php if ($user_id): ?>
                        <form method="POST" class="mt-4">
                            <label for="rating">Notez ce livre (1 à 10) :</label>
                            <div class="star-rating">
                                <?php for ($i = 10; $i >= 1; $i--): ?>
                                    <input type="radio" id="star<?= $i ?>" name="rating" value="<?= $i ?>">
                                    <label for="star<?= $i ?>">★</label>
                                <?php endfor; ?>
                            </div>
                            <button type="submit" class="btn btn-primary mt-2">Envoyer ma note</button>
                        </form>
                    <?php else: ?>
                        <p><em>Connectez-vous pour noter ce livre.</em></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <h5>Laisser un commentaire</h5>
        <?php if ($user_id): ?>
            <form method="POST">
                <div class="mb-3">
                    <textarea class="form-control" name="comment" rows="3" placeholder="Écrivez votre commentaire ici..."></textarea>
                </div>
                <button type="submit" class="btn btn-secondary">Publier</button>
            </form>
        <?php else: ?>
            <p><em>Connectez-vous pour commenter ce livre.</em></p>
        <?php endif; ?>
    </div>

    <div class="mt-5">
        <h5>Commentaires récents</h5>
        <?php if ($comments): ?>
            <?php foreach ($comments as $com): ?>
                <div class="border rounded p-3 mb-3">
                    <p><strong><?= htmlspecialchars($com['username']) ?> :</strong> <?= nl2br(htmlspecialchars($com['content'])) ?></p>
                    <small class="text-muted"><?= date("d/m/Y à H:i", strtotime($com['created_at'])) ?></small>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Aucun commentaire pour ce livre pour le moment.</p>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
