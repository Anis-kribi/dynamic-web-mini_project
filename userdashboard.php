<?php
session_start();
require_once("../dbconnect.php");

if (!isset($_SESSION['user_role']) || strtolower($_SESSION['user_role']) !== 'user') {
    header("Location: ../index.php");
    exit();
}

$user_full_name = $_SESSION['full_name'] ?? 'Utilisateur';
$user_id = $_SESSION['user_id'] ?? null;

// Favoris
$favorites_stmt = $pdo->prepare("SELECT b.*, a.name AS author_name 
                                FROM favorites f 
                                JOIN books b ON f.book_id = b.book_id 
                                LEFT JOIN authors a ON b.author_id = a.author_id 
                                WHERE f.user_id = ? 
                                LIMIT 5");
$favorites_stmt->execute([$user_id]);
$favorite_books = $favorites_stmt->fetchAll(PDO::FETCH_ASSOC);

// Livres
$books_stmt = $pdo->query("SELECT b.*, a.name AS author_name FROM books b LEFT JOIN authors a ON b.author_id = a.author_id ORDER BY b.title ASC");
$books = $books_stmt->fetchAll(PDO::FETCH_ASSOC);

// Auteurs
$authors_stmt = $pdo->query("SELECT * FROM authors ORDER BY name ASC");
$authors = $authors_stmt->fetchAll(PDO::FETCH_ASSOC);

// Commentaires
$comments_stmt = $pdo->prepare("
    SELECT c.content, c.created_at, b.title AS book_title
    FROM comments c
    JOIN books b ON c.book_id = b.book_id
    WHERE c.user_id = ?
    ORDER BY c.created_at DESC
");
$comments_stmt->execute([$user_id]);
$user_comments = $comments_stmt->fetchAll(PDO::FETCH_ASSOC);

require_once("../fragments_pages/header.php");
include('../fragments_pages/navbar.php');
?>

<!-- Styles -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<style>
    body {
        background-color: #f8f9fa;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .dashboard-header {
        font-size: 2.5rem;
        font-weight: 700;
        color: #0d6efd;
        text-shadow: 1px 1px 4px rgba(0,0,0,0.1);
    }

    .dashboard-title {
        font-size: 2.2rem;
        color: #2d2d2d;
        margin: 2rem 0;
    }

    .card {
        border: none;
        border-radius: 1rem;
        transition: transform 0.3s ease;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        background: linear-gradient(145deg, #ffffff, #f8f9fa);
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 20px rgba(0,0,0,0.1);
    }

    .table thead th {
        background-color: #0d6efd;
        color: white;
        font-weight: 600;
    }

    .category-badge {
        background-color: #0d6efd;
        color: white;
    }

    .rating-stars {
        color: #ffd700;
        font-size: 1.1rem;
    }

    .author-card {
        transition: transform 0.3s ease;
    }

    .author-card:hover {
        transform: scale(1.03);
    }
</style>

<div class="container my-5">
    <div class="text-center mb-4">
        <h1 class="dashboard-header">Bienvenue, <?= htmlspecialchars($user_full_name); ?> !</h1>
        <div class="dashboard-title">
            <i class="bi bi-person-circle me-2"></i>Tableau de Bord Utilisateur
        </div>
    </div>

    <!-- Favoris -->
    <div class="card border-0 shadow mb-5">
        <div class="card-body">
            <h3 class="mb-4"><i class="bi bi-heart-fill text-danger"></i> Vos Favoris</h3>
            <div class="row">
                <?php if(count($favorite_books) > 0): ?>
                    <?php foreach($favorite_books as $book): ?>
                        <div class="col-md-4 mb-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title"><?= htmlspecialchars($book['title']) ?></h5>
                                    <p class="card-text text-muted">
                                        <i class="bi bi-person"></i> <?= htmlspecialchars($book['author_name'] ?? 'Auteur inconnu') ?>
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="badge category-badge">
                                            Catégorie <?= htmlspecialchars($book['category_id']) ?>
                                        </span>
                                        <div class="rating-stars">
                                            <?php for($i = 0; $i < 5; $i++): ?>
                                                <i class="bi bi-star<?= $i < floor($book['rating']) ? '-fill' : '' ?>"></i>
                                            <?php endfor; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12 text-center py-4">
                        <p class="text-muted">Aucun livre favori pour le moment</p>
                        <a href="#books-section" class="btn btn-primary">
                            <i class="bi bi-book"></i> Parcourir les livres
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Commentaires -->
    <div class="card border-0 shadow mb-5">
        <div class="card-body">
            <h3 class="mb-4"><i class="bi bi-chat-dots"></i> Vos Commentaires</h3>
            <?php if(count($user_comments) > 0): ?>
                <?php foreach($user_comments as $comment): ?>
                    <div class="mb-4 p-3 rounded border bg-light">
                        <p class="mb-1 text-muted">
                            Sur <strong><?= htmlspecialchars($comment['book_title']) ?></strong>
                            — <small><?= date('d/m/Y H:i', strtotime($comment['created_at'])) ?></small>
                        </p>
                        <p class="mb-0"><?= nl2br(htmlspecialchars($comment['content'])) ?></p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-muted">Vous n'avez encore publié aucun commentaire.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Tous les livres -->
    <div class="card border-0 shadow mb-5" id="books-section">
        <div class="card-body">
            <h3 class="mb-4"><i class="bi bi-book"></i> Tous les Livres</h3>
            <table id="books-table" class="table table-hover">
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Auteur</th>
                        <th>Catégorie</th>
                        <th>Prix</th>
                        <th>Note</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($books as $book): ?>
                    <tr>
                        <td><?= htmlspecialchars($book['title']) ?></td>
                        <td><?= htmlspecialchars($book['author_name'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($book['category_id']) ?></td>
                        <td><?= htmlspecialchars($book['price']) ?> DT</td>
                        <td>
                            <div class="rating-stars">
                                <?php for($i = 0; $i < 5; $i++): ?>
                                    <i class="bi bi-star<?= $i < floor($book['rating']) ? '-fill' : '' ?>"></i>
                                <?php endfor; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Auteurs -->
    <div class="card border-0 shadow mb-5">
        <div class="card-body">
            <h3 class="mb-4"><i class="bi bi-people-fill"></i> Auteurs</h3>
            <div class="row">
                <?php foreach ($authors as $author): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card author-card h-100">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($author['name']) ?></h5>
                                <p class="card-text text-muted truncate-text">
                                    <?= htmlspecialchars(substr($author['biography'], 0, 100)) ?>...
                                </p>
                                <a href="../authors/author_details.php?author_id=<?= $author['author_id'] ?>" class="btn btn-outline-primary">
                                    <i class="bi bi-info-circle"></i> Voir les détails
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#books-table').DataTable({
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json'
        },
        columnDefs: [
            { orderable: true, targets: [0, 1, 2, 3] },
            { className: "align-middle", targets: "_all" }
        ],
        order: [[0, 'asc']]
    });

    document.querySelectorAll('#books-table tbody tr').forEach(row => {
        row.addEventListener('mouseenter', () => row.style.backgroundColor = '#f8f9fa');
        row.addEventListener('mouseleave', () => row.style.backgroundColor = '');
    });
});
</script>

<?php include('../fragments_pages/footer.php'); ?>
