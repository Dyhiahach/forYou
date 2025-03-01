$(document).ready(function () {

    // Gestion du clic sur le bouton "Ajouter au panier"
    $(".add-to-cart").on("click", function () {
        let productID = $(this).data("id"); // Récupérer l'ID du produit

        $.ajax({
            url: "../php/add_to_cart.php", // Chemin correct du fichier PHP
            type: "POST",
            data: { prodID: productID },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    updateCartCount(); // Mise à jour du compteur
                } else {
                    alert("Erreur : " + response.message);
                }
            },
            error: function () {
                alert("Erreur de connexion avec le serveur.");
            }
        });
    });

    // Gestion de la modification de quantité dans le panier
    $(".update-quantity").on("change", function () {
        let productID = $(this).data("productid");
        let newQuantity = parseInt($(this).val()); // Convertir en nombre

        if (newQuantity < 1) {
            alert("La quantité ne peut pas être inférieure à 1 !");
            return;
        }

        $.ajax({
            url: "../php/update_cart.php",
            type: "POST",
            data: { productID: productID, qte: newQuantity },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    alert("Quantité mise à jour !");
                    updateCartCount(); // Mettre à jour le compteur
                    location.reload(); // Recharger la page
                } else {
                    alert("Erreur : " + response.message);
                }
            },
            error: function () {
                alert("Erreur de connexion avec le serveur.");
            }
        });
    });

    // Gestion de la suppression d'un article du panier
    $(".remove-from-cart").on("click", function () {
        let cartID = $(this).data("cartid");

        if (!confirm("Êtes-vous sûr de vouloir supprimer cet article ?")) {
            return;
        }

        $.ajax({
            url: "../php/remove_from_cart.php",
            type: "POST",
            data: { cartID: cartID },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    alert("Produit supprimé du panier !");
                    updateCartCount(); // Mise à jour du compteur
                    location.reload(); // Recharger la page
                } else {
                    alert("Erreur : " + response.message);
                }
            },
            error: function () {
                alert("Erreur de connexion avec le serveur.");
            }
        });
    });

    // Fonction pour mettre à jour le compteur du panier
    function updateCartCount() {
        $.ajax({
            url: "../php/cart_count.php",
            type: "GET",
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    $(".nbr").text(response.count); // Affichage du nombre d'articles
                }
            },
            error: function () {
                console.log("Impossible de récupérer le nombre d'articles dans le panier.");
            }
        });
    }

    // Mise à jour du compteur au chargement de la page
    updateCartCount();
});
