<?php
session_start();
require_once("../fragments_pages/header.php");
require_once("../fragments_pages/navbar.php");
?>

<body class="index-page">
    <header id="header" class="header d-flex align-items-center sticky-top">
        <!-- Your header code here -->
    </header>

    <form action="traitement_inscription.php" method="POST">
        <section class="vh-100">
            <div class="container-fluid h-custom">
                <div class="row d-flex justify-content-center align-items-center h-100">
                    <div class="col-md-9 col-lg-6 col-xl-5">
                        <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-login-form/draw2.webp" class="img-fluid" alt="Image d'inscription">
                    </div>
                    <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">
                        <!-- Display error or success messages -->
                        <?php if (isset($_GET['error'])): ?>
                            <div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div>
                        <?php endif; ?>
                        <?php if (isset($_GET['success'])): ?>
                            <div class="alert alert-success"><?= htmlspecialchars($_GET['success']) ?></div>
                        <?php endif; ?>

                        <div class="d-flex flex-row align-items-center justify-content-center justify-content-lg-start">
                            <p class="lead fw-normal mb-0 me-3">S'inscrire avec</p>
                            <!-- Optional social buttons -->
                        </div>

                        <div class="divider d-flex align-items-center my-4">
                            <p class="text-center fw-bold mx-3 mb-0">Ou</p>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="userRole" class="form-label">Sélectionner le rôle</label>
                                <select id="userRole" name="role_utilisateur" class="form-select" required>
                                    <option selected disabled>Choisissez...</option>
                                    <option value="user">Utilisateur</option>
                                    <option value="admin">Administrateur</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="nom" class="form-label">Nom</label>
                                <input type="text" class="form-control" id="nom" name="nom" placeholder="Entrez votre nom" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="prenom" class="form-label">Prénom</label>
                                <input type="text" class="form-control" id="prenom" name="prenom" placeholder="Entrez votre prénom" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Adresse e-mail</label>
                                <input type="email" id="email" name="email" class="form-control" placeholder="Entrez une adresse e-mail valide" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="mot_de_passe" class="form-label">Mot de passe</label>
                                <input type="password" id="mot_de_passe" name="mot_de_passe" class="form-control" placeholder="Entrez un mot de passe" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="confirm_mot_de_passe" class="form-label">Confirmer le mot de passe</label>
                                <input type="password" id="confirm_mot_de_passe" name="confirm_mot_de_passe" class="form-control" placeholder="Confirmer le mot de passe" required>
                            </div>
                        </div>

                        <div class="text-center text-lg-start mt-4 pt-2">
                            <button type="submit" class="btn bg-secondary text-white btn-lg">S'inscrire</button>
                            <p class="small fw-bold mt-2 pt-1 mb-0">
                                Vous avez déjà un compte ? <a href="connexion.php">Se connecter</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </form>

    <?php require_once("../fragments_pages/footer.php"); ?>
</body>
</html>
