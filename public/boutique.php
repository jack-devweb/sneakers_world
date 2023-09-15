<?php
include('../includes/db.php');

session_start();

// Récupérer la liste des catégories principales
$mainCategories = $conn->query('SELECT id, name FROM categories WHERE parent_id IS NULL')->fetchAll(PDO::FETCH_ASSOC);

// Récupérer la catégorie sélectionnée depuis la requête GET
$selectedCategory = isset($_GET['category']) ? $_GET['category'] : '';

// Construire une requête SQL pour obtenir les produits en fonction de la catégorie sélectionnée et de ses sous-catégories
if ($selectedCategory) {
    $stmt = $conn->prepare('SELECT * FROM products WHERE category_id IN (
        SELECT id FROM categories WHERE id = :category_id OR parent_id = :category_id
    )');
    $stmt->bindParam(':category_id', $selectedCategory, PDO::PARAM_INT);
} else {
    $stmt = $conn->prepare('SELECT * FROM products');
}
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boutique</title>
    <!-- <link href="node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet"> -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
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
                <a href="#" class="nav-menu-item"><i class="fas fa-search me-3"></i> Explorer</a>
                <a href="#" class="nav-menu-item"><i class="fas fa-wrench me-3"></i> Services</a>
                <a href="#" class="nav-menu-item"><i class="fas fa-dollar-sign me-3"></i> Tarification</a>
                <a href="#" class="nav-menu-item"><i class="fas fa-file-alt me-3"></i> Blog</a>
                <a href="a_propos.php" class="nav-menu-item"><i class="fas fa-building me-3"></i> À propos de nous</a>
                <a href="panier.php" class="nav-menu-item"><i class="fas fa-shopping-cart me-3"></i> Panier</a>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <label for="category" class="form-label">Filtrer par catégorie :</label>
        <select class="form-select" name="category" id="category">
            <option value="">Toutes les catégories</option>
            <?php foreach ($mainCategories as $category): ?>
                <option value="<?php echo $category['id']; ?>" <?php if ($selectedCategory == $category['id'])
                       echo 'selected'; ?>>
                    <?php echo $category['name']; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <script>
        document.getElementById('category').addEventListener('change', function () {
            var selectedCategory = this.value;
            if (selectedCategory !== '') {
                window.location.href = 'boutique.php?category=' + selectedCategory;
            } else {
                window.location.href = 'boutique.php';
            }
        });
    </script>


    <!-- Affichage des produits -->
    <div class="container mt-5">
        <h2>Produits</h2>
        <div class="row">
            <?php foreach ($products as $product): ?>
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
                                    data-product-id="<?php echo $product['id']; ?>">Ajouter au panier</button>
                                <button class="btn btn-secondary btn-sm"><i class="fas fa-eye"></i> Voir les
                                    détails</button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <!-- <script src="node_modules/bootstrap/dist/js/bootstrap.min.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="script/script.js"></script>
    <script>
    // Sélectionnez tous les boutons "Ajouter au panier"
    var addToCartButtons = document.querySelectorAll('.btn-add-to-cart');

    // Attachez un gestionnaire d'événements à chaque bouton
    addToCartButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            // Récupérez le nom du produit à partir de l'attribut data-product-name
            var productName = this.getAttribute('data-product-name');

            // Affichez une fenêtre pop-up d'alerte avec le message de confirmation
            alert(productName + ' ajouté avec succès au panier !');
        });
    });
</script>

</body>

</html>