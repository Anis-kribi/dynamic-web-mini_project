<?php
session_start();

// Remove the restriction so everyone can access (admin or not)

// Optional: You can still check if the user is an admin, if needed
$isAdmin = isset($_SESSION['user_role']) && strtolower($_SESSION['user_role']) === 'admin';

require_once("../fragments_pages/header.php");
require_once("../fragments_pages/navbar.php");
require_once("../dbconnect.php");  // Make sure $pdo is properly configured with ERRMODE_EXCEPTION


// Get total number of books
$sqlBooks = "SELECT COUNT(*) AS total_books FROM books";
$queryBooks = $pdo->query($sqlBooks);
$books = $queryBooks->fetch(PDO::FETCH_ASSOC);

// Get total number of authors
$sqlAuthors = "SELECT COUNT(*) AS total_authors FROM authors";
$queryAuthors = $pdo->query($sqlAuthors);
$authors = $queryAuthors->fetch(PDO::FETCH_ASSOC);
?>

<main class="main">

  <div class="page-title" data-aos="fade">
    <div class="heading">
      <div class="container">
        <div class="row justify-content-center text-center">
          <div class="col-lg-8">
            <h1>À propos de notre site</h1>
            <p class="mb-0">Découvrez une vaste sélection de livres, des classiques aux nouveautés. Nous vous offrons une expérience d'achat pratique, rapide et sécurisée.</p>
          </div>
        </div>
      </div>
    </div>
    <nav class="breadcrumbs">
      <div class="container">
        <ol>
          <li><a href="../index.php">Accueil</a></li>
          <li class="current">À propos</li>
        </ol>
      </div>
    </nav>
  </div>

  <section id="about-us" class="section about-us">
    <div class="container">
      <div class="row gy-4 align-items-center">
        <div class="col-lg-6" data-aos="fade-up">
          <img src="../assets/img/about-1.jpg" class="img-fluid rounded shadow" alt="Librairie">
        </div>
        <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
          <h3>Notre mission : Partager l’amour de la lecture</h3>
          <p class="fst-italic">
            Nous proposons des livres de tous genres : fiction, non-fiction, romans, science, et plus encore, pour satisfaire chaque lecteur.
          </p>
          <ul class="list-unstyled">
            <li><i class="bi bi-check-circle text-success"></i> Un large choix de livres, 24h/24 et 7j/7.</li>
            <li><i class="bi bi-check-circle text-success"></i> Recommandations personnalisées selon vos goûts.</li>
            <li><i class="bi bi-check-circle text-success"></i> Service client prêt à vous assister à tout moment.</li>
          </ul>
        </div>
      </div>
    </div>
  </section>

  <section id="counts" class="section counts bg-light py-5">
    <div class="container">
      <div class="row gy-4 text-center">
        <div class="col-lg-6 col-md-6" data-aos="fade-up">
          <div class="stats-item">
            <span data-purecounter-start="0" data-purecounter-end="<?= htmlspecialchars($books['total_books']) ?>" data-purecounter-duration="1" class="purecounter"></span>
            <p>Livres disponibles</p>
          </div>
        </div>
        <div class="col-lg-6 col-md-6" data-aos="fade-up" data-aos-delay="100">
          <div class="stats-item">
            <span data-purecounter-start="0" data-purecounter-end="<?= htmlspecialchars($authors['total_authors']) ?>" data-purecounter-duration="1" class="purecounter"></span>
            <p>Auteurs référencés</p>
          </div>
        </div>
      </div>
    </div>
  </section>

<section id="testimonials" class="testimonials section py-5">
  <div class="container">
    <div class="section-title text-center" data-aos="fade-up">
      <h2>Conseils Lecture</h2>
      <p>Pourquoi lire des livres et des romans ?</p>
    </div>
    <div class="swiper init-swiper" data-aos="fade-up" data-aos-delay="100">
      <script type="application/json" class="swiper-config">
        {
          "loop": true,
          "speed": 600,
          "autoplay": { "delay": 5000 },
          "slidesPerView": "auto",
          "pagination": { "el": ".swiper-pagination", "clickable": true },
          "breakpoints": {
            "320": { "slidesPerView": 1, "spaceBetween": 20 },
            "1200": { "slidesPerView": 2, "spaceBetween": 30 }
          }
        }
      </script>
      <div class="swiper-wrapper">

        <!-- Advice Slide -->
        <div class="swiper-slide">
          <div class="testimonial-wrap p-3">
            <div class="testimonial-item">
             <img src="../assets/img/read_books.jpg" class="testimonial-img rounded-circle mb-3" alt="Lire des livres">

              <h4>Lisez chaque jour</h4>
              <p class="small fst-italic">
                <i class="bi bi-quote quote-icon-left"></i>
                Lire des livres et des romans enrichit l'esprit, développe l'empathie et améliore la concentration. Que ce soit du mystère, de la science-fiction ou des classiques, chaque page vous ouvre un nouveau monde.
                <i class="bi bi-quote quote-icon-right"></i>
              </p>
            </div>
          </div>
        </div>

        <!-- Add more advice slides if needed -->

      </div>
      <div class="swiper-pagination"></div>
    </div>
  </div>
</section>


</main>

<?php
require_once("../fragments_pages/footer.php");
?>
