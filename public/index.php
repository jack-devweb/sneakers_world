<?php
include('../includes/db.php');


session_start();

// Récupérer la liste des catégories principales
$mainCategories = $conn->query('SELECT id, name FROM categories WHERE parent_id IS NULL')->fetchAll(PDO::FETCH_ASSOC);

// Vérifier si l'utilisateur est connecté
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    $username = ""; // Initialisez une variable pour stocker le nom d'utilisateur
    // Récupérez le nom d'utilisateur à partir de la base de données
    $stmt = $conn->prepare('SELECT username FROM users WHERE id = :user_id');
    $stmt->bindParam(':user_id', $_SESSION['id']);
    $stmt->execute();

    // Récupérez le nom d'utilisateur depuis la requête
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($userData) {
        $username = $userData['username'];
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <!-- <link href="node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet"> -->
    <link rel="stylesheet" type="text/css" href="css/styles.css" />
    <link rel="https://use.fontawesome.com/releases/v5.7.2/css/all.css">
</head>

<body>
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
                    Sneakers_world
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
                    <div class="col text-end ">
                        <i class="fas fa-times" role="btn" onclick="menuToggle()"></i>
                    </div>
                </div>
            </div>
            <div>
                <a href="#" class="nav-menu-item"><i class="fas fa-home me-3"></i> Accueil</a>
                <a href="#" class="nav-menu-item"><i class="fab fa-product-hunt me-3"></i> Produits</a>
                <a href="boutique.php" class="nav-menu-item"><i class="fas fa-search me-3"></i> Boutique</a>
                <a href="#" class="nav-menu-item"><i class="fas fa-wrench me-3"></i> Services</a>
                <a href="#" class="nav-menu-item"><i class="fas fa-dollar-sign me-3"></i> Tarification</a>
                <a href="#" class="nav-menu-item"><i class="fas fa-file-alt me-3"></i> Blog</a>
                <a href="a_propos.php" class="nav-menu-item"><i class="fas fa-building me-3"></i> À propos de nous</a>
                <a href="panier.php" class="nav-menu-item"><i class="fas fa-shopping-cart me-3"></i> Panier</a>
            </div>
        </div>
    </div>

    <div class="carousel-inner custom-carousel">
        <div id="headerCarousel" class="carousel slide" data-bs-ride="carousel">
            <!-- Indicateurs du carrousel -->
            <ol class="carousel-indicators">
                <li data-bs-target="#headerCarousel" data-bs-slide-to="0" class="active"></li>
                <li data-bs-target="#headerCarousel" data-bs-slide-to="1"></li>
                <li data-bs-target="#headerCarousel" data-bs-slide-to="2"></li>
            </ol>

            <!-- Slides du carrousel -->
            <!-- Slides du carrousel -->
            <div class="carousel-inner">
                <?php
                // Récupérez les informations des images du carrousel depuis la base de données
                $carouselImages = $conn->query('SELECT * FROM carousel_images')->fetchAll(PDO::FETCH_ASSOC);

                foreach ($carouselImages as $index => $carouselImage) {
                    echo "<div class='carousel-item" . ($index === 0 ? " active" : "") . "'>";
                    echo "<img src='../" . $carouselImage['image_path'] . "' class='d-block w-100' alt='Image " . ($index + 1) . "'>";
                    echo "<div class='carousel-caption d-none d-md-block'>";
                    echo "<h3>" . $carouselImage['title'] . "</h3>";
                    echo "<p>" . $carouselImage['description'] . "</p>";
                    echo "</div>";
                    echo "</div>";
                }
                ?>
            </div>

            <!-- Contrôles du carrousel -->
            <a class="carousel-control-prev" href="#headerCarousel" role="button" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Précédent</span>
            </a>
            <a class="carousel-control-next" href="#headerCarousel" role="button" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Suivant</span>
            </a>
        </div>
    </div>
    </div>
    <div class="container mt-5">
        <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
            <p>Bienvenue,
                <?php echo $username; ?> !
            </p>
        <?php endif; ?>

        <div class="row">
            <?php
            $stmt = $conn->query('SELECT * FROM site_content ORDER BY id DESC LIMIT 10');
            while ($row = $stmt->fetch()) {
                ?>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100">
                        <?php if ($row['content_type'] === 'image'): ?>
                            <img src="../<?php echo $row['content_value']; ?>" class="card-img-top" alt="Image">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title">
                                <?php echo $row['content_name']; ?>
                            </h5>
                            <?php if ($row['content_type'] === 'text'): ?>
                                <p class="card-text">
                                    <?php echo $row['content_value']; ?>
                                </p>
                            <?php endif; ?>
                        </div>
                        <!-- Supprimer les liens Modifier et Supprimer -->
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>

    <div class="container mt-5">
        <h2>Promotions</h2>
        <div class="row">
            <?php
            $stmt = $conn->query('SELECT id, name, description, price, image FROM products WHERE is_promo = 1');
            while ($product = $stmt->fetch()) {
                ?>
                <div class="col-lg-4 col-md-12 mb-4">
                    <div class="card h-100">
                        <div class="bg-image hover-zoom ripple ripple-surface ripple-surface-light"
                            data-mdb-ripple-color="light">
                            <img src="../<?php echo $product['image']; ?>" class="w-100 card-image img-fluid" />
                            <a href="#!">
                                <div class="mask">
                                    <div class="d-flex justify-content-start align-items-end h-100">
                                        <h5><span class="badge bg-primary ms-2">New</span></h5>
                                    </div>
                                </div>
                                <div class="hover-overlay">
                                    <div class="mask" style="background-color: rgba(251, 251, 251, 0.15);"></div>
                                </div>
                            </a>
                        </div>
                        <div class="card-body">
                            <a href="#" class="text-reset">
                                <h5 class="card-title mb-3">
                                    <?php echo $product['name']; ?>
                                </h5>
                            </a>
                            <a href="#" class="text-reset">
                                <p>
                                    <?php echo $product['description']; ?>
                                </p>
                            </a>
                            <h6 class="mb-3">
                                <?php echo $product['price']; ?> €
                            </h6>
                            <div class="d-flex justify-content-between">
                                <button class="btn btn-primary btn-sm btn-add-to-cart"
                                    data-product-id="<?php echo $product['id']; ?>"
                                    data-product-name="<?php echo $product['name']; ?>">Ajouter au panier</button>


                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
    <div id="popup-dialog" title="Produit ajouté au panier">
    </div>
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

    </footer>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- <script src="node_modules/bootstrap/dist/js/bootstrap.min.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
    <script src="script/script.js"></script>

    <script>

    </script>

</body>

</html>