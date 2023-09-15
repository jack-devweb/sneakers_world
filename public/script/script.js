var menuHolder = document.getElementById('menuHolder')
var siteBrand = document.getElementById('siteBrand')
function menuToggle(){
  if(menuHolder.className === "drawMenu") menuHolder.className = ""
  else menuHolder.className = "drawMenu"
}
if(window.innerWidth < 426) siteBrand.innerHTML = "MAS"
window.onresize = function(){
  if(window.innerWidth < 420) siteBrand.innerHTML = "MAS"
  else siteBrand.innerHTML = "MY AWESOME WEBSITE"
}
// Fonction pour ajouter un produit au panier
function addToCart(productId) {
  $.post("add_to_cart.php", { product_id: productId }, function() {
      // Après avoir ajouté au panier, recharger la page
      location.reload();
  });
}

// Gestionnaire d'événements pour le bouton "Ajouter au panier"
$(document).on("click", ".btn-add-to-cart", function() {
  var productId = $(this).data("product-id");
  addToCart(productId);
});
