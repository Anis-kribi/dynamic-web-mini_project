<?php
session_start();

// Remove the restriction so everyone can access (admin or not)

// Optional: You can still check if the user is an admin, if needed
$isAdmin = isset($_SESSION['user_role']) && strtolower($_SESSION['user_role']) === 'admin';

require_once("../fragments_pages/header.php");
require_once("../fragments_pages/navbar.php");
require_once("../dbconnect.php");  // Make sure $pdo is properly configured with ERRMODE_EXCEPTION

$responseMessage = "";

// Helper function to sanitize output
function e($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    // Basic validation
    if ($nom === '' || $email === '' || $subject === '' || $message === '') {
        $responseMessage = "<div class='alert alert-warning text-center'>Tous les champs sont obligatoires.</div>";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $responseMessage = "<div class='alert alert-warning text-center'>Adresse email invalide.</div>";
    } else {
        try {
            // Use the new table name "contacts"
            $stmt = $pdo->prepare("INSERT INTO contacts (nom, email, subject, message) VALUES (?, ?, ?, ?)");
            if ($stmt->execute([$nom, $email, $subject, $message])) {
                $responseMessage = "<div class='alert alert-success text-center'>Votre message a été envoyé avec succès.</div>";
                // Optionally clear POST to reset form fields (not shown here)
            } else {
                $responseMessage = "<div class='alert alert-danger text-center'>Une erreur s'est produite lors de l'envoi du message.</div>";
            }
        } catch (PDOException $e) {
            $responseMessage = "<div class='alert alert-danger text-center'>Erreur : " . e($e->getMessage()) . "</div>";
        }
    }
}
?>

<main class="main">

  <!-- Titre de la page -->
  <div class="page-title" data-aos="fade">
    <div class="heading py-4 bg-light">
      <div class="container">
        <div class="row d-flex justify-content-center text-center">
          <div class="col-lg-8">
            <h1>Contact</h1>
            <p class="mb-0">
              Pour toute question ou demande, n'hésitez pas à nous contacter. Nous sommes disponibles pour vous aider
              et répondre à toutes vos préoccupations.
            </p>
          </div>
        </div>
      </div>
    </div>
    <nav class="breadcrumbs">
      <div class="container">
        <ol>
          <li><a href="../index.php">Accueil</a></li>
          <li class="current">Contact</li>
        </ol>
      </div>
    </nav>
  </div>

  <!-- Section Contact -->
  <section id="contact" class="contact section">

    <!-- Carte Google Map centrée sur Nabeul -->
    <div class="mb-5" data-aos="fade-up" data-aos-delay="200">
      <iframe style="border:0; width: 100%; height: 350px;"
        src="https://www.google.com/maps?q=Nabeul,Tunisie&output=embed"
        frameborder="0" allowfullscreen="" loading="lazy"></iframe>
    </div>

    <div class="container" data-aos="fade-up" data-aos-delay="100">
      <div class="row gy-4">

        <div class="col-lg-4">
          <div class="info-item d-flex align-items-start mb-4">
            <i class="bi bi-geo-alt fs-4 me-3 text-white"></i> <!-- Icône blanche -->
            <div>
              <h5>Adresse</h5>
              <p>Nabeul, Tunisie</p>
            </div>
          </div>

          <div class="info-item d-flex align-items-start mb-4">
            <i class="bi bi-telephone fs-4 me-3 text-white"></i> <!-- Icône blanche -->
            <div>
              <h5>Téléphone</h5>
              <p>+216 00 000 000</p>
            </div>
          </div>

          <div class="info-item d-flex align-items-start mb-4">
            <i class="bi bi-envelope fs-4 me-3 text-white"></i> <!-- Icône blanche -->
            <div>
              <h5>Email</h5>
              <p>contact@monsite.com</p>
            </div>
          </div>
        </div>

        <div class="col-lg-8">
          <?php if (!empty($responseMessage)) echo $responseMessage; ?>

          <form action="contact.php" method="post" novalidate>
            <div class="row gy-4">

              <div class="col-md-6">
                <input type="text" name="name" class="form-control" placeholder="Votre nom" required
                  value="<?php echo e($_POST['name'] ?? '') ?>">
              </div>

              <div class="col-md-6">
                <input type="email" class="form-control" name="email" placeholder="Votre adresse email" required
                  value="<?php echo e($_POST['email'] ?? '') ?>">
              </div>

              <div class="col-md-12">
                <input type="text" class="form-control" name="subject" placeholder="Objet" required
                  value="<?php echo e($_POST['subject'] ?? '') ?>">
              </div>

              <div class="col-md-12">
                <textarea class="form-control" name="message" rows="6" placeholder="Votre message" required><?php echo e($_POST['message'] ?? '') ?></textarea>
              </div>

              <div class="col-md-12 text-center">
                <button type="submit" class="btn btn-primary px-4 py-2">Envoyer le message</button>
              </div>

            </div>
          </form>
        </div>

      </div>
    </div>

  </section>
</main>

<?php
require_once("../fragments_pages/footer.php");
?>
