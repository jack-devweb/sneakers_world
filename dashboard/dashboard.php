<?php
include('../includes/db.php');


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if ($_SESSION['role'] != 'admin') { // Redirection vers la page de connexion si l'utilisateur n'est pas administrateur.
    header('Location: <public>login.php');
    exit;
}
//récupérer les informations de l'utilisateur
$userInfo = null;
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    $sql = "SELECT username, address, phone_number FROM users WHERE id = :user_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $stmt->execute();
    $userInfo = $stmt->fetch(PDO::FETCH_ASSOC);
    $carouselImages = $conn->query('SELECT * FROM carousel_images')->fetchAll(PDO::FETCH_ASSOC);
}
// Traitement des formulaires POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add_product'])) {
        $name = $_POST["product_name"];
        $description = $_POST["product_description"];
        $price = $_POST["product_price"];
        $category_id = $_POST["product_category_id"] ?? null;
        $quantity = $_POST["product_quantity"] ?? 0;
        $is_promo = isset($_POST['product_is_promo']) ? 1 : 0;

        $imagePath = null;
        if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] == 0) {
            $tmp_name = $_FILES['product_image']['tmp_name'];
            $name_image = uniqid() . '-' . $_FILES['product_image']['name'];
            move_uploaded_file($tmp_name, "uploads/$name_image");
            $imagePath = "uploads/$name_image";
        }

        $sql = "INSERT INTO products (name, description, price, image, category_id, quantity, is_promo) VALUES (:name, :description, :price, :image, :category_id, :quantity, :is_promo)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':image', $imagePath);
        $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':is_promo', $is_promo);

        if ($stmt->execute()) {
            echo "<div class='alert alert-success'>Produit ajouté avec succès!</div>";
        } else {
            echo "<div class='alert alert-danger'>Erreur lors de l'ajout du produit.</div>";
        }
    }

    if (isset($_POST['add_content'])) {
                // Récupération des données du formulaire pour ajouter du contenu au site.
        $contentType = $_POST["content_type"];
        $contentValue = $_POST["content_value"];
        $contentName = $_POST["content_name"];

        $imagePath = null;
        if ($contentType === 'image' && isset($_FILES['content_image']) && $_FILES['content_image']['error'] == 0) {
            $tmp_name = $_FILES['content_image']['tmp_name'];
            $name_image = uniqid() . '-' . $_FILES['content_image']['name'];
            move_uploaded_file($tmp_name, "uploads/$name_image");
            $imagePath = "uploads/$name_image";
        }

        $sql = "INSERT INTO site_content (content_type, content_value, content_name) VALUES (:content_type, :content_value, :content_name)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':content_type', $contentType);
        $stmt->bindValue(':content_value', $contentType === 'image' ? $imagePath : $contentValue);
        $stmt->bindParam(':content_name', $contentName);

        // Insertion des données de contenu dans la base de données avec gestion des erreurs.
        try {
            if ($stmt->execute()) {
                echo "<div class='alert alert-success'>Contenu ajouté avec succès!</div>";
            } else {
                echo "<div class='alert alert-danger'>Erreur lors de l'ajout du contenu.</div>";
            }
        } catch (PDOException $e) {
            if ($e->errorInfo[1] == 1062) {
                echo "<div class='alert alert-danger'>Le nom du contenu existe déjà. Veuillez en choisir un autre.</div>";
            } else {
                echo "<div class='alert alert-danger'>Erreur de base de données : " . $e->getMessage() . "</div>";
            }
        }
    }
}
// Récupération des catégories principales et des sous-catégories.
$mainCategories = $conn->query('SELECT id, name FROM categories WHERE parent_id IS NULL')->fetchAll(PDO::FETCH_ASSOC);
$subCategories = $conn->query('SELECT id, name, parent_id FROM categories WHERE parent_id IS NOT NULL')->fetchAll(PDO::FETCH_ASSOC);

// Récupération des produits à partir de la base de données.
$products = $conn->query('SELECT * FROM products')->fetchAll(PDO::FETCH_ASSOC);

// Récupération des contenus du site.
$siteContents = $conn->query('SELECT * FROM site_content ORDER BY id DESC LIMIT 10')->fetchAll(PDO::FETCH_ASSOC);

// Récupération des commandes depuis la base de données.
$orders = $conn->query('SELECT id, total_price, order_date, status FROM orders')->fetchAll(PDO::FETCH_ASSOC);
$ordersQuery = "SELECT o.id, o.total_price, o.order_date, o.status, u.username, u.address, u.phone_number
               FROM orders AS o
               INNER JOIN users AS u ON o.user_id = u.id";
$orders = $conn->query($ordersQuery)->fetchAll(PDO::FETCH_ASSOC);

if (isset($_GET['action']) && $_GET['action'] === 'terminer' && isset($_GET['id'])) {

 // Marquer une commande comme "Expédiée" dans la base de données.
    $orderId = $_GET['id'];

    // Mettez à jour le statut de la commande dans la base de données
    $stmt = $conn->prepare('UPDATE orders SET status = "EXPÉDIÉ" WHERE id = :order_id');
    $stmt->bindParam(':order_id', $orderId, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>La commande a été marquée comme 'Expédiée' avec succès.</div>";
    } else {
        echo "<div class='alert alert-danger'>Erreur lors de la mise à jour du statut de la commande : " . $stmt->errorInfo()[2] . "</div>";
    }
}

    // Traitement de l'ajout d'une image au carrousel.
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_carousel_image'])) {
    $imagePath = null;
    $imageTitle = $_POST["image_title"];
    $imageDescription = $_POST["image_description"];
    
    
    if (isset($_FILES['image_path']) && $_FILES['image_path']['error'] == 0) {
        $tmp_name = $_FILES['image_path']['tmp_name'];
        $name_image = uniqid() . '-' . $_FILES['image_path']['name'];
        move_uploaded_file($tmp_name, "uploads/$name_image");
        $imagePath = "uploads/$name_image";
    }
    
    if ($imagePath) {
        // Établissez votre connexion à la base de données ici
        
        $sql = "INSERT INTO carousel_images (image_path, title, description) VALUES (:image_path, :title, :description)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':image_path', $imagePath);
        $stmt->bindParam(':title', $imageTitle);
        $stmt->bindParam(':description', $imageDescription);
        
            // Insertion de l'image dans la base de données avec gestion des erreurs.
        if ($stmt->execute()) {
            echo "<div class='alert alert-success'>Image ajoutée avec succès au carrousel!</div>";
        } else {
            echo "<div class='alert alert-danger'>Erreur lors de l'ajout de l'image.</div>";
        }
    }
}

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord</title>
    <link href="node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="public\css\styles.css" />
</head>

<body>
<div class="container mt-5">
    <h2>Liste des produits par catégorie</h2>
    <div id="accordion">
        <?php
        // Récupérez la liste des catégories principales
        $mainCategories = $conn->query('SELECT id, name FROM categories WHERE parent_id IS NULL')->fetchAll(PDO::FETCH_ASSOC);

        // Compteur pour générer des identifiants uniques pour les boutons déroulants
        $accordionCounter = 1;

        // Parcourez les catégories principales
        foreach ($mainCategories as $mainCategory):
            $mainCategoryId = $mainCategory['id'];
            $mainCategoryName = $mainCategory['name'];

            // Récupérez les catégories enfants
            $subCategories = $conn->prepare('SELECT id, name FROM categories WHERE parent_id = :parent_id');
            $subCategories->bindParam(':parent_id', $mainCategoryId, PDO::PARAM_INT);
            $subCategories->execute();
            $subCategories = $subCategories->fetchAll(PDO::FETCH_ASSOC);
        ?>
        <div class="card">
            <div class="card-header" id="heading<?php echo $accordionCounter; ?>">
                <h5 class="mb-0">
                    <button class="btn btn-link" data-toggle="collapse" data-target="#collapse<?php echo $accordionCounter; ?>" aria-expanded="false" aria-controls="collapse<?php echo $accordionCounter; ?>">
                        <?php echo $mainCategoryName; ?>
                    </button>
                </h5>
            </div>

            <div id="collapse<?php echo $accordionCounter; ?>" class="collapse" aria-labelledby="heading<?php echo $accordionCounter; ?>" data-parent="#accordion">
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nom</th>
                                <th>Description</th>
                                <th>Prix</th>
                                <th>Quantité</th>
                                <th>Image</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Récupérez les produits pour la catégorie principale
                            $productsForMainCategory = $conn->prepare('SELECT * FROM products WHERE category_id = :category_id');
                            $productsForMainCategory->bindParam(':category_id', $mainCategoryId, PDO::PARAM_INT);
                            $productsForMainCategory->execute();
                            $products = $productsForMainCategory->fetchAll(PDO::FETCH_ASSOC);

                            // Affichez les produits pour la catégorie principale
                            foreach ($products as $product):
                            ?>
                            <tr>
                                <td><?php echo $product['id']; ?></td>
                                <td><?php echo $product['name']; ?></td>
                                <td><?php echo $product['description']; ?></td>
                                <td><?php echo $product['price']; ?> €</td>
                                <td><?php echo $product['quantity']; ?></td>
                                <td>
                                    <?php if (!empty($product['image'])): ?>
                                        <img src="<?php echo $product['image']; ?>" alt="Image du produit" width="100">
                                    <?php else: ?>
                                        Aucune image
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="dashboard/edit_product.php?id=<?php echo $product['id']; ?>"
                                        class="btn btn-warning">Modifier</a>
                                    <a href="dashboard/delete_product.php?id=<?php echo $product['id']; ?>"
                                        class="btn btn-danger"
                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce produit?');">Supprimer</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>

                            <?php
                            // Récupérez les produits pour les catégories enfants
                            foreach ($subCategories as $subCategory):
                                $subCategoryId = $subCategory['id'];

                                $productsForSubCategory = $conn->prepare('SELECT * FROM products WHERE category_id = :category_id');
                                $productsForSubCategory->bindParam(':category_id', $subCategoryId, PDO::PARAM_INT);
                                $productsForSubCategory->execute();
                                $products = $productsForSubCategory->fetchAll(PDO::FETCH_ASSOC);

                                // Affichez les produits pour les catégories enfants
                                foreach ($products as $product):
                            ?>
                            <tr>
                                <td><?php echo $product['id']; ?></td>
                                <td><?php echo $product['name']; ?></td>
                                <td><?php echo $product['description']; ?></td>
                                <td><?php echo $product['price']; ?> €</td>
                                <td><?php echo $product['quantity']; ?></td>
                                <td>
                                    <?php if (!empty($product['image'])): ?>
                                        <img src="<?php echo $product['image']; ?>" alt="Image du produit" width="100">
                                    <?php else: ?>
                                        Aucune image
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="edit_product.php?id=<?php echo $product['id']; ?>"
                                        class="btn btn-warning">Modifier</a>
                                    <a href="delete_product.php?id=<?php echo $product['id']; ?>"
                                        class="btn btn-danger"
                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce produit?');">Supprimer</a>
                                </td>
                            </tr>
                            <?php endforeach; endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php
            $accordionCounter++;
        endforeach;
        ?>
    </div>
</div>



    <div class="container mt-5">
        <h2>Ajouter un produit pour la boutique</h2>
        <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="add_product" value="1">
            <div class="mb-3">
                <label for="product_name" class="form-label">Nom du produit</label>
                <input type="text" class="form-control" id="product_name" name="product_name" required>
            </div>
            <div class="mb-3">
                <label for="product_description" class="form-label">Description</label>
                <textarea class="form-control" id="product_description" name="product_description" rows="3"></textarea>
            </div>
            <div class="mb-3">
                <label for="product_price" class="form-label">Prix</label>
                <input type="number" class="form-control" id="product_price" name="product_price" step="0.01" required>
            </div>
            <div class="mb-3">
                <label for="product_is_promo" class="form-label">En promotion :</label>
                <input type="checkbox" name="product_is_promo" id="product_is_promo">
            </div>
            <div class="mb-3">
                <label for="product_image" class="form-label">Image du produit</label>
                <input type="file" class="form-control" id="product_image" name="product_image">
            </div>
            <div class="mb-3">
                <label for="product_quantity" class="form-label">Quantité</label>
                <input type="number" class="form-control" id="product_quantity" name="product_quantity">
            </div>
            <div class="mb-3">
    <label for="product_category_id" class="form-label">Catégorie</label>
    <select class="form-select" id="product_category_id" name="product_category_id">
        <option value="" selected>Sélectionnez une catégorie</option>
        <?php foreach ($mainCategories as $category): ?>
            <option value="<?php echo $category['id']; ?>"><?php echo $category['name']; ?></option>
        <?php endforeach; ?>
    </select>
</div>
<div class="mb-3">
    <label for="product_category" class="form-label">Catégorie</label><br>
    <input type="checkbox" id="homme" name="categories[]" value="Hommes">
    <label for="homme">Hommes</label><br>
    <input type="checkbox" id="femme" name="categories[]" value="Femmes">
    <label for="femme">Femmes</label><br>
    <input type="checkbox" id="enfant" name="categories[]" value="Enfants">
    <label for="enfant">Enfants</label><br>
</div>

            <button type="submit" class="btn btn-primary">Ajouter</button>
        </form>
    </div>
    <div class="container mt-5">
    <h2>Ajouter une image au carrousel</h2>
    <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="add_carousel_image" value="1">
        
        <div class="mb-3">
            <label for="image_path" class="form-label">Image</label>
            <input type="file" class="form-control" id="image_path" name="image_path" required>
        </div>
        <div class="mb-3">
            <label for="image_title" class="form-label">Titre</label>
            <input type="text" class="form-control" id="image_title" name="image_title" required>
        </div>
        <div class="mb-3">
            <label for="image_description" class="form-label">Description</label>
            <textarea class="form-control" id="image_description" name="image_description" rows="3" required></textarea>
        </div>
        
        <button type="submit" class="btn btn-primary">Ajouter l'image</button>
    </form>
</div>
<?php if (!empty($carouselImages)): ?>
    <div class="container mt-5">
        <h2>Liste des éléments du carrousel</h2>
        <div class="row">
            <?php foreach ($carouselImages as $image): ?>
                <div class="col-md-4">
                    <div class="card mb-4">
                        <img src="<?php echo $image['image_path']; ?>" class="card-img-top" alt="<?php echo $image['title']; ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $image['title']; ?></h5>
                            <p class="card-text"><?php echo $image['description']; ?></p>
                        </div>
                        <div class="card-footer">
                            <a href="edit_carousel_image.php?id=<?php echo $image['id']; ?>" class="btn btn-warning">Modifier</a>
                            <a href="delete_carousel_image.php?id=<?php echo $image['id']; ?>" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette image du carrousel?');">Supprimer</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php else: ?>
    <p>Aucune image du carrousel disponible.</p>
<?php endif; ?>


    <div class="container mt-5">
        <h2>Ajouter du contenu au site</h2>
        <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="add_content" value="1">
            <div class="mb-3">
                <label for="content_type" class="form-label">Type de contenu</label>
                <select class="form-select" id="content_type" name="content_type" required>
                    <option value="text">Texte</option>
                    <option value="image">Image</option>
                </select>
            </div>
            
            <div class="mb-3">
                <label for="content_name" class="form-label">Nom du contenu</label>
                <input type="text" class="form-control" id="content_name" name="content_name" required>
            </div>
            <div class="mb-3">
                <label for="content_value" class="form-label">Valeur du contenu</label>
                <input type="text" class="form-control" id="content_value" name="content_value" required>
            </div>
            <div class="mb-3">
                <label for="content_image" class="form-label">Image du contenu (si le type est une image)</label>
                <input type="file" class="form-control" id="content_image" name="content_image">
            </div>
            <button type="submit" class="btn btn-primary">Ajouter</button>
        </form>
    </div>

    <div class="container mt-5">
        <h2>Contenu soumis</h2>
        <div class="row">
            <?php foreach ($siteContents as $content): ?>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card">
                        <?php if ($content['content_type'] === 'image') { ?>
                            <img src="<?php echo $content['content_value']; ?>" class="card-img-top" alt="Image">
                        <?php } ?>
                        <div class="card-body">
                            <h5 class="card-title">
                                <?php echo $content['content_name']; ?>
                            </h5>
                            <?php if ($content['content_type'] === 'text') { ?>
                                <p class="card-text">
                                    <?php echo $content['content_value']; ?>
                                </p>
                            <?php } ?>
                        </div>
                        <div class="card-footer">
                            <a href="edit_content.php?id=<?php echo $content['id']; ?>"
                                class="btn btn-warning">Modifier</a>
                            <a href="delete_content.php?id=<?php echo $content['id']; ?>" class="btn btn-danger"
                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce contenu?');">Supprimer</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="container mt-5">
    <h2>Nouvelles commandes</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID Commande</th>
                <th>Montant total</th>
                <th>Date</th>
                <th>Nom du client</th>
                <th>Adresse du client</th>
                <th>Numéro de téléphone du client</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?php echo $order['id']; ?></td>
                    <td><?php echo $order['total_price']; ?> €</td>
                    <td><?php echo $order['order_date']; ?></td>
                    <td><?php echo $order['username']; ?></td>
                    <td><?php echo $order['address']; ?></td>
                    <td><?php echo $order['phone_number']; ?></td>
                    <td><?php echo isset($order['status']) ? $order['status'] : 'Statut non défini'; ?></td>
                    <td>
                        <?php if ($order['status'] !== 'Expédié'): ?>
                            <a href="public/dashboard.php?action=terminer&id=<?php echo $order['id']; ?>" class="btn btn-primary">Terminer</a>
                        <?php endif; ?>
                        <a href="supprimer_commande.php?id=<?php echo $order['id']; ?>" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette commande?');">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>



<!-- Inclure jQuery en premier -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Ensuite, inclure Bootstrap JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- <script src="node_modules/bootstrap/dist/js/bootstrap.min.js"></script> -->
</body>

</html>
