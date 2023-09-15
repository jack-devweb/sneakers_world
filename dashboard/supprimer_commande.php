<?php
include 'includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $orderId = $_GET['id'];

    // Supprimer d'abord les enregistrements liés dans la table order_items
    $stmtDeleteOrderItems = $conn->prepare('DELETE FROM order_items WHERE order_id = :order_id');
    $stmtDeleteOrderItems->bindParam(':order_id', $orderId);

    // Supprimer la commande de la table orders
    $stmtDeleteOrder = $conn->prepare('DELETE FROM orders WHERE id = :id');
    $stmtDeleteOrder->bindParam(':id', $orderId);

    try {
        $conn->beginTransaction();

        if ($stmtDeleteOrderItems->execute() && $stmtDeleteOrder->execute()) {
            $conn->commit();
            // La commande et les articles associés ont été supprimés avec succès
            header('Location: dashboard.php');
            exit;
        } else {
            $conn->rollBack();
            // Il y a eu une erreur lors de la suppression de la commande
            echo "Une erreur s'est produite lors de la suppression de la commande.";
        }
    } catch (PDOException $e) {
        $conn->rollBack();
        echo "Erreur de base de données : " . $e->getMessage();
    }
} else {
    // Requête non valide
    echo "Requête non valide.";
}
?>
