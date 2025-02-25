<?php
$servername = "localhost"; // Correction ici : "servername" au lieu de "servernme"
$username = "root";
$password = "";
$dbname = "forYou";

// Connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}
// echo "Connexion réussie <br>"; // Décommentez pour vérifier la connexion
?>