<?php
require_once ('../vendor/autoload.php'); 
\Stripe\Stripe::setApiKey('sk_test_51MQuAvBP0eWb8kzX1hqVxHAYzdc6HR6ogi3Vco8lB3qiLKiscIVhMhhqAOZMBPropQlj9Ku2kMnjptZIdCHSHG7I00fQF1J934'); // Remplacez par votre clé secrète Stripe

// Récupérer les données du formulaire
$input = file_get_contents('php://input');
$data = json_decode($input);

// Créer une charge avec Stripe
try {
    $charge = \Stripe\Charge::create([
        'amount' => $data->amount,
        'currency' => 'EUR', // Changez la devise si nécessaire
        'source' => $data->token,
        'description' => 'Paiement pour votre commande'
    ]);

    // Si la charge est réussie, vous pouvez enregistrer les détails de la commande dans votre base de données
    if ($charge->status === 'reussi') {
        // Récupérez d'autres informations sur la commande, par exemple, les produits et les quantités depuis $_SESSION['cart']

        // Enregistrez les détails de la commande dans la base de données
        $stmt = $conn->prepare('INSERT INTO commandes (montant, token, statut) VALUES (:montant, :token, :statut)');
        $stmt->bindParam(':montant', $data->amount);
        $stmt->bindParam(':token', $data->token);
        $stmt->bindValue(':statut', 'Réussi'); // Vous pouvez enregistrer le statut de la commande
        
        if ($stmt->execute()) {
            // La commande a été enregistrée avec succès dans la base de données
            echo "Le paiement a été effectué avec succès. Votre commande a été enregistrée.";
        } else {
            // Il y a eu un problème lors de l'enregistrement de la commande dans la base de données
            echo "Il y a eu une erreur lors de l'enregistrement de la commande dans la base de données.";
        }
    } else {
        // La charge a échoué pour une raison inconnue
        echo "Le paiement a échoué pour une raison inconnue.";
    }
} catch (\Stripe\Exception\CardException $e) {
    // Le paiement a échoué en raison d'une erreur de carte
    echo "Le paiement a échoué : " . $e->getError()->message;
} catch (Exception $e) {
    // Une autre erreur s'est produite
    echo "Une erreur s'est produite : " . $e->getMessage();
}


