<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once('../vendor/autoload.php');
require_once('../includes/db.php');


// clé secrète Stripe
\Stripe\Stripe::setApiKey('sk_test_51MQuAvBP0eWb8kzX1hqVxHAYzdc6HR6ogi3Vco8lB3qiLKiscIVhMhhqAOZMBPropQlj9Ku2kMnjptZIdCHSHG7I00fQF1J934'); // Remplacez 'VOTRE_CLE_SECRETE_STRIPE' par votre clé secrète Stripe

$confirmationMessage = ''; // Variable pour le message de confirmation

// Vérifiez si le formulaire de paiement a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérez le token de carte de crédit à partir du formulaire
    $token = $_POST['stripeToken'];

    // Montant à facturer (en centimes, par exemple, 2000 pour 20,00 €)
    $montant = 2000;

    // Créer une charge (paiement) avec Stripe
    try {
        $charge = \Stripe\Charge::create([
            'amount' => $montant,
            'currency' => 'eur',
            'source' => $token,
        ]);

        // Le paiement a réussi,
        // insertion dans la base de données
        $stmt = $conn->prepare('INSERT INTO paiements (montant, token, status) VALUES (:montant, :token, :status)');
        $stmt->bindParam(':montant', $montant);
        $stmt->bindParam(':token', $token);
        $stmt->bindValue(':status', 'Réussi'); // Vous pouvez enregistrer le statut du paiement

        if ($stmt->execute()) {
            // Le paiement a été enregistré avec succès dans la base de données
            $confirmationMessage = "Le paiement a été effectué avec succès !";
        } else {
           
            $confirmationMessage = "Il y a eu une erreur lors de l'enregistrement du paiement dans la base de données.";
        }
    } catch (\Stripe\Exception\CardException $e) {
        
        $confirmationMessage = "Le paiement a échoué : " . $e->getError()->message;
    } catch (Exception $e) {
    
        $confirmationMessage = "Une erreur s'est produite : " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de paiement</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css" />
</head>
<body>
    <div class="container2">
        <h1>Confirmation de paiement</h1>
        <p>Merci pour votre paiement.</p>
        <p>Votre paiement a été effectué avec succès !</p>
        <p>Nous vous remercions de votre achat.</p>
        <div class="success-message">Paiement réussi !</div>
    </div>

    <!-- Redirection automatique après 3 secondes -->
    <script>
        setTimeout(function() {
            window.location.href = 'index.php';
        }, 6000);
    </script>
</body>
</html>


