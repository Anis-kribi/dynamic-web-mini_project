<?php
// Define the current page
$page_courante = basename($_SERVER['PHP_SELF']);
?>
<body class="index-page">

<header id="header" class="header d-flex align-items-center sticky-top">
  <div class="container-fluid container-xl position-relative d-flex align-items-center">

    <!-- Logo + lien vers l'accueil -->
    <a href="../index.php" class="logo d-flex align-items-center me-auto">
      <h1 class="sitename <?php echo ($page_courante == 'index.php') ? 'active' : ''; ?>">Bookory</h1>
    </a>

    <nav id="navmenu" class="navmenu">
      <ul>
        <!-- Accueil -->
        <li>
          <a href="../index.php" class="<?= $page_courante == 'index.php' ? 'active' : '' ?>">
            <i class="bi bi-house-door"></i> Accueil
          </a>
        </li>

        <!-- Livres -->
        <li>
          <a href="../books/books.php" class="<?= $page_courante == 'books.php' ? 'active' : '' ?>">
            <i class="bi bi-book"></i> Livres
          </a>
        </li>

        <!-- Écrivains -->
        <li>
          <a href="../authors/authors.php" class="<?= $page_courante == 'authors.php' ? 'active' : '' ?>">
            <i class="bi bi-pen"></i> Écrivains
          </a>
        </li>

        <!-- Contact -->
        <li>
          <a href="../contact/contact.php" class="<?= $page_courante == 'contact.php' ? 'active' : '' ?>">
            <i class="bi bi-envelope"></i> Contact
          </a>
        </li>

        <!-- À propos -->
        <li>
          <a href="../propos/propos.php" class="<?= $page_courante == 'propos.php' ? 'active' : '' ?>">
            <i class="bi bi-info-circle"></i> À propos
          </a>
        </li>
  <!-- Dashboard for Admin -->
<?php if (isset($_SESSION['user_role']) && strtolower($_SESSION['user_role']) === 'admin'): ?>
  <li>
    <a href="../admin/dashboard.php" class="<?= ($page_courante === 'dashboard.php') ? 'active' : '' ?>">
      <i class="bi bi-speedometer2"></i> Dashboard 
    </a>
  </li>
<?php endif; ?>

<!-- Dashboard for User -->
<?php if (isset($_SESSION['user_role']) && strtolower($_SESSION['user_role']) === 'user'): ?>
  <li>
    <a href="../admin/userdashboard.php" class="<?= ($page_courante === 'userdashboard.php') ? 'active' : '' ?>">
      <i class="bi bi-speedometer2"></i> Dashboard
    </a>
  </li>
<?php endif; ?>


        <!-- Barre de recherche -->
        <li class="search-bar">
          <form action="../search/search.php" method="GET">
            <input type="text" name="query" placeholder="Recherche..." required>
            <button type="submit"><i class="bi bi-search"></i></button>
          </form>
        </li>

        <!-- Connexion / Déconnexion -->
        <?php if (isset($_SESSION['user_role'])): ?>
    <li>
        <a href="../connexion/logout.php" class="btn btn-danger">
            <i class="bi bi-box-arrow-right"></i> Déconnexion
            <?php if ($_SESSION['user_role'] === 'admin'); ?>
        </a>
    </li>
<?php else: ?>
    <li class="profile-connexion">
        <a href="../connexion/connexion.php" class="btn btn-primary">
            <i class="bi bi-person-circle"></i> Connexion
        </a>
    </li>
<?php endif; ?>



      </ul>

      <!-- Mobile menu toggle -->
      <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
    </nav>

  </div>
</header>
