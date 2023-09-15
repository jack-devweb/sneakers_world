<?php
include ('../includes/db.php');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $address = $_POST["address"];
    $city = $_POST["city"];
    $postalCode = $_POST["postal_code"];
    $phoneNumber = $_POST["phone_number"];

    $sql = "INSERT INTO users (username, email, password, address, city, postal_code, phone_number) VALUES (:username, :email, :password, :address, :city, :postal_code, :phone_number)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':address', $address);
    $stmt->bindParam(':city', $city);
    $stmt->bindParam(':postal_code', $postalCode);
    $stmt->bindParam(':phone_number', $phoneNumber);

    if ($stmt->execute()) {
        header("Location: login.php?registration_success=1");
        exit; 
    } else {
        echo "Erreur lors de l'inscription.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css" />
</head>
<body>
    <div class="form-container">
    <form class="form" method="post">
    <span class="title">Register</span>
    <label for="username" class="label">Username</label>
    <input type="text" id="username" name="username" required="" class="input">
    <label for="email" class="label">Email</label>
    <input type="email" id="email" name="email" required="" class="input">
    <label for="password" class="label">Password</label>
    <input type="password" id="password" name="password" required="" class="input">
    <label for="address" class="label">Address</label>
    <input type="text" id="address" name="address" required="" class="input">
    <label for="city" class="label">City</label>
    <input type="text" id="city" name="city" required="" class="input">
    <label for="postal_code" class="label">Postal Code</label>
    <input type="text" id="postal_code" name="postal_code" required="" class="input">
    <label for="phone_number" class="label">Phone Number</label>
    <input type="text" id="phone_number" name="phone_number" required="" class="input">
    <button type="submit" class="submit">Register</button>
</form>

</div>
</body>
</html>
