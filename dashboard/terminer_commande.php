<?php
// Inclure votre fichier de configuration de la base de données (db.php) ici
include 'includes/db.php';

// Vérifier si un identifiant de commande est passé dans l'URL
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $orderId = $_GET['id'];

    // Mettez à jour le statut de la commande dans la base de données en tant qu'"expédiée"
    $stmt = $conn->prepare('UPDATE orders SET status = :status WHERE id = :id');
    $stmt->bindParam(':id', $orderId);
    $stmt->bindValue(':status', 'expedié'); // Mise à jour du statut ici
    
    if ($stmt->execute()) {
        // La commande a été marquée comme expédiée avec succès
        header('Location: dashboard.php'); // Redirigez l'utilisateur vers le tableau de bord
        exit;
    } else {
        // Il y a eu une erreur lors de la mise à jour du statut
        echo "Une erreur s'est produite lors de la mise à jour du statut de la commande.";
    }
} else {
    // Si aucun identifiant de commande n'est passé dans l'URL, affichez un message d'erreur
    echo "Identifiant de commande manquant.";
}
?>
