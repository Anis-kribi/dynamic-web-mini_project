<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once("../fragments_pages/header.php");
require_once("../fragments_pages/navbar.php");
require_once("../dbconnect.php");
?>

<div class="d-flex justify-content-center align-items-center vh-100 bg-light">
    <div class="card mb-3 shadow-lg" style="max-width: 800px;">
        <div class="row g-0 d-flex align-items-center">
            <!-- Image -->
            <div class="col-lg-4 d-none d-lg-flex">
                <img src="../assets/img/cover.jpg"
                    style="width: 270px; height: 600px; object-fit: cover; border-top-left-radius: .5rem; border-bottom-left-radius: .5rem;"
                    alt="Library full of books">
            </div>

            <!-- Form -->
            <div class="col-lg-8">
                <div class="card-body py-5 px-md-5">
                    <h3 class="text-center mb-4">Connexion</h3>

                    <?php
                    if (isset($_GET['error'])) {
                        echo '<div class="alert alert-danger">' . htmlspecialchars($_GET['error']) . '</div>';
                    }
                    ?>

                    <form action="traitement_connexion.php" method="POST">
                        <!-- Email -->
                        <div class="form-outline mb-4">
                            <label class="form-label mb-0" for="email">Adresse email</label>
                            <input type="email" name="email" id="email" class="form-control" required />
                        </div>

                        <!-- Mot de passe -->
                        <div class="form-outline mb-4">
                            <label class="form-label mb-0" for="password">Mot de passe</label>
                            <input type="password" name="password" id="password" class="form-control" required />
                        </div>

                        <!-- Se souvenir -->
                        <div class="row mb-4">
                            <div class="col d-flex justify-content-center">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="remember_me" name="remember_me" />
                                    <label class="form-check-label" for="remember_me"> Se souvenir de moi </label>
                                </div>
                            </div>
                            <div class="col text-end">
                                <a href="motdepasse_oublie.php">Mot de passe oublié ?</a>
                            </div>
                        </div>

                        <!-- Google reCAPTCHA -->
                        <div class="mb-3">
                            <div class="g-recaptcha" data-sitekey="6Lew5UgrAAAAAP4RquHfn8n2kEpFJRPn89E4rRgj"></div>
                        </div>

                        <!-- Bouton de connexion -->
                        <button type="submit" class="btn bg-secondary text-white btn-block w-100">Se connecter</button>
                    </form>

                    <!-- Lien vers la page d'inscription -->
                    <div class="row mb-4">
                        <div class="col text-center">
                            <p>Vous n'avez pas de compte ? <a href="inscription.php">Inscrivez-vous ici</a></p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script Google reCAPTCHA -->

<script src="https://www.google.com/recaptcha/api.js" async defer></script>


<?php
require_once("../fragments_pages/footer.php");
?>
