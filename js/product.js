// Lorsque le document est prêt (chargé et prêt à être manipulé)
$(document).ready(function () {

    // Gestion de l'événement de clic sur le bouton "Ajouter au panier"
    $(".add-to-cart").on("click", function () {
        // Récupère l'ID du produit à partir de l'attribut "data-id" du bouton cliqué
        let productID = $(this).data("id");

        // Envoie une requête AJAX pour ajouter le produit au panier
        $.ajax({
            url: "add_to_cart.php", // URL du script PHP qui gère l'ajout au panier
            type: "POST", // Méthode HTTP utilisée (POST)
            data: { prodID: productID }, // Données envoyées au serveur (ID du produit)
            dataType: "json", // Type de données attendu en réponse (JSON)
            success: function (response) {
                // Si la requête réussit
                if (response.success) {
                    alert("Produit ajouté au panier !"); // Affiche un message de succès
                    updateCartCount(); // Met à jour le compteur du panier
                } else {
                    alert("Erreur : " + response.message); // Affiche un message d'erreur
                }
            },
            error: function () {
                // Si la requête échoue (problème de connexion, etc.)
                alert("Erreur de connexion avec le serveur.");
            }
        });
    });

    // Gestion de l'événement de changement de quantité dans le panier
    $(".update-quantity").on("change", function () {
        // Récupère l'ID du produit et la nouvelle quantité
        let productID = $(this).data("productid");
        let newQuantity = $(this).val();

        // Vérifie que la quantité est valide (au moins 1)
        if (newQuantity < 1) {
            alert("La quantité ne peut pas être inférieure à 1 !");
            return; // Arrête l'exécution si la quantité est invalide
        }

        // Envoie une requête AJAX pour mettre à jour la quantité du produit dans le panier
        $.ajax({
            url: "update_cart.php", // URL du script PHP qui gère la mise à jour de la quantité
            type: "POST", // Méthode HTTP utilisée (POST)
            data: { productID: productID, qte: newQuantity }, // Données envoyées au serveur (ID du produit et nouvelle quantité)
            dataType: "json", // Type de données attendu en réponse (JSON)
            success: function (response) {
                // Si la requête réussit
                if (response.success) {
                    alert("Quantité mise à jour !"); // Affiche un message de succès
                    updateCartCount(); // Met à jour le compteur du panier
                    location.reload(); // Rafraîchit la page pour afficher les changements
                } else {
                    alert("Erreur : " + response.message); // Affiche un message d'erreur
                }
            },
            error: function () {
                // Si la requête échoue (problème de connexion, etc.)
                alert("Erreur de connexion avec le serveur.");
            }
        });
    });

    // Gestion de l'événement de clic sur le bouton "Supprimer du panier"
    $(".remove-from-cart").on("click", function () {
        // Récupère l'ID de l'article à supprimer à partir de l'attribut "data-cartid" du bouton cliqué
        let cartID = $(this).data("cartid");

        // Demande une confirmation avant de supprimer l'article
        if (!confirm("Êtes-vous sûr de vouloir supprimer cet article ?")) {
            return; // Arrête l'exécution si l'utilisateur annule
        }

        // Envoie une requête AJAX pour supprimer l'article du panier
        $.ajax({
            url: "remove_from_cart.php", // URL du script PHP qui gère la suppression
            type: "POST", // Méthode HTTP utilisée (POST)
            data: { cartID: cartID }, // Données envoyées au serveur (ID de l'article à supprimer)
            dataType: "json", // Type de données attendu en réponse (JSON)
            success: function (response) {
                // Si la requête réussit
                if (response.success) {
                    alert("Produit supprimé du panier !"); // Affiche un message de succès
                    updateCartCount(); // Met à jour le compteur du panier
                    location.reload(); // Rafraîchit la page pour afficher les changements
                } else {
                    alert("Erreur : " + response.message); // Affiche un message d'erreur
                }
            },
            error: function () {
                // Si la requête échoue (problème de connexion, etc.)
                alert("Erreur de connexion avec le serveur.");
            }
        });
    });

    // Fonction pour mettre à jour l'affichage du nombre d'articles dans le panier
    function updateCartCount() {
        // Envoie une requête AJAX pour récupérer le nombre total d'articles dans le panier
        $.ajax({
            url: "cart_count.php", // URL du script PHP qui retourne le nombre d'articles
            type: "GET", // Méthode HTTP utilisée (GET)
            dataType: "json", // Type de données attendu en réponse (JSON)
            success: function (response) {
                // Si la requête réussit
                if (response.success) {
                    // Met à jour le texte du compteur du panier avec le nombre d'articles
                    $("#cart-count").text(response.count);
                }
            },
            error: function () {
                // Si la requête échoue (problème de connexion, etc.)
                console.log("Impossible de récupérer le nombre d'articles dans le panier.");
            }
        });
    }

    // Met à jour le nombre d'articles dans le panier au chargement de la page
    updateCartCount();
});