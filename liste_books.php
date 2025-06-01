 <?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
require_once("../fragments_pages/header.php");
require_once("../fragments_pages/navbar.php");

require_once("../dbconnect.php");


// Vérification de l'authentification de l'admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
  header("Location: ../index.php");
  exit();
}

// Nombre d'éléments par page
$limite = 5;

// Déterminer la page actuelle (par défaut = 1)
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limite;

// Compter le nombre total de cours
$totalCours = $pdo->query("SELECT COUNT(*) FROM cours")->fetchColumn();
$totalPages = ceil($totalCours / $limite);

// Récupérer la liste des cours avec pagination
$stmt = $pdo->prepare("
    SELECT *, cat.nom_categorie 
    FROM cours c 
    JOIN categories cat ON c.id_categorie = cat.id_categorie 
    LIMIT :offset, :limite
");
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->bindParam(':limite', $limite, PDO::PARAM_INT);
$stmt->execute();
$cours = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<style>
  table {
        font-size: 0.85rem; /* Réduire la taille du texte */
    }
    table td, table th {
        padding: 5px; /* Réduire le padding des cellules */
        vertical-align: middle; /* Centrer verticalement le texte */
    }
    .table img {
        display: block;
        margin: auto;
        max-height: 40px; /* Taille réduite pour les images */
    }
    .pagination .page-item.active .page-link {
    background-color: #198754 !important; /* Vert Bootstrap (success) */
    border-color: #198754 !important;
    color: white !important;
}
td {
    word-wrap: break-word; /* Casse les mots longs */
    white-space: normal; /* Permet le retour à la ligne */
    max-width: 200px; /* Définit une largeur maximale pour éviter les cellules trop larges */
}

</style>

    <div class="container mt-5">
        <h2 class="text-center">Gestion des Cours</h2>
        <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addCourseModal">Ajouter un Cours</button>
        
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Catégorie</th>
                    <th>Titre</th>
                    <th>Description</th>
                    <th>Image</th>
                    <th>Inscrits</th>
                    <th>Likes</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($cours as $row): ?>
                    <tr>
                        <td><?= $row['id_cours'] ?></td>
                        <td><?= $row['nom_categorie'] ?></td>
                        <td><?= $row['titre_cours'] ?></td>
                        <td><?= $row['description_cours'] ?></td>
                        <td><img src="../uploads/<?= $row['image_cours'] ?>" width="60"></td>
                        <td><?= $row['nombre_inscrits'] ?></td>
                        <td><?= $row['nombre_likes'] ?></td>
                        <td>
                    <button class="btn btn-warning btn-sm edit-btn" data-id="<?php echo $row['id_cours']; ?>">
                        <i class="bi bi-pencil"></i>
                    </button>
                    <button class="btn btn-danger btn-sm delete-btn" data-id="<?php echo $row['id_cours']; ?>">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <nav>
    <ul class="pagination justify-content-center">
        <?php if ($page > 1): ?>
            <li class="page-item">
                <a class="page-link text-success" href="?page=<?= $page - 1 ?>">Précédent</a>
            </li>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?= ($i == $page) ? 'active bg-success' : '' ?>">
                <a class="page-link <?= ($i == $page) ? 'text-white' : 'text-success' ?>" href="?page=<?= $i ?>">
                    <?= $i ?>
                </a>
            </li>
        <?php endfor; ?>

        <?php if ($page < $totalPages): ?>
            <li class="page-item">
                <a class="page-link text-success" href="?page=<?= $page + 1 ?>">Suivant</a>
            </li>
        <?php endif; ?>
    </ul>
</nav>


    </div>
<?php
// Récupérer les catégories depuis la base de données
$query = $pdo->query("SELECT id_categorie, nom_categorie FROM categories");
$categories = $query->fetchAll(PDO::FETCH_ASSOC);
?>
    <!-- MODALE : Ajout d'un cours -->
<div class="modal fade" id="addCourseModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajouter un Cours</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="add_cours.php" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label>Catégorie</label>
                        <select name="id_categorie" class="form-control" required>
                            <option value="">-- Sélectionner une catégorie --</option>
                            <?php foreach ($categories as $categorie) : ?>
                                <option value="<?= $categorie['id_categorie'] ?>"><?= htmlspecialchars($categorie['nom_categorie']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Titre</label>
                        <input type="text" name="titre_cours" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Description</label>
                        <textarea name="description_cours" class="form-control" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Image</label>
                        <input type="file" name="image_cours" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-success">Ajouter</button>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- MODALE : Modification d'un cours -->
<div class="modal fade" id="editCourseModal" tabindex="-1" aria-labelledby="editCourseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCourseModalLabel">Modifier un Cours</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="edit_cours.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id_cours" id="edit-id">
                    <div class="row">
                        <!-- Première colonne (Champs texte) -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit-categorie">Catégorie</label>
                                <select name="id_categorie" id="edit-categorie" class="form-control" required>
                                    <option value="">-- Sélectionner une catégorie --</option>
                                    <?php foreach ($categories as $categorie) : ?>
                                        <option value="<?= $categorie['id_categorie'] ?>"><?= htmlspecialchars($categorie['nom_categorie']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="edit-titre">Titre</label>
                                <input type="text" name="titre_cours" id="edit-titre" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit-description">Description</label>
                                <textarea name="description_cours" id="edit-description" class="form-control" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="edit-image-upload" class="form-label">Modifier l'image</label>
                                <input type="file" class="form-control" id="edit-image-upload" name="image_cours">
                            </div>
                        </div>

                        <!-- Deuxième colonne (Image et modification de l'image) -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit-image" class="form-label">Image actuelle</label>
                                <img id="edit-image" src="" alt="Image actuelle" class="img-fluid mb-2">
                            </div>
                            
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success">Mettre à jour</button>
                </form>
            </div>
        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- SCRIPT POUR REMPLIR LA MODALE D'ÉDITION -->
    <script>
        $(document).ready(function() {
            $(".edit-btn").click(function() {
                $("#edit-id").val($(this).data("id"));
                $("#edit-categorie").val($(this).data("categorie"));
                $("#edit-titre").val($(this).data("titre"));
                $("#edit-description").val($(this).data("description"));
            });
        });
    </script>


<script>
$(document).ready(function () {
    $(".edit-btn").click(function () {
        var id_cours = $(this).data("id"); // Récupérer l'ID du cours
        console.log("ID du cours cliqué :", id_cours); // Vérifier si l'ID est bien récupéré

        // Requête AJAX pour récupérer les infos du cours
        $.ajax({
            url: "get_cours.php",
            type: "POST",
            data: { id_cours: id_cours },
            dataType: "json",
            success: function (data) {
                $("#edit-id").val(data.id_cours);
                $("#edit-categorie").val(data.id_categorie);
                $("#edit-titre").val(data.titre_cours);
                $("#edit-description").val(data.description_cours);
// Ajouter l'image dans le modal
if (data.image_cours) {
                    $("#edit-image").attr("src", "../uploads/" + data.image_cours); // Met à jour l'élément <img> avec le chemin de l'image
                } else {
                    $("#edit-image").attr("src", "path/to/default-image.jpg"); // Met une image par défaut si aucune image n'est définie
                }
                // Ouvrir le modal après chargement des données
                $("#editCourseModal").modal("show");
            },
            error: function () {
                alert("Erreur lors du chargement des données.");
            }
        });
    });
});
</script>

<?php
require_once("../fragments_pages/footer.php");