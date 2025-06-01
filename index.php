<?php
session_start();
require_once("dbconnect.php");

// Remove the restriction so everyone can access (admin or not)

// Optional: You can still check if the user is an admin, if needed
$isAdmin = isset($_SESSION['user_role']) && strtolower($_SESSION['user_role']) === 'admin';

require_once("fragments/header.php");
require_once("fragments/navbar.php");
?>




  <main class="main">

    <!-- Hero Section -->
    <section id="hero" class="hero section dark-background">

      <img src="assets/img/Hero-bg.jpg" alt="" data-aos="fade-in">

      <div class="container">
    <h2 data-aos="fade-up" data-aos-delay="100">Lire Aujourd'hui,<br>Grandir Demain</h2>
    <p data-aos="fade-up" data-aos-delay="200">Découvrez une collection passionnante de livres et mangas pour nourrir votre imagination.</p>
    <div class="d-flex mt-4" data-aos="fade-up" data-aos-delay="300">
      <a href="courses.html" class="btn-get-started">Découvrir</a>
    </div>
</div>


    </section><!-- /Hero Section -->

   <!-- About Section -->
<section id="about" class="about section">

<div class="container">

  <div class="row gy-4">

    <div class="col-lg-6 order-1 order-lg-2" data-aos="fade-up" data-aos-delay="100">
      <img src="assets/img/about0.jpg" class="img-fluid" alt="Notre Boutique">
    </div>

    <div class="col-lg-6 order-2 order-lg-1 content" data-aos="fade-up" data-aos-delay="200">
      <h3>Un univers dédié aux passionnés de lecture</h3>
      <p class="fst-italic">
        Notre plateforme propose une large sélection de livres et de mangas soigneusement choisis pour satisfaire chaque lecteur, du débutant au passionné.
      </p>
      <ul>
        <li><i class="bi bi-check-circle"></i> <span>Livres et mangas pour tous les âges et tous les goûts.</span></li>
        <li><i class="bi bi-check-circle"></i> <span>Offres exclusives, promotions et nouveautés régulières.</span></li>
        <li><i class="bi bi-check-circle"></i> <span>Un service simple, rapide et sécurisé pour commander vos lectures préférées en quelques clics.</span></li>
      </ul>
      <a href="#" class="read-more"><span>En savoir plus</span><i class="bi bi-arrow-right"></i></a>
    </div>

  </div>

</div>

</section>
<!-- /About Section -->



    <!-- Why Us Section -->
<section id="why-us" class="section why-us">

<div class="container">

  <div class="row gy-4">

    <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
      <div class="why-box">
        <h3>Pourquoi Choisir Notre Librairie ?</h3>
        <p>
          Découvrez une sélection unique de livres et mangas soigneusement choisis pour éveiller votre passion. Qualité, prix compétitifs et nouveautés régulières sont au rendez-vous.
          Profitez d'une expérience d'achat simple, rapide et sécurisée.
        </p>
        <div class="text-center">
          <a href="#" class="more-btn"><span>En savoir plus</span> <i class="bi bi-chevron-right"></i></a>
        </div>
      </div>
    </div><!-- End Why Box -->

    <div class="col-lg-8 d-flex align-items-stretch">
      <div class="row gy-4" data-aos="fade-up" data-aos-delay="200">

        <div class="col-xl-4">
          <div class="icon-box d-flex flex-column justify-content-center align-items-center">
            <i class="bi bi-book-half"></i> <!-- Icône de livre -->
            <h4>Large Catalogue</h4>
            <p>Des milliers de livres et mangas pour tous les âges, du classique au best-seller du moment.</p>
          </div>
        </div><!-- End Icon Box -->

        <div class="col-xl-4" data-aos="fade-up" data-aos-delay="300">
          <div class="icon-box d-flex flex-column justify-content-center align-items-center">
            <i class="bi bi-truck"></i> <!-- Icône de livraison -->
            <h4>Livraison Rapide</h4>
            <p>Commande expédiée rapidement et soigneusement emballée jusqu’à votre porte.</p>
          </div>
        </div><!-- End Icon Box -->

        <div class="col-xl-4" data-aos="fade-up" data-aos-delay="400">
          <div class="icon-box d-flex flex-column justify-content-center align-items-center">
            <i class="bi bi-stars"></i> <!-- Icône d’étoiles -->
            <h4>Satisfaction Garantie</h4>
            <p>Nos clients sont au cœur de nos priorités : qualité, service et confiance assurés.</p>
          </div>
        </div><!-- End Icon Box -->

      </div>
    </div>

  </div>

</div>

</section>
<!-- /Why Us Section -->


   <!-- Features Section -->
<section id="features" class="features section">

<div class="container">

  <div class="row gy-4">

    <div class="col-lg-3 col-md-4" data-aos="fade-up" data-aos-delay="100">
      <div class="features-item">
        <i class="bi bi-book-half" style="color: #ffbb2c;"></i> <!-- Book Icon -->
        <h3><a href="" class="stretched-link">Vaste Sélection</a></h3>
      </div>
    </div><!-- End Feature Item -->

    <div class="col-lg-3 col-md-4" data-aos="fade-up" data-aos-delay="200">
      <div class="features-item">
        <i class="bi bi-file-earmark-text" style="color: #5578ff;"></i> <!-- Text Icon -->
        <h3><a href="" class="stretched-link">Critiques & Résumés</a></h3>
      </div>
    </div><!-- End Feature Item -->

    <div class="col-lg-3 col-md-4" data-aos="fade-up" data-aos-delay="300">
      <div class="features-item">
        <i class="bi bi-gift" style="color: #e80368;"></i> <!-- Gift Icon -->
        <h3><a href="" class="stretched-link">Offres Exclusives</a></h3>
      </div>
    </div><!-- End Feature Item -->

    <div class="col-lg-3 col-md-4" data-aos="fade-up" data-aos-delay="400">
      <div class="features-item">
        <i class="bi bi-credit-card" style="color: #e361ff;"></i> <!-- Credit Card Icon -->
        <h3><a href="" class="stretched-link">Paiement Sécurisé</a></h3>
      </div>
    </div><!-- End Feature Item -->

    <div class="col-lg-3 col-md-4" data-aos="fade-up" data-aos-delay="500">
      <div class="features-item">
        <i class="bi bi-truck" style="color: #47aeff;"></i> <!-- Truck Icon -->
        <h3><a href="" class="stretched-link">Livraison Rapide</a></h3>
      </div>
    </div><!-- End Feature Item -->

    <div class="col-lg-3 col-md-4" data-aos="fade-up" data-aos-delay="600">
      <div class="features-item">
        <i class="bi bi-star" style="color: #ffa76e;"></i> <!-- Star Icon -->
        <h3><a href="" class="stretched-link">Évaluations des Clients</a></h3>
      </div>
    </div><!-- End Feature Item -->

    <div class="col-lg-3 col-md-4" data-aos="fade-up" data-aos-delay="700">
      <div class="features-item">
        <i class="bi bi-calendar-check" style="color: #11dbcf;"></i> <!-- Calendar Check Icon -->
        <h3><a href="" class="stretched-link">Nouveautés Hebdomadaires</a></h3>
      </div>
    </div><!-- End Feature Item -->

    <div class="col-lg-3 col-md-4" data-aos="fade-up" data-aos-delay="800">
      <div class="features-item">
        <i class="bi bi-eye" style="color: #4233ff;"></i> <!-- Eye Icon -->
        <h3><a href="" class="stretched-link">Suggestions Personnalisées</a></h3>
      </div>
    </div><!-- End Feature Item -->

    <div class="col-lg-3 col-md-4" data-aos="fade-up" data-aos-delay="900">
      <div class="features-item">
        <i class="bi bi-pencil" style="color: #b2904f;"></i> <!-- Pencil Icon -->
        <h3><a href="" class="stretched-link">Écrire une Critique</a></h3>
      </div>
    </div><!-- End Feature Item -->

    <div class="col-lg-3 col-md-4" data-aos="fade-up" data-aos-delay="1000">
      <div class="features-item">
        <i class="bi bi-person-lines-fill" style="color: #b20969;"></i> <!-- User Lines Icon -->
        <h3><a href="" class="stretched-link">Suivi de Commande</a></h3>
      </div>
    </div><!-- End Feature Item -->

    <div class="col-lg-3 col-md-4" data-aos="fade-up" data-aos-delay="1100">
      <div class="features-item">
        <i class="bi bi-chat-left-text" style="color: #ff5828;"></i> <!-- Chat Icon -->
        <h3><a href="" class="stretched-link">Support Client</a></h3>
      </div>
    </div><!-- End Feature Item -->

    <div class="col-lg-3 col-md-4" data-aos="fade-up" data-aos-delay="1200">
      <div class="features-item">
        <i class="bi bi-heart" style="color: #29cc61;"></i> <!-- Heart Icon -->
        <h3><a href="" class="stretched-link">Favoris Personnels</a></h3>
      </div>
    </div><!-- End Feature Item -->

  </div>

</div>

</section><!-- /Features Section -->


   <!-- Courses Section -->
<section id="courses" class="courses section">

<!-- Section Title -->
<div class="container section-title" data-aos="fade-up">
  <h2>Livres</h2>
  <p>Livres populaires</p>
</div><!-- End Section Title -->

<div class="container">
  <div class="row">

   <!-- Book 1: A Study in Scarlet -->
<div class="col-lg-4 col-md-6 d-flex align-items-stretch" data-aos="zoom-in" data-aos-delay="100">
  <div class="course-item">
    <img src="assets/img/books/cover_study_in_scarlet.jpg" class="img-fluid" alt="A Study in Scarlet">
    <div class="course-content">
      <h3><a href="#">A Study in Scarlet</a></h3>
      <p class="description">Le tout premier mystère mettant en vedette Sherlock Holmes, où l'art de la déduction prend vie.</p>
      <div class="trainer d-flex justify-content-between align-items-center">
        <div class="trainer-profile d-flex align-items-center">
          <img src="assets/img/authors/arthur_conan_doyle.jpg" class="img-fluid" alt="Arthur Conan Doyle">
          <a href="#" class="trainer-link">Arthur Conan Doyle</a>
        </div>
        <div class="trainer-rank d-flex align-items-center">
          <i class="bi bi-person user-icon"></i>&nbsp;120
          &nbsp;&nbsp;
          <i class="bi bi-heart heart-icon"></i>&nbsp;98
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Book 2: The Sign of the Four -->
<div class="col-lg-4 col-md-6 d-flex align-items-stretch mt-4 mt-md-0" data-aos="zoom-in" data-aos-delay="200">
  <div class="course-item">
    <img src="assets/img/books/cover_sign_of_four.jpg" class="img-fluid" alt="The Sign of the Four">
    <div class="course-content">
      <h3><a href="#">The Sign of the Four</a></h3>
      <p class="description">Un mystère complexe rempli de trahisons, de trésors cachés et de révélations étonnantes.</p>
      <div class="trainer d-flex justify-content-between align-items-center">
        <div class="trainer-profile d-flex align-items-center">
          <img src="assets/img/authors/arthur_conan_doyle.jpg" class="img-fluid" alt="Arthur Conan Doyle">
          <a href="#" class="trainer-link">Arthur Conan Doyle</a>
        </div>
        <div class="trainer-rank d-flex align-items-center">
          <i class="bi bi-person user-icon"></i>&nbsp;110
          &nbsp;&nbsp;
          <i class="bi bi-heart heart-icon"></i>&nbsp;87
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Book 3: The Hound of the Baskervilles -->
<div class="col-lg-4 col-md-6 d-flex align-items-stretch mt-4 mt-lg-0" data-aos="zoom-in" data-aos-delay="300">
  <div class="course-item">
    <img src="assets/img/books/cover_hound_of_baskervilles.jpg" class="img-fluid" alt="The Hound of the Baskervilles">
    <div class="course-content">
      <h3><a href="#">The Hound of the Baskervilles</a></h3>
      <p class="description">Sherlock enquête sur une bête légendaire dans la lande anglaise. L’un des plus célèbres cas de Holmes.</p>
      <div class="trainer d-flex justify-content-between align-items-center">
        <div class="trainer-profile d-flex align-items-center">
          <img src="assets/img/authors/arthur_conan_doyle.jpg" class="img-fluid" alt="Arthur Conan Doyle">
          <a href="#" class="trainer-link">Arthur Conan Doyle</a>
        </div>
        <div class="trainer-rank d-flex align-items-center">
          <i class="bi bi-person user-icon"></i>&nbsp;150
          &nbsp;&nbsp;
          <i class="bi bi-heart heart-icon"></i>&nbsp;104
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Book 4: The Valley of Fear -->
<div class="col-lg-4 col-md-6 d-flex align-items-stretch mt-4" data-aos="zoom-in" data-aos-delay="400">
  <div class="course-item">
    <img src="assets/img/books/cover_valley_of_fear.jpg" class="img-fluid" alt="The Valley of Fear">
    <div class="course-content">
      <h3><a href="#">The Valley of Fear</a></h3>
      <p class="description">Un complot ténébreux et des sociétés secrètes : un thriller palpitant signé Conan Doyle.</p>
      <div class="trainer d-flex justify-content-between align-items-center">
        <div class="trainer-profile d-flex align-items-center">
          <img src="assets/img/authors/arthur_conan_doyle.jpg" class="img-fluid" alt="Arthur Conan Doyle">
          <a href="#" class="trainer-link">Arthur Conan Doyle</a>
        </div>
        <div class="trainer-rank d-flex align-items-center">
          <i class="bi bi-person user-icon"></i>&nbsp;95
          &nbsp;&nbsp;
          <i class="bi bi-heart heart-icon"></i>&nbsp;82
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Book 5: Murder on the Orient Express -->
<div class="col-lg-4 col-md-6 d-flex align-items-stretch mt-4" data-aos="zoom-in" data-aos-delay="500">
  <div class="course-item">
    <img src="assets/img/books/Murder.jpg" class="img-fluid" alt="Murder on the Orient Express">
    <div class="course-content">
      <h3><a href="#">Murder on the Orient Express</a></h3>
      <p class="description">Hercule Poirot résout un crime à bord d’un train de luxe où chaque passager cache un secret.</p>
      <div class="trainer d-flex justify-content-between align-items-center">
        <div class="trainer-profile d-flex align-items-center">
          <img src="assets/img/authors/agatha_christie.jpg" class="img-fluid" alt="Agatha Christie">
          <a href="#" class="trainer-link">Agatha Christie</a>
        </div>
        <div class="trainer-rank d-flex align-items-center">
          <i class="bi bi-person user-icon"></i>&nbsp;180
          &nbsp;&nbsp;
          <i class="bi bi-heart heart-icon"></i>&nbsp;130
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Book 6: And Then There Were None -->
<div class="col-lg-4 col-md-6 d-flex align-items-stretch mt-4" data-aos="zoom-in" data-aos-delay="600">
  <div class="course-item">
    <img src="assets/img/books/None.jpg" class="img-fluid" alt="And Then There Were None">
    <div class="course-content">
      <h3><a href="#">And Then There Were None</a></h3>
      <p class="description">Dix inconnus réunis sur une île isolée... chacun portant un lourd passé à cacher.</p>
      <div class="trainer d-flex justify-content-between align-items-center">
        <div class="trainer-profile d-flex align-items-center">
          <img src="assets/img/authors/agatha_christie.jpg" class="img-fluid" alt="Agatha Christie">
          <a href="#" class="trainer-link">Agatha Christie</a>
        </div>
        <div class="trainer-rank d-flex align-items-center">
          <i class="bi bi-person user-icon"></i>&nbsp;200
          &nbsp;&nbsp;
          <i class="bi bi-heart heart-icon"></i>&nbsp;170
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Book 7: Murder in the Blue Train -->
<div class="col-lg-4 col-md-6 d-flex align-items-stretch mt-4" data-aos="zoom-in" data-aos-delay="700">
  <div class="course-item">
    <img src="assets/img/books/train.jpg" class="img-fluid" alt="Murder in the Blue Train">
    <div class="course-content">
      <h3><a href="#">Murder in the Blue Train</a></h3>
      <p class="description">Un meurtre à bord du légendaire Train Bleu. Hercule Poirot démêle les fils de l’intrigue.</p>
      <div class="trainer d-flex justify-content-between align-items-center">
        <div class="trainer-profile d-flex align-items-center">
          <img src="assets/img/authors/agatha_christie.jpg" class="img-fluid" alt="Agatha Christie">
          <a href="authors/authors.php" class="trainer-link">Agatha Christie</a>
        </div>
        <div class="trainer-rank d-flex align-items-center">
          <i class="bi bi-person user-icon"></i>&nbsp;160
          &nbsp;&nbsp;
          <i class="bi bi-heart heart-icon"></i>&nbsp;140
        </div>
      </div>
    </div>
  </div>
</div>



  </div>
</div>


   

  </main>
<?php 
require_once("fragments/footer.php");
  