<?php
include ('../includes/db.php');
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <!-- <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css"> -->
    <link rel="stylesheet" type="text/css" href="css/styles.css" />
    <link rel="https://use.fontawesome.com/releases/v5.7.2/css/all.css">
  <title>À propos | Boutique en ligne</title>
</head>
<body>
  <header>
    <h1><?php echo "a-propos"; ?></h1>
    <div class="p-3 bg-dark text-white">
        <div class="flexMain">
            <div class="flex1"></div>
            <div class="flex2 text-center">
                <div><strong></strong></div>
            </div>
            <div class="flex1"></div>
        </div>
    </div>
    <div id="menuHolder">
        <div role="navigation" class="sticky-top border-bottom border-top" id="mainNavigation">
            <div class="flexMain">
                <div class="flex2">
                    <button class="whiteLink siteLink" style="border-right:1px solid #eaeaea" onclick="menuToggle()"><i
                            class="fas fa-bars me-2"></i> MENU</button>
                </div>
                <div class="flex3 text-center" id="siteBrand">
                    MY AWESOME SITE
                </div>
                <div class="flex2 text-end d-block d-md-none">
                    <button class="whiteLink siteLink"><i class="fas fa-search"></i></button>
                </div>
                <div class="flex2 text-end d-none d-md-block">
                    <button class="whiteLink siteLink"><a href="inscription.php">REGISTER</a></button>
                    <button class="blackLink siteLink"><a href="login.php">Login</a></button>
                </div>
            </div>
        </div>
        <div id="menuDrawer">
            <div class="p-4 border-bottom">
                <div class='row'>
                    <div class="col">
                        <!-- Le sélecteur de langue a été supprimé -->
                    </div>
                    <div class="col text-end ">
                        <i class="fas fa-times" role="btn" onclick="menuToggle()"></i>
                    </div>
                </div>
            </div>
            <div>
                <a href="index.php" class="nav-menu-item"><i class="fas fa-home me-3"></i> Accueil</a>
                <a href="#" class="nav-menu-item"><i class="fab fa-product-hunt me-3"></i> Produits</a>
                <a href="boutique.php" class="nav-menu-item"><i class="fas fa-search me-3"></i> Explorer</a>
                <a href="#" class="nav-menu-item"><i class="fas fa-wrench me-3"></i> Services</a>
                <a href="#" class="nav-menu-item"><i class="fas fa-dollar-sign me-3"></i> Tarification</a>
                <a href="#" class="nav-menu-item"><i class="fas fa-file-alt me-3"></i> Blog</a>
                <a href="a_propos.php" class="nav-menu-item"><i class="fas fa-building me-3"></i> À propos de nous</a>
                <a href="panier.php" class="nav-menu-item"><i class="fas fa-shopping-cart me-3"></i> Panier</a>
            </div>
        </div>
    </div>
    </div>

    </div>
  </header>

  <main>
  <section class="about py-5">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-lg-6 text-center mb-4 mb-lg-0">
        <img src="../uploads/groupe.jpg" alt="Image à gauche" class="img-fluid">
      </div>
      <div class="col-lg-6 text-center">
        <h2 class="display-4">À propos de notre entreprise</h2>
        <p class="lead">
              Notre boutique en ligne a été créée pour offrir une sélection de sneakers de qualité à des prix compétitifs. Nous nous engageons à fournir un service client exceptionnel et une expérience d'achat en ligne agréable.
            </p>
            <p>
              Nous élargissons constamment notre gamme de produits en travaillant avec des fournisseurs fiables et responsables. Notre objectif est de devenir la destination préférée des amateurs de sneakers.
            </p>
      </div>
    </div>
  </div>
</section>

<section class="mission bg-light py-5">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-lg-6 text-center">
        <h2 class="display-4">Notre mission</h2>
        <p class="lead">
              Notre mission est de fournir une plateforme sécurisée et conviviale pour acheter des sneakers de qualité à des prix abordables. Nous visons l'excellence du service client et des relations durables avec nos clients.
            </p>
      </div>
      <div class="col-lg-6 text-center mb-4 mb-lg-0">
        <img src="../uploads/image_apropos.jpg" alt="Image à droite" class="img-fluid">
      </div>
    </div>
  </div>
</section>


<section class="values py-5">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-lg-4 text-center mb-4 mb-lg-0">
        <img src="../uploads/affiche.jpg" alt="Image à gauche" class="img-fluid">
      </div>
      <div class="col-lg-4 text-center">
        <h2 class="display-4">Nos valeurs</h2>
        <ul class="list-unstyled">
              <li>Intégrité et transparence</li>
              <li>Engagement envers la satisfaction du client</li>
              <li>Responsabilité environnementale et sociale</li>
              <li>Innovation et amélioration continue</li>
              <li>Création d'un environnement de travail positif et inclusif</li>
            </ul>
      </div>
      <div class="col-lg-4 text-center">
        <img src="../uploads/bouti.jpg" alt="Image à droite" class="img-fluid">
      </div>
    </div>
  </div>
</section>
  </main>
 <!-- Footer -->
<footer class="bg-dark text-white py-4">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <h5>Informations</h5>
                <ul class="list-unstyled">
                    <li><a href="#">À propos de nous</a></li>
                    <li><a href="#">Contactez-nous</a></li>
                    <li><a href="#">Politique de confidentialité</a></li>
                </ul>
            </div>
            <div class="col-md-4">
                <h5>Catégories</h5>
                <ul class="list-unstyled">
                    <li><a href="#">Chaussures</a></li>
                    <li><a href="#">Vêtements</a></li>
                    <li><a href="#">Accessoires</a></li>
                </ul>
            </div>
            <div class="col-md-4">
                <h5>Suivez-nous</h5>
                <ul class="list-unstyled">
                    <li><a href="#"><i class="fab fa-facebook"></i> Facebook</a></li>
                    <li><a href="#"><i class="fab fa-twitter"></i> Twitter</a></li>
                    <li><a href="#"><i class="fab fa-instagram"></i> Instagram</a></li>
                </ul>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 text-center">
                <p>&copy; 2023 Super Commerce. Tous droits réservés.</p>
            </div>
        </div>
    </div>
    <p>&copy; <?php echo date("Y"); ?> - Boutique en ligne. Tous droits réservés.</p>
  </footer>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <!-- <script src="node_modules/bootstrap/dist/js/bootstrap.min.js"></script> -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="script/script.js"></script>
</body>
</html>

    