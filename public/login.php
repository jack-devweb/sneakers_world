<?php
include('../includes/db.php');


session_start();

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Requête pour obtenir les détails de l'utilisateur basée sur l'email
    $sql = "SELECT id, password, role FROM users WHERE email = :email";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() == 1) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (password_verify($password, $row["password"])) {
            $_SESSION["loggedin"] = true;
            $_SESSION["id"] = $row["id"];
            $_SESSION["role"] = $row["role"];

            // Stocker l'ID de l'utilisateur dans la session
            $_SESSION["user_id"] = $row["id"];

            // Rediriger l'administrateur vers le tableau de bord
            if ($row["role"] == "admin") {
                header("Location: <public>dashboard.php");
            } else {
                header("Location: index.php");
            }
            exit;
        } else {
            $error = "Mot de passe incorrect!";
        }
    } else {
        $error = "Email non trouvé!";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css" />
</head>
<body>

<div class="login-box">
    <p>Login</p>
    <form method="post">
        <div class="user-box">
            <input type="email" name="email" required>
            <label>Email</label>
        </div>
        <div class="user-box">
            <input type="password" name="password" required>
            <label>Password</label>
        </div>
        <button type="submit">
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            Submit
        </button>
    </form>
    <p>Don't have an account? <a href="inscription.php" class="a2">Sign up!</a></p>
</div>

</body>
</html>
