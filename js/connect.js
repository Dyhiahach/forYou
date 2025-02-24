// *******************************
//  Inscription ou Connexion
// *******************************

// Lorsqu'un élément <span> est cliqué
$("span").click(function(){
    // On bascule les classes "active" et "desactive" sur l'élément avec la classe "login"
    $(".login").toggleClass("active desactive");

    // Les éléments avec la classe "active" seront affichés
    $(".active").show();
    // Les éléments avec la classe "desactive" seront cachés
    $(".desactive").hide();

    // On fait de même avec l'élément ayant la classe "register"
    $(".register").toggleClass("desactive active");

    // Affichage et masquage selon la classe active ou désactivée
    $(".active").show();
    $(".desactive").hide();
});

/*******************************************************************/
// *******************************
//  Afficher ou masquer le mot de passe
// *******************************

// Variable globale utilisée pour suivre l'état de visibilité du mot de passe
let e = true; 

// Fonction pour afficher ou masquer le mot de passe
function togglePassword(inputId, eyeIcon) {
    // Récupération de l'input du mot de passe par son ID
    const passwordInput = document.getElementById(inputId);

    // Vérifie si l'input est actuellement caché (type="password")
    if (e) {
        // Change le type de l'input en "text" pour afficher le mot de passe
        passwordInput.setAttribute("type", "text");

        // Change l'icône en "œil ouvert" pour indiquer que le mot de passe est visible
        eyeIcon.classList.remove("fa-eye-slash");
        eyeIcon.classList.add("fa-eye");

        // Met à jour la variable pour refléter l'état du mot de passe visible
        e = false;
    } else { 
        // Change le type de l'input en "password" pour masquer le mot de passe
        passwordInput.setAttribute("type", "password");

        // Change l'icône en "œil barré" pour indiquer que le mot de passe est masqué
        eyeIcon.classList.remove("fa-eye");
        eyeIcon.classList.add("fa-eye-slash");

        // Met à jour la variable pour refléter l'état du mot de passe masqué
        e = true;
    }
}

/********************************************************************/
// *******************************
//  Demander au client de se connecter avant d'accéder au profil
// *******************************

// Récupération du lien vers la page de profil par son ID
document.getElementById('profile-link').addEventListener('click', function (e) {
    // Affichage d'une alerte demandant à l'utilisateur de se connecter avant d'accéder au profil
    alert("Veuillez vous connecter d'abord");
});

/********************************************************************/
// *******************************
//  Gestion de la soumission du formulaire de connexion via AJAX
// *******************************

$(".login-form").submit(function (event) {
    // Empêche le rechargement de la page lors de la soumission du formulaire
    event.preventDefault();

    // Récupération de l'email saisi dans le champ email
    email = $("#userEmail").val();

    // Détermine le rôle en fonction de l'email fourni (l'email "admin@gmail.com" est considéré comme un admin)
    role = email === "admin@gmail.com" ? "admin" : "user";

    // Envoi des données du formulaire via AJAX
    $.ajax({
        url : "../php/login.php", // URL du fichier PHP qui gère la connexion
        type : "POST", // Méthode HTTP utilisée (POST pour envoyer des données)
        data : $(this).serialize(), // Sérialisation du formulaire pour l'envoyer en requête HTTP
        success : function (response) {
            // Vérifie si la réponse du serveur indique un succès de connexion
            if (response.trim() == "success") {
                // Redirection en fonction du rôle de l'utilisateur
                if (role === "admin"){
                    window.location.href = "../php/admin.php"; // Redirection vers la page admin
                } else {
                    window.location.href = "../php/product.php"; // Redirection vers la page utilisateur standard
                }
            } else {
                console.log("success"); // Affichage d'un message dans la console en cas de succès
            }
        },   
        error : function (){
            // Affichage d'un message d'erreur en cas d'échec de la connexion
            alert("Erreur lors de la connexion. Veuillez réessayer.");

            // Réinitialisation du formulaire de connexion
            $(".login-form")[0].reset();
        },
    });
});

/***********************************************************************/
// *******************************
//  Gestion de la soumission du formulaire d'inscription via AJAX
// *******************************

$(".register-form").submit(function (event) {
    // Empêche le rechargement de la page lors de la soumission du formulaire
    event.preventDefault();

    // Récupération du mot de passe saisi dans le champ d'inscription
    var password = $("#pass-register").val();

    /*
    // Vérification si les mots de passe correspondent (commenté pour l'instant)
    var confirmPassword = $("#conf-pass-register").val();

    if (password !== confirmPassword) {
        alert("Les mots de passe ne correspondent pas !");
        $(".register-form")[0].reset(); // Réinitialise le formulaire
        return; // Stoppe l'exécution de la fonction
    }
    */

    // Envoi des données du formulaire d'inscription via AJAX
    $.ajax({
        url : "../php/register.php", // URL du fichier PHP qui gère l'inscription
        type : "POST", // Méthode HTTP utilisée (POST pour envoyer des données)
        data : $(this).serialize(), // Sérialisation du formulaire pour l'envoyer en requête HTTP
        success : function (response) {
            alert(response); // Affichage du message de retour du serveur (ex : succès ou erreur)

            // Réinitialisation du formulaire après soumission
            $(".register-form")[0].reset();

            // Cacher le formulaire d'inscription
            $(".register").hide();

            // Afficher le formulaire de connexion avec une animation
            $(".login").show(800);
        },
        error : function (){
            // Affichage d'un message d'erreur en cas d'échec de l'inscription
            alert("Erreur lors de l'inscription. Veuillez réessayer.");

            // Réinitialisation du formulaire d'inscription
            $(".register-form")[0].reset();
        }
    });
});
