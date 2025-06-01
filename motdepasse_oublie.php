<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once("../fragments_pages/header.php");
require_once("../fragments_pages/navbar.php");
?>

<div class="d-flex justify-content-center align-items-center vh-100 bg-light">
    <div class="card mb-3 shadow-lg" style="max-width: 800px;">
        <div class="row g-0 d-flex align-items-center">
            <!-- Image -->
            <div class="col-lg-4 d-none d-lg-flex">
                <img src="../assets/img/forgot-password.jpg"
                    style="width: 245px; height: 600px; object-fit: cover; border-top-left-radius: .5rem; border-bottom-left-radius: .5rem;"
                     alt="Password recovery">
            </div>

            <!-- Form -->
            <div class="col-lg-8">
                <div class="card-body py-5 px-md-5">
                    <h3 class="text-center mb-4">Mot de passe oublié</h3>

                    <?php
                    if (isset($_GET['message'])) {
                        echo '<div class="alert alert-success">' . htmlspecialchars($_GET['message']) . '</div>';
                    } elseif (isset($_GET['error'])) {
                        echo '<div class="alert alert-danger">' . htmlspecialchars($_GET['error']) . '</div>';
                    }
                    ?>

                    <form action="traitement_motdepasse_oublie.php" method="POST">
                        <div class="form-outline mb-4">
                            <label class="form-label mb-0" for="email">Adresse email</label>
                            <input type="email" name="email" id="email" class="form-control" required />
                        </div>

                        <!-- Google reCAPTCHA -->
                        <div class="mb-3">
                            <div class="g-recaptcha" data-sitekey="6Lew5UgrAAAAAP4RquHfn8n2kEpFJRPn89E4rRgj"></div>
                        </div>

                        <button type="submit" class="btn bg-secondary text-white btn-block w-100">Envoyer le lien de réinitialisation</button>
                    </form>

                    <div class="mt-4 text-center">
                        <a href="connexion.php">Retour à la connexion</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>

<?php
require_once("../fragments_pages/footer.php");
?>
