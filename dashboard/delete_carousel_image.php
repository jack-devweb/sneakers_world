<?php
include 'includes/db.php';
if (!isset($_GET['id'])) {
    die("L'ID de l'image du carrousel est requis.");
}

$imageId = intval($_GET['id']);

$stmt = $conn->prepare("SELECT image_path FROM carousel_images WHERE id = :id");
$stmt->bindParam(':id', $imageId, PDO::PARAM_INT);
$stmt->execute();
$imageData = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$imageData) {
    die("L'image du carrousel avec cet ID n'a pas été trouvée.");
}

$imagePath = $imageData['image_path'];

// Supprimer l'image du carrousel de la base de données
$deleteStmt = $conn->prepare("DELETE FROM carousel_images WHERE id = :id");
$deleteStmt->bindParam(':id', $imageId, PDO::PARAM_INT);

// Supprimer l'image du carrousel du système de fichiers
if (unlink($imagePath) && $deleteStmt->execute()) {
    // Rediriger vers dashboard.php avec un message de succès
    header("Location: dashboard.php?msg=Image du carrousel supprimée avec succès");
    exit;
} else {
    die("Erreur lors de la suppression de l'image du carrousel.");
}
?>
