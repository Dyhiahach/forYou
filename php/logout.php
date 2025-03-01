<?php
    // Démarrer la session pour pouvoir la manipuler
    session_start();

    // Supprimer toutes les variables de session
    session_unset();

    // Détruire complètement la session (supprime aussi le fichier de session sur le serveur)
    session_destroy();

    // Rediriger l'utilisateur vers la page d'accueil après la déconnexion
    header('location: ../html/index.html');

    // Arrêter l'exécution du script après la redirection
    exit();
?>
