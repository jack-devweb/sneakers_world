<?php
session_start();

if(isset($_POST['product_id']) && isset($_POST['quantity'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    if ($quantity > 0) {
        // Mettre à jour la quantité du produit dans le panier
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    } else {
        // Supprimer le produit du panier si la quantité est nulle
        unset($_SESSION['cart'][$product_id]);
    }
}

// Rediriger vers la page panier.php
header("Location: panier.php");
exit();
?>
