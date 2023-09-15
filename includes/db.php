<?php
include "config.php";

try {
    // Établissement de la connexion PDO
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erreur de connexion: " . $e->getMessage();
}
if (!$conn) {
    die("Erreur de connexion : " . $conn->errorInfo());
}
?>
