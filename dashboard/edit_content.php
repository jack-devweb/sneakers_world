<?php
include 'includes/db.php';
session_start();
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
if ($_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit;
}
//vérifiez si l'ID du contenu est présent dans la requête 
if (!isset($_GET['id'])) {
    die("L'ID du contenu est requis.");
}
//Récupération du contenu
$content_id = intval($_GET['id']);

$stmt = $conn->prepare("SELECT * FROM site_content WHERE id = :id");
$stmt->bindParam(':id', $content_id);
$stmt->execute();
$content = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$content) {
    die("Le contenu n'a pas été trouvé.");
}
//traitez les données POST en fonction du type de contenu sélectionné (texte ou image)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $contentType = $_POST["contentType"];
    $contentName = $_POST["contentName"];

    $imagePath = null;
    if ($contentType === 'image' && isset($_FILES['contentImage']) && $_FILES['contentImage']['error'] == 0) {
        $tmp_name = $_FILES['contentImage']['tmp_name'];
        $name_image = uniqid() . '-' . $_FILES['contentImage']['name'];
        move_uploaded_file($tmp_name, "uploads/$name_image");
        $imagePath = "uploads/$name_image";
    }

    if ($contentType === 'text') {
        $contentValue = $_POST["contentValue"];

        $sql = "UPDATE site_content SET content_type = :content_type, content_value = :content_value, content_name = :content_name WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':content_type', $contentType);
        $stmt->bindParam(':content_value', $contentValue);
        $stmt->bindParam(':content_name', $contentName);
        $stmt->bindParam(':id', $content_id);
    } elseif ($contentType === 'image') {
        $sql = "UPDATE site_content SET content_type = :content_type, content_value = :content_value, content_name = :content_name WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':content_type', $contentType);
        $stmt->bindParam(':content_value', $imagePath);
        $stmt->bindParam(':content_name', $contentName);
        $stmt->bindParam(':id', $content_id);
    }
// mis en place d'une gestion des erreurs en cas d'échec de la mise à jour de la base de données
    try {
        if ($stmt->execute()) {
            header("Location: dashboard.php?msg=Contenu modifié avec succès");
            exit;
        } else {
            echo "<div class='alert alert-danger'>Erreur lors de la modification du contenu.</div>";
        }
    } catch (PDOException $e) {
        if ($e->errorInfo[1] == 1062) {
            echo "<div class='alert alert-danger'>Le nom du contenu existe déjà. Veuillez en choisir un autre.</div>";
        } else {
            echo "<div class='alert alert-danger'>Erreur de base de données : " . $e->getMessage() . "</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier le contenu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="styles.css" />
</head>

<body>
    <div class="container mt-5">
        <h2>Modifier le contenu</h2>
        <form method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="contentType" class="form-label">Type de contenu</label>
                <select class="form-select" id="contentType" name="contentType" required>
                    <option value="text" <?php if ($content['content_type'] === 'text')
                        echo 'selected'; ?>>Texte</option>
                    <option value="image" <?php if ($content['content_type'] === 'image')
                        echo 'selected'; ?>>Image
                    </option>
                </select>
            </div>
            <div class="mb-3">
                <label for="contentName" class="form-label">Nom du contenu</label>
                <input type="text" class="form-control" id="contentName" name="contentName"
                    value="<?php echo $content['content_name']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="contentValue" class="form-label">Valeur du contenu</label>
                <?php if ($content['content_type'] === 'text') { ?>
                    <textarea class="form-control" id="contentValue" name="contentValue"
                        rows="5"><?php echo $content['content_value']; ?></textarea>
                <?php } elseif ($content['content_type'] === 'image') { ?>
                    <img src="<?php echo $content['content_value']; ?>" alt="Image" style="max-width: 100%;">
                    <input type="file" class="form-control" id="contentImage" name="contentImage">
                <?php } ?>
            </div>

            <button type="submit" class="btn btn-primary">Modifier</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm"
        crossorigin="anonymous"></script>
</body>

</html>