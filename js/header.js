$(document).ready(function() {
    // Exécuter ce code lorsque le document est complètement chargé

    // Fonction pour gérer la déconnexion lorsqu'on clique sur le bouton avec la classe "exit"
    $(".exit").click(function(event) {
        // Envoi d'une requête AJAX pour appeler le script de déconnexion "logout.php"
        $.ajax({
            url: "../php/logout.php", // Chemin du fichier PHP qui gère la déconnexion
            type: "POST", // Méthode POST pour envoyer la requête
            success: function(response) {
                // Rediriger l'utilisateur vers la page d'accueil après la déconnexion
                window.location.href = "../html/index.html";
            },
            error: function() {
                // Afficher une alerte en cas d'erreur lors de la déconnexion
                alert("Erreur lors de la déconnexion. Veuillez réessayer.");
            }
        });
    });
});
