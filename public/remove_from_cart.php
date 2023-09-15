<?php
session_start();

if(isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];

    // Supprimer le produit du panier
    unset($_SESSION['cart'][$product_id]);
}

// Rediriger vers la page panier.php
header("Location: panier.php");
exit();
?>
