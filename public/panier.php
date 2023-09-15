<?php
include ('../includes/db.php');
session_start();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panier</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
</head>

<body>
    <div class="container mt-5">
        <h2>Panier</h2>
        <?php
        // Vérifier si la clé 'cart' existe dans la session
        if (isset($_SESSION['cart'])) {
            $cartProducts = $_SESSION['cart'];

            $totalPrice = 0;
            ?>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nom du produit</th>
                            <th>Quantité</th>
                            <th>Prix unitaire</th>
                            <th>Total</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cartProducts as $productID => $productData) {
                            // Récupérer les détails du produit depuis la base de données
                            $stmt = $conn->prepare('SELECT name, price FROM products WHERE id = :product_id');
                            $stmt->bindParam(':product_id', $productID);
                            $stmt->execute();
                            $product = $stmt->fetch();

                            $productName = $product['name'];
                            $productPrice = $product['price'];

                            $productTotal = $productPrice * $productData['quantity'];
                            $totalPrice += $productTotal;
                            ?>
                            <tr>
                                <td>
                                    <?php echo $productName; ?>
                                </td>
                                <td>
                                    <?php echo $productData['quantity']; ?>
                                </td>
                                <td>
                                    <?php echo $productPrice; ?> €
                                </td>
                                <td>
                                    <?php echo $productTotal; ?> €
                                </td>
                                <td>
                                    <form method="post" action="update_cart.php">
                                        <input type="hidden" name="product_id" value="<?php echo $productID; ?>">
                                        <input type="number" name="quantity" value="<?php echo $productData['quantity']; ?>"
                                            min="1">
                                        <button type="submit" class="btn btn-primary btn-sm">Mettre à jour</button>
                                    </form>
                                    <form method="post" action="remove_from_cart.php">
                                        <input type="hidden" name="product_id" value="<?php echo $productID; ?>">
                                        <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        <?php } ?>
                        <tr>
                            <td colspan="3"><strong>Total</strong></td>
                            <td><strong>
                                    <?php echo $totalPrice; ?> €
                                </strong></td>
                            <td>
                                <form method="post" action="valider_commande.php">
                                    <button type="submit" class="btn btn-success">Valider la commande</button>
                                </form>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <?php
        } else {
            echo "Votre panier est vide.";
        }
        ?>
    </div>

    <div class="container mt-3">
        <a href="index.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Retour à la page d'accueil</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm"
        crossorigin="anonymous"></script>
</body>

</html>