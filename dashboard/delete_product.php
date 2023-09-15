<?php
include 'includes/db.php';
if (!isset($_GET['id'])) {
    die("L'ID du produit est requis.");
}

$product_id = intval($_GET['id']);

try {
    // Commencez une transaction
    $conn->beginTransaction();

    // Supprimez les enregistrements associés dans la table order_items
    $stmt_order_items = $conn->prepare("DELETE FROM order_items WHERE product_id = :product_id");
    $stmt_order_items->bindParam(':product_id', $product_id);

    if (!$stmt_order_items->execute()) {
        throw new Exception("Erreur lors de la suppression des enregistrements order_items.");
    }

    // Supprimez le produit de la table products
    $stmt_products = $conn->prepare("DELETE FROM products WHERE id = :id");
    $stmt_products->bindParam(':id', $product_id);

    if (!$stmt_products->execute()) {
        throw new Exception("Erreur lors de la suppression du produit.");
    }

    // Validez la transaction
    $conn->commit();

    // Redirigez vers dashboard.php avec un message de succès
    header("Location: dashboard.php?msg=Produit supprimé avec succès");
    exit;
} catch (Exception $e) {
    // En cas d'erreur, annulez la transaction et affichez un message d'erreur
    $conn->rollback();
    die("Une erreur s'est produite : " . $e->getMessage());
}

?>
