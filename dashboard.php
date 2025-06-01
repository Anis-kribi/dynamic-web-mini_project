<?php
session_start();
require_once("../dbconnect.php");

// Check if admin is authenticated
if (!isset($_SESSION['user_role']) || strtolower($_SESSION['user_role']) !== 'admin') {
    header("Location: ../index.php");
    exit();
}

// Get admin full name from session
$admin_full_name = isset($_SESSION['full_name']) ? $_SESSION['full_name'] : 'Admin';

// Handle delete actions for books and authors
if (isset($_GET['delete_book_id'])) {
    $delete_id = (int)$_GET['delete_book_id'];
    $stmt = $pdo->prepare("DELETE FROM books WHERE book_id = ?");
    $stmt->execute([$delete_id]);
    header("Location: dashboard.php");
    exit();
}

if (isset($_GET['delete_author_id'])) {
    $delete_id = (int)$_GET['delete_author_id'];
    // Delete related books first to avoid foreign key constraint issues (if any)
    $stmt = $pdo->prepare("DELETE FROM books WHERE author_id = ?");
    $stmt->execute([$delete_id]);
    // Then delete author
    $stmt = $pdo->prepare("DELETE FROM authors WHERE author_id = ?");
    $stmt->execute([$delete_id]);
    header("Location: dashboard.php");
    exit();
}

// Handle Add or Edit Book submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Add or update book
    if (isset($_POST['book_submit'])) {
        $book_id = $_POST['book_id'] ?? null;
        $title = $_POST['title'] ?? '';
        $author_id = $_POST['author_id'] ?? null;
        $category_id = $_POST['category_id'] ?? null;
        $price = $_POST['price'] ?? null;
        $description = $_POST['description'] ?? '';
        $publication_year = $_POST['publication_year'] ?? null;
        $rating = $_POST['rating'] ?? null;

        if ($book_id) {
            // Update book
            $stmt = $pdo->prepare("UPDATE books SET title = ?, author_id = ?, category_id = ?, price = ?, description = ?, publication_year = ?, rating = ? WHERE book_id = ?");
            $stmt->execute([$title, $author_id, $category_id, $price, $description, $publication_year, $rating, $book_id]);
        } else {
            // Insert new book
            $stmt = $pdo->prepare("INSERT INTO books (title, author_id, category_id, price, description, publication_year, rating) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$title, $author_id, $category_id, $price, $description, $publication_year, $rating]);
        }
        header("Location: dashboard.php");
        exit();
    }

    // Add or update author
    if (isset($_POST['author_submit'])) {
        $author_id = $_POST['author_id'] ?? null;
        $name = $_POST['name'] ?? '';
        $biography = $_POST['biography'] ?? '';
        $cover_image_author = $_POST['cover_image_author'] ?? '';
        $author_introduction = $_POST['author_introduction'] ?? '';

        if ($author_id) {
            // Update author
            $stmt = $pdo->prepare("UPDATE authors SET name = ?, biography = ?, cover_image_author = ?, author_introduction = ? WHERE author_id = ?");
            $stmt->execute([$name, $biography, $cover_image_author, $author_introduction, $author_id]);
        } else {
            // Insert new author
            $stmt = $pdo->prepare("INSERT INTO authors (name, biography, cover_image_author, author_introduction) VALUES (?, ?, ?, ?)");
            $stmt->execute([$name, $biography, $cover_image_author, $author_introduction]);
        }
        header("Location: dashboard.php");
        exit();
    }
}

// Fetch non-admin users
$users_stmt = $pdo->prepare("SELECT * FROM users WHERE role = 'user' ORDER BY created_at DESC");
$users_stmt->execute();
$users = $users_stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch counts
$total_users = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$total_books = $pdo->query("SELECT COUNT(*) FROM books")->fetchColumn();
$total_authors = $pdo->query("SELECT COUNT(*) FROM authors")->fetchColumn();

// Fetch books and authors for display
$books_stmt = $pdo->query("SELECT b.*, a.name AS author_name FROM books b LEFT JOIN authors a ON b.author_id = a.author_id ORDER BY b.created_at DESC");
$books = $books_stmt->fetchAll(PDO::FETCH_ASSOC);

$authors_stmt = $pdo->query("SELECT * FROM authors ORDER BY created_at DESC");
$authors = $authors_stmt->fetchAll(PDO::FETCH_ASSOC);

require_once("../fragments_pages/header.php");
include('../fragments_pages/navbar.php');
?>

<!-- Bootstrap + Icons + Chart.js -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

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
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 20px rgba(0,0,0,0.1);
    }

    .table thead th {
        background-color: #2d2d2d;
        color: white;
        font-weight: 600;
    }

    .dataTables_wrapper {
        margin-top: 1cm;
    }

    .dataTables_wrapper .dataTables_filter input {
        border: 2px solid #6c757d !important;
        border-radius: 25px;
        padding: 8px 20px;
        margin-left: 10px;
        transition: all 0.3s ease;
    }

    .dataTables_wrapper .dataTables_filter input:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(108, 117, 125, 0.25);
    }

    .dt-buttons .btn {
        background-color: transparent;
        color: #2d2d2d !important;
        border: 2px solid #6c757d !important;
        border-radius: 25px;
        padding: 8px 20px;
        transition: all 0.3s ease;
    }

    .dt-buttons .btn:hover {
        background-color: #0d6efd !important;
        border-color: #0d6efd !important;
        color: white !important;
    }

    .chart-container {
        background: white;
        border-radius: 1rem;
        padding: 20px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    }

    .bg-warning.text-white h2,
    .bg-warning.text-white p {
        color: white !important;
    }

    .action-icons i {
        cursor: pointer;
        font-size: 1.3rem;
        margin: 0 5px;
    }

    .action-icons i:hover {
        color: #0d6efd;
    }
</style>

<div class="container my-5">
    <!-- Existing Dashboard Header and Stats -->
    <div class="text-center mb-4">
        <h1 class="dashboard-header">
            Bienvenue, <?= htmlspecialchars($admin_full_name); ?> !
        </h1>
        <div class="dashboard-title">
            <i class="bi bi-speedometer2 me-2"></i>Tableau de Bord
        </div>
    </div>

    <div class="row text-center mb-5">
        <div class="col-md-4 mb-4">
            <div class="card p-4 bg-primary text-white">
                <h2><i class="bi bi-people"></i> <?= $total_users; ?></h2>
                <p class="mb-0">Utilisateurs</p>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card p-4 bg-success text-white">
                <h2><i class="bi bi-book"></i> <?= $total_books; ?></h2>
                <p class="mb-0">Livres</p>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card p-4 bg-warning text-white">
                <h2><i class="bi bi-person-lines-fill"></i> <?= $total_authors; ?></h2>
                <p class="mb-0">Auteurs</p>
            </div>
        </div>
    </div>

    <!-- In your HTML after the stats cards -->
<div class="card border-0 shadow mb-5">
    <div class="card-body">
        <h3 class="text-center mb-4">STATISTIQUES GLOBALES</h3>
        <div id="column_chart" style="width: 100%; height: 500px;"></div>
    </div>
</div>

<!-- Load Google Charts -->
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Load Google Charts
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        // Verify data values
        const users = <?= $total_users ?>;
        const books = <?= $total_books ?>;
        const authors = <?= $total_authors ?>;

        console.log('Chart Data:', { users, books, authors });

        // Create data table
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Category');
        data.addColumn('number', 'Count');
        data.addRows([
            ['Utilisateurs', users],
            ['Livres', books],
            ['Auteurs', authors]
        ]);

        // Chart options
        var options = {
            title: 'STATISTIQUES GLOBALES',
            titleTextStyle: {
                fontSize: 20,
                bold: true,
                color: '#2d2d2d'
            },
            backgroundColor: 'transparent',
            colors: ['#0d6efd', '#198754', '#ffc107'], // Blue, Green, Yellow
            chartArea: {
                width: '80%',
                height: '70%',
                backgroundColor: '#ffffff'
            },
            hAxis: {
                title: 'Catégories',
                titleTextStyle: {
                    color: '#6c757d',
                    fontSize: 14,
                    bold: true
                },
                textStyle: {
                    color: '#6c757d'
                }
            },
            vAxis: {
                title: 'Nombre',
                titleTextStyle: {
                    color: '#6c757d',
                    fontSize: 14,
                    bold: true
                },
                minValue: 0,
                gridlines: {
                    color: '#e9ecef'
                }
            },
            legend: { position: 'none' },
            animation: {
                startup: true,
                duration: 1000,
                easing: 'out'
            },
            bar: {
                groupWidth: '60%'
            }
        };

        try {
            var chart = new google.visualization.ColumnChart(
                document.getElementById('column_chart')
            );
            chart.draw(data, options);
        } catch (error) {
            console.error('Chart Error:', error);
        }
    }

    // Handle window resize
    let resizeTimer;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(drawChart, 100);
    });
});
</script>

   <!-- Latest Users Table with Modals -->
<div class="card border-0 shadow mb-5">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="text-center mb-0">Dernières Inscriptions</h3>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                <i class="bi bi-plus-circle"></i> Ajouter un utilisateur
            </button>
        </div>
        <table id="table-inscriptions" class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom d'utilisateur</th>
                    <th>Nom complet</th>
                    <th>Email</th>
                    <th>Date d'inscription</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= htmlspecialchars($user['user_id']) ?></td>
                    <td><?= htmlspecialchars($user['username']) ?></td>
                    <td><?= htmlspecialchars($user['full_name']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td><?= htmlspecialchars($user['created_at']) ?></td>
                    <td>
                        <button class="btn btn-sm btn-warning editBtn" 
                                data-user='<?= json_encode($user) ?>'
                                data-bs-toggle="modal" data-bs-target="#editUserModal">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                        <button class="btn btn-sm btn-danger deleteBtn" 
                                data-id="<?= $user['user_id'] ?>" 
                                data-bs-toggle="modal" data-bs-target="#deleteUserModal">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" action="add_user.php" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Ajouter un utilisateur</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input name="username" class="form-control mb-3" placeholder="Nom d'utilisateur" required>
        <input name="full_name" class="form-control mb-3" placeholder="Nom complet" required>
        <input name="email" type="email" class="form-control mb-3" placeholder="Email" required>
        <input name="password" type="password" class="form-control mb-3" placeholder="Mot de passe" required>
      </div>
      <div class="modal-footer">
        <button class="btn btn-success">Ajouter</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
      </div>
    </form>
  </div>
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" action="edit_user.php" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Modifier l'utilisateur</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="user_id" id="edit_user_id">
        <input name="username" class="form-control mb-3" id="edit_username" placeholder="Nom d'utilisateur" required>
        <input name="full_name" class="form-control mb-3" id="edit_full_name" placeholder="Nom complet" required>
        <input name="email" type="email" class="form-control mb-3" id="edit_email" placeholder="Email" required>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary">Enregistrer</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
      </div>
    </form>
  </div>
</div>

<!-- Delete User Modal -->
<div class="modal fade" id="deleteUserModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" action="delete_user.php" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Supprimer l'utilisateur</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p>Êtes-vous sûr de vouloir supprimer cet utilisateur ?</p>
        <input type="hidden" name="user_id" id="delete_user_id">
      </div>
      <div class="modal-footer">
        <button class="btn btn-danger">Supprimer</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
      </div>
    </form>
  </div>
</div>

<!-- JavaScript to fill edit/delete modals -->
<script>
    document.querySelectorAll('.editBtn').forEach(btn => {
        btn.addEventListener('click', () => {
            const user = JSON.parse(btn.getAttribute('data-user'));
            document.getElementById('edit_user_id').value = user.user_id;
            document.getElementById('edit_username').value = user.username;
            document.getElementById('edit_full_name').value = user.full_name;
            document.getElementById('edit_email').value = user.email;
        });
    });

    document.querySelectorAll('.deleteBtn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.getElementById('delete_user_id').value = btn.getAttribute('data-id');
        });
    });
</script>

    <!-- === BOOKS MANAGEMENT SECTION === -->
    <div class="card border-0 shadow mb-5">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3>Gestion des Livres</h3>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#bookModal" onclick="openBookModal()">Ajouter un Livre</button>
            </div>
            <table id="books-table" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Titre</th>
                        <th>Auteur</th>
                        <th>Categorie</th>
                        <th>Prix</th>
                        <th>Description</th>
                        <th>Année</th>
                        <th>Note</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($books as $book): ?>
                    <tr>
                        <td><?= htmlspecialchars($book['book_id']) ?></td>
                        <td><?= htmlspecialchars($book['title']) ?></td>
                        <td><?= htmlspecialchars($book['author_name'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($book['category_id']) ?></td>
                        <td><?= htmlspecialchars($book['price']) ?></td>
                        <td><?= htmlspecialchars($book['description']) ?></td>
                        <td><?= htmlspecialchars($book['publication_year']) ?></td>
                        <td><?= htmlspecialchars($book['rating']) ?></td>
                        <td class="action-icons">
                            <i class="bi bi-pencil-square text-primary" style="cursor:pointer;" onclick="openBookModal(<?= $book['book_id'] ?>)"></i>
                            <a href="dashboard.php?delete_book_id=<?= $book['book_id'] ?>" onclick="return confirm('Supprimer ce livre ?')" class="text-danger"><i class="bi bi-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- === AUTHORS MANAGEMENT SECTION === -->
    <div class="card border-0 shadow mb-5">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3>Gestion des Auteurs</h3>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#authorModal" onclick="openAuthorModal()">Ajouter un Auteur</button>
            </div>
            <table id="authors-table" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Biographie</th>
                        <th>Image de Couverture</th>
                        <th>Introduction</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($authors as $author): ?>
                    <tr>
                        <td><?= htmlspecialchars($author['author_id']) ?></td>
                        <td><?= htmlspecialchars($author['name']) ?></td>
                        <td><?= htmlspecialchars($author['biography']) ?></td>
                        <td><?= htmlspecialchars($author['cover_image_author']) ?></td>
                        <td><?= htmlspecialchars($author['author_introduction']) ?></td>
                        <td class="action-icons">
                            <i class="bi bi-pencil-square text-primary" style="cursor:pointer;" onclick="openAuthorModal(<?= $author['author_id'] ?>)"></i>
                            <a href="dashboard.php?delete_author_id=<?= $author['author_id'] ?>" onclick="return confirm('Supprimer cet auteur ? Cette action supprimera aussi ses livres.')" class="text-danger"><i class="bi bi-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- === MODALS === -->

<!-- Book Modal -->
<div class="modal fade" id="bookModal" tabindex="-1" aria-labelledby="bookModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form method="post" id="bookForm" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="bookModalLabel">Ajouter / Modifier Livre</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>
      <div class="modal-body">
          <input type="hidden" name="book_id" id="book_id" value="">
          <div class="mb-3">
              <label for="title" class="form-label">Titre</label>
              <input type="text" class="form-control" id="title" name="title" required>
          </div>
          <div class="mb-3">
              <label for="author_id" class="form-label">Auteur</label>
              <select class="form-select" id="author_id" name="author_id" required>
                <option value="">-- Sélectionnez un auteur --</option>
                <?php foreach ($authors as $author): ?>
                    <option value="<?= $author['author_id'] ?>"><?= htmlspecialchars($author['name']) ?></option>
                <?php endforeach; ?>
              </select>
          </div>
          <div class="mb-3">
              <label for="category_id" class="form-label">Catégorie (ID)</label>
              <input type="number" class="form-control" id="category_id" name="category_id" required>
          </div>
          <div class="mb-3">
              <label for="price" class="form-label">Prix</label>
              <input type="number" step="0.01" class="form-control" id="price" name="price" required>
          </div>
          <div class="mb-3">
              <label for="description" class="form-label">Description</label>
              <textarea class="form-control" id="description" name="description"></textarea>
          </div>
          <div class="mb-3">
              <label for="publication_year" class="form-label">Année de publication</label>
              <input type="number" class="form-control" id="publication_year" name="publication_year">
          </div>
          <div class="mb-3">
              <label for="rating" class="form-label">Note</label>
              <input type="number" step="0.1" min="0" max="5" class="form-control" id="rating" name="rating">
          </div>
      </div>
      <div class="modal-footer">
        <button type="submit" name="book_submit" class="btn btn-success">Enregistrer</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
      </div>
    </form>
  </div>
</div>

<!-- Author Modal -->
<div class="modal fade" id="authorModal" tabindex="-1" aria-labelledby="authorModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form method="post" id="authorForm" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="authorModalLabel">Ajouter / Modifier Auteur</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>
      <div class="modal-body">
          <input type="hidden" name="author_id" id="author_id" value="">
          <div class="mb-3">
              <label for="name" class="form-label">Nom</label>
              <input type="text" class="form-control" id="name" name="name" required>
          </div>
          <div class="mb-3">
              <label for="biography" class="form-label">Biographie</label>
              <textarea class="form-control" id="biography" name="biography"></textarea>
          </div>
          <div class="mb-3">
              <label for="cover_image_author" class="form-label">Image de couverture (URL)</label>
              <input type="text" class="form-control" id="cover_image_author" name="cover_image_author">
          </div>
          <div class="mb-3">
              <label for="author_introduction" class="form-label">Introduction</label>
              <textarea class="form-control" id="author_introduction" name="author_introduction"></textarea>
          </div>
      </div>
      <div class="modal-footer">
        <button type="submit" name="author_submit" class="btn btn-success">Enregistrer</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
      </div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function() {
    $('#table-inscriptions').DataTable({
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json'
        }
    });
    $('#books-table').DataTable({
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json'
        },
        order: [[0, 'desc']],
        dom: 'Bfrtip',
        buttons: ['print']
    });
    $('#authors-table').DataTable({
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json'
        },
        order: [[0, 'desc']],
        dom: 'Bfrtip',
        buttons: ['print']
    });
});

// Open Book modal, optionally fill with book data
function openBookModal(bookId = null) {
    const modal = new bootstrap.Modal(document.getElementById('bookModal'));
    if (bookId) {
        // Find book data from PHP (inject JS object)
        let bookData = <?= json_encode($books) ?>.find(b => b.book_id == bookId);
        if (bookData) {
            $('#book_id').val(bookData.book_id);
            $('#title').val(bookData.title);
            $('#author_id').val(bookData.author_id);
            $('#category_id').val(bookData.category_id);
            $('#price').val(bookData.price);
            $('#description').val(bookData.description);
            $('#publication_year').val(bookData.publication_year);
            $('#rating').val(bookData.rating);
        }
    } else {
        $('#bookForm')[0].reset();
        $('#book_id').val('');
    }
    modal.show();
}

// Open Author modal, optionally fill with author data
function openAuthorModal(authorId = null) {
    const modal = new bootstrap.Modal(document.getElementById('authorModal'));
    if (authorId) {
        let authorData = <?= json_encode($authors) ?>.find(a => a.author_id == authorId);
        if (authorData) {
            $('#author_id').val(authorData.author_id);
            $('#name').val(authorData.name);
            $('#biography').val(authorData.biography);
            $('#cover_image_author').val(authorData.cover_image_author);
            $('#author_introduction').val(authorData.author_introduction);
        }
    } else {
        $('#authorForm')[0].reset();
        $('#author_id').val('');
    }
    modal.show();
}
</script>

</body>
</html>
