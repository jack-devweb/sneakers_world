<?php
include 'includes/db.php';
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

session_start();
if ($_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: dashboard.php');
    exit;
}

$imageId = intval($_GET['id']);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_carousel_image'])) {
    $imageTitle = $_POST["image_title"];
    $imageDescription = $_POST["image_description"];

    // Vérifiez si une nouvelle image a été téléchargée
    $newImagePath = null;
    if (isset($_FILES['image_path']) && $_FILES['image_path']['error'] == 0) {
        $tmp_name = $_FILES['image_path']['tmp_name'];
        $name_image = uniqid() . '-' . $_FILES['image_path']['name'];
        move_uploaded_file($tmp_name, "uploads/$name_image");
        $newImagePath = "uploads/$name_image";
    }

    // Mettez à jour les informations de l'image du carrousel
    $sql = "UPDATE carousel_images SET title = :title, description = :description";
    
    // Si une nouvelle image a été téléchargée, mettez également à jour le chemin de l'image
    if ($newImagePath) {
        $sql .= ", image_path = :image_path";
    }

    $sql .= " WHERE id = :id";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':title', $imageTitle);
    $stmt->bindParam(':description', $imageDescription);
    $stmt->bindParam(':id', $imageId);

    // Si une nouvelle image a été téléchargée, liez également le nouveau chemin de l'image
    if ($newImagePath) {
        $stmt->bindParam(':image_path', $newImagePath);
    }

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Image du carrousel mise à jour avec succès!</div>";
    } else {
        echo "<div class='alert alert-danger'>Erreur lors de la mise à jour de l'image du carrousel.</div>";
    }
}

// Récupérez les informations de l'image du carrousel à partir de la base de données
$stmt = $conn->prepare("SELECT * FROM carousel_images WHERE id = :id");
$stmt->bindParam(':id', $imageId);
$stmt->execute();
$image = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$image) {
    header('Location: dashboard.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier l'image du carrousel</title>
    <link href="node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h2>Modifier l'image du carrousel</h2>
        <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="edit_carousel_image" value="1">

            <div class="mb-3">
                <label for="image_title" class="form-label">Titre</label>
                <input type="text" class="form-control" id="image_title" name="image_title" value="<?php echo $image['title']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="image_description" class="form-label">Description</label>
                <textarea class="form-control" id="image_description" name="image_description" rows="3" required><?php echo $image['description']; ?></textarea>
            </div>
            <div class="mb-3">
                <label for="image_path" class="form-label">Nouvelle image (facultatif)</label>
                <input type="file" class="form-control" id="image_path" name="image_path">
                <small class="text-muted">Laissez vide pour conserver l'image existante.</small>
            </div>
            <div class="mb-3">
                <label>Image actuelle</label>
                <br>
                <img src="<?php echo $image['image_path']; ?>" alt="Image actuelle du carrousel" width="300">
            </div>

            <button type="submit" class="btn btn-primary">Mettre à jour</button>
        </form>
    </div>

    <!-- jquery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!--  Bootstrap  -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
