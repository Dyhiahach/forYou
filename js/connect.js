// *******************************
//  Inscription ou Connexion
// *******************************

// Lorsqu'un élément <span> est cliqué
$("span").click(function(){
    // Bascule les classes "active" et "desactive" sur l'élément avec la classe "login"
    $(".login").toggleClass("active desactive");
    
    // Affiche les éléments ayant la classe "active" et cache ceux ayant la classe "desactive"
    $(".active").show();
    $(".desactive").hide();
    
    // Applique le même comportement à l'élément avec la classe "register"
    $(".register").toggleClass("desactive active");
    $(".active").show();
    $(".desactive").hide();
});

/*******************************************************************/
// *******************************
//  Afficher ou masquer le mot de passe
// *******************************

// Variable globale pour suivre l'état de visibilité du mot de passe
let e = true; 

// Fonction pour afficher ou masquer le mot de passe
function togglePassword(inputId, eyeIcon) {
    // Récupère l'input du mot de passe via son ID
    const passwordInput = document.getElementById(inputId);
    
    // Vérifie l'état actuel du mot de passe
    if (e) {
        // Rend le mot de passe visible
        passwordInput.setAttribute("type", "text");
        
        // Change l'icône en "œil ouvert"
        eyeIcon.classList.remove("fa-eye-slash");
        eyeIcon.classList.add("fa-eye");
        
        // Met à jour la variable d'état
        e = false;
    } else { 
        // Cache le mot de passe
        passwordInput.setAttribute("type", "password");
        
        // Change l'icône en "œil barré"
        eyeIcon.classList.remove("fa-eye");
        eyeIcon.classList.add("fa-eye-slash");
        
        // Met à jour la variable d'état
        e = true;
    }
}

/********************************************************************/
// *******************************
//  Demander au client de se connecter avant d'accéder au profil
// *******************************

// Ajoute un écouteur d'événements sur le lien du profil
document.getElementById('profile-link').addEventListener('click', function (e) {
    // Affiche une alerte demandant à l'utilisateur de se connecter
    alert("Veuillez vous connecter d'abord");
});

/********************************************************************/
// *******************************
//  Gestion de la soumission du formulaire de connexion via AJAX
// *******************************

// Gestion de l'événement de soumission du formulaire de connexion
// Gestion de la soumission du formulaire de connexion via AJAX
$(".login-form").submit(function (event) {
    event.preventDefault(); // Empêche le rechargement de la page
    
    let email = $("#userEmail").val();
    let role = (email === "admin@gmail.com") ? "admin" : "user";

    $.ajax({
        url: "../php/login.php", // Script de connexion
        type: "POST",
        data: $(this).serialize(),
        success: function (response) {
            if (response.trim() == "success") {
                // Redirection selon le rôle de l'utilisateur
                window.location.href = (role === "admin") ? "../php/admin.php" : "../php/product.php";
            } else {
                alert(response); // Affiche l'erreur renvoyée par le serveur
            }
        },   
        error: function () {
            alert("Erreur lors de la connexion. Veuillez réessayer.");
            $(".login-form")[0].reset();
        },
    });
});


/***********************************************************************/
// *******************************
//  Gestion de la soumission du formulaire d'inscription via AJAX
// *******************************

// Gestion de l'événement de soumission du formulaire d'inscription
$(".register-form").submit(function (event) {
    event.preventDefault();

    $.ajax({
        url: "../php/register.php",
        type: "POST",
        data: $(this).serialize(),
        success: function (response) {
            if (response.includes("déjà utilisé")) {
                alert(response); // Affiche un message si l'email est en double
            } else if (response.includes("Inscription réussie")) {
                alert(response);
                $(".register-form")[0].reset();
                $(".register").hide();
                $(".login").fadeIn(800);
            } else {
                alert("Erreur : " + response);
            }
        },
        error: function () {
            alert("Erreur lors de l'inscription. Veuillez réessayer.");
            $(".register-form")[0].reset();
        }
    });
});
