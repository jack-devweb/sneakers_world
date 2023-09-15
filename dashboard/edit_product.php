<?php
include 'includes/db.php';
// Validation de l'ID du produit
$product_id = isset($_GET['id']) ? intval($_GET['id']) : null;
if ($product_id === null || $product_id <= 0) {
    header("HTTP/1.0 404 Not Found");
    include("error_404.php"); // Assurez-vous de créer cette page avec un message d'erreur 404

    // Ou afficher un message d'erreur directement dans cette page
    echo "ID de produit invalide. Veuillez fournir un ID de produit valide.";
    exit;
}

$product = $conn->prepare("SELECT * FROM products WHERE id = :product_id");
$product->bindParam(':product_id', $product_id, PDO::PARAM_INT);
$product->execute();
$product = $product->fetch(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $description = $_POST["description"];
    $price = $_POST["price"];
    $category_id = !empty($_POST["category_id"]) ? intval($_POST["category_id"]) : null;
    $quantity = isset($_POST["quantity"]) ? $_POST["quantity"] : 0;

    $imagePath = $product['image']; // Utiliser l'image existante comme valeur par défaut
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $tmp_name = $_FILES['image']['tmp_name'];
        $imageName = uniqid() . '-' . $_FILES['image']['name'];
        move_uploaded_file($tmp_name, "uploads/$imageName");
        $imagePath = "uploads/$imageName";
    }

    $sql = "UPDATE products SET name = :name, description = :description, price = :price, image = :image, category_id = :category_id, quantity = :quantity WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':price', $price);
    $stmt->bindParam(':image', $imagePath);
    $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
    $stmt->bindParam(':quantity', $quantity);
    $stmt->bindParam(':id', $product_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        // Rediriger l'utilisateur vers une page de confirmation de la mise à jour du produit
        header("Location: confirmation_modification_produit.php?msg=Produit mis à jour avec succès!");
        exit;
    } else {
        echo "Erreur lors de la mise à jour du produit.";
    }
}

// Récupérer toutes les catégories
$categories = $conn->query('SELECT id, name FROM categories')->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="container mt-5">
    <h2>Modifier le produit</h2>
    <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="name" class="form-label">Nom du produit</label>
            <input type="text" class="form-control" name="name" value="<?php echo $product['name']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" name="description" rows="3"><?php echo $product['description']; ?></textarea>
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Prix</label>
            <input type        ="number" class="form-control" name="price" step="0.01" value="<?php echo $product['price']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Image du produit (laissez vide pour conserver l'image actuelle)</label>
            <input type="file" class="form-control" name="image">
            <?php if ($product['image']): ?>
                <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" width="100">
            <?php endif; ?>
        </div>
        <div class="mb-3">
            <label for="category_id" class="form-label">Catégorie</label>
            <select class="form-control" name="category_id">
                <option value="">Sélectionnez une catégorie</option>
                <?php foreach ($categories as $category) : ?>
                    <option value="<?php echo $category['id']; ?>" <?php if ($product['category_id'] == $category['id']) echo 'selected'; ?>><?php echo $category['name']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="quantity" class="form-label">Quantité</label>
            <input type="number" class="form-control" name="quantity" value="<?php echo $product['quantity']; ?>">
        </div>
        <button type="submit" class="btn btn-primary">Mettre à jour</button>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
</body>
</html>
