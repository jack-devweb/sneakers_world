<?php
session_start();

if(isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];
    $is_promo = isset($_POST['is_promo']) ? true : false; // Récupérer la valeur de la case à cocher

    // Si le panier n'existe pas dans la session, le créer
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    // Ajouter le produit au panier
    if (isset($_SESSION['cart'][$product_id])) {
        // Si le produit existe déjà dans le panier, augmenter la quantité
        $_SESSION['cart'][$product_id]['quantity'] += 1;
    } else {
        // Sinon, ajouter le produit avec une quantité de 1
        $_SESSION['cart'][$product_id] = array(
            'product_id' => $product_id,
            'quantity' => 1,
            'is_promo' => $is_promo // Ajouter la valeur de la promotion
        );
    }
}

// Rediriger vers la page index.php
header("Location: index.php");
exit();
?>
