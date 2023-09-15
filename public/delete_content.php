<?php
include 'includes/db.php';
if (!isset($_GET['id'])) {
    die("L'ID du contenu est requis.");
}

$content_id = intval($_GET['id']);

$stmt = $conn->prepare("DELETE FROM site_content WHERE id = :id");
$stmt->bindParam(':id', $content_id);

if ($stmt->execute()) {
    // Rediriger vers dashboard.php avec un message de succès
    header("Location: dashboard.php?msg=Contenu supprimé avec succès");
    exit;
} else {
    die("Erreur lors de la suppression du contenu.");
}
?>
