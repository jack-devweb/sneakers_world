<?php
// Récupérer le message de confirmation depuis l'URL
$message = isset($_GET['msg']) ? $_GET['msg'] : 'Mise à jour du produit réussie!';

// Afficher le message de confirmation
echo $message;
?>
 <script>
        setTimeout(function() {
            window.location.href = 'dashboard.php';
        }, 3000);
    </script>
