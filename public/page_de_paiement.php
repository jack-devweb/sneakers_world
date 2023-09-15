<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiement par CB</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css" />
    <!-- Inclure la librairie Stripe.js -->
    <script src="https://js.stripe.com/v3/"></script>
</head>
<body>
    <h1>Paiement par carte de crédit</h1>
    <!-- Votre champ de carte de crédit ici -->
    <form id="payment-form">
        <div id="card-element">
            <!-- Une div vide pour le champ de carte de crédit -->
        </div>
        <div id="card-errors" role="alert"></div>
        <button id="submit">Payer</button>
    </form>

    <script>
        //  clé public
var stripe = Stripe('pk_test_51MQuAvBP0eWb8kzXfgzBUNZsOTvyqnyIJ3WgJtx3PW1PMh2Qjze4ynlLI2V0jowc3AVocg5bTWHJ2ul3xlz5P30v00RAAzk2F7');

// Créer un élément de champ de carte de crédit
var elements = stripe.elements();
var card = elements.create('card');

// Ajouter le champ de carte à la div "card-element"
card.mount('#card-element');

// Gérer les erreurs de validation du champ de carte
card.addEventListener('change', function(event) {
    var displayError = document.getElementById('card-errors');
    if (event.error) {
        displayError.textContent = event.error.message;
    } else {
        displayError.textContent = '';
    }
});

// Gérer la soumission du formulaire
var form = document.getElementById('payment-form');
form.addEventListener('submit', function(event) {
    event.preventDefault();
    
    // Désactiver le bouton de paiement pendant le traitement
    document.getElementById('submit').disabled = true;
    
    // Créer un token de carte en utilisant Stripe.js
    stripe.createToken(card).then(function(result) {
        if (result.error) {
            // Gérer les erreurs de création de token
            var errorElement = document.getElementById('card-errors');
            errorElement.textContent = result.error.message;
            
            // Réactiver le bouton de paiement
            document.getElementById('submit').disabled = false;
        } else {
            // Envoyer le token à votre serveur pour traiter le paiement du côté serveur
            var token = result.token.id;
            var amount = 1000; // Montant du paiement en cents (par exemple, 10 €)
            
            // Envoyer le token et le montant à votre serveur via une requête AJAX
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'votre_endpoint_de_paiement_sur_le_serveur.php');
            xhr.setRequestHeader('Content-Type', 'application/json');
            
            xhr.onload = function() {
                if (xhr.status === 200) {
                    // Paiement réussi, rediriger l'utilisateur vers une page de confirmation
                    window.location.href = 'confirmation.php';
                } else {
                    // Erreur lors du paiement, afficher un message d'erreur
                    alert('Erreur lors du paiement. Veuillez réessayer.');
                }
            };
            
            xhr.send(JSON.stringify({ token: token, amount: amount }));
        }
    });
});

    </script>
</body>
</html>
