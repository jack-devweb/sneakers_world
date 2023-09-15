<?php
include('../includes/db.php');
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Si l'utilisateur n'est pas connecté, redirigez-le vers la page de connexion
    header("Location: login.php");
    exit();
}

// Vérifier si le panier existe dans la session
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    // Récupérer les détails de la commande (les produits et quantités) depuis le panier
    $cartProducts = $_SESSION['cart'];

    // Calculer le prix total de la commande
    $totalPrice = 0;
    foreach ($cartProducts as $productID => $productData) {
        $stmt = $conn->prepare('SELECT price FROM products WHERE id = :product_id');
        $stmt->bindParam(':product_id', $productID);
        $stmt->execute();
        $product = $stmt->fetch();

        $productPrice = $product['price'];
        $productTotal = $productPrice * $productData['quantity'];
        $totalPrice += $productTotal;
    }

  // Insérer la commande dans la base de données avec l'ID de l'utilisateur, la date actuelle et le statut "pending"
$userID = $_SESSION['user_id'];
$orderDate = date('Y-m-d');
$orderStatus = 'pending'; // Statut initial de la commande
$stmt = $conn->prepare('INSERT INTO orders (total_price, user_id, order_date, status) VALUES (:total_price, :user_id, :order_date, :status)');
$stmt->bindParam(':total_price', $totalPrice);
$stmt->bindParam(':user_id', $userID);
$stmt->bindParam(':order_date', $orderDate);
$stmt->bindParam(':status', $orderStatus);
$stmt->execute();


    // Récupérer l'ID de la dernière commande insérée
    $orderID = $conn->lastInsertId();

    // Insérer les détails de chaque produit dans la table order_items
    foreach ($cartProducts as $productID => $productData) {
        $quantity = $productData['quantity'];

        $stmt = $conn->prepare('SELECT price FROM products WHERE id = :product_id');
        $stmt->bindParam(':product_id', $productID);
        $stmt->execute();
        $product = $stmt->fetch();

        $stmt = $conn->prepare('INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (:order_id, :product_id, :quantity, :price)');
        $stmt->bindParam(':order_id', $orderID);
        $stmt->bindParam(':product_id', $productID);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':price', $product['price']);
        $stmt->execute();
    }

    // Vider le panier
    unset($_SESSION['cart']);

    // Rediriger vers la page de paiement 
    header("Location: page_de_paiement.php");
    exit();
} else {
    // Rediriger vers la page précédente avec un message d'erreur
    $_SESSION['cart_error'] = "Votre panier est vide.";
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
}
?>
