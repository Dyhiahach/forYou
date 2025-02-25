<?php
// Démarre une session PHP pour accéder aux variables de session
session_start();

// Inclut le fichier de connexion à la base de données
include 'db_connect.php';

// Initialise un tableau de réponse avec des valeurs par défaut
$response = ["success" => false, "count" => 0];

// Vérifie si l'utilisateur est connecté en vérifiant la présence de 'userID' dans la session
if (isset($_SESSION['userID'])) {
    // Récupère l'ID de l'utilisateur depuis la session et le convertit en entier
    $userID = intval($_SESSION['userID']);

    // Prépare une requête SQL pour récupérer la quantité totale d'articles dans le panier de l'utilisateur
    // La fonction SUM(qte) calcule la somme des quantités (qte) pour tous les articles du panier de l'utilisateur
    $query = $conn->prepare("SELECT SUM(qte) AS total FROM cart WHERE userID = ?");
    
    // Exécute la requête en passant l'ID de l'utilisateur comme paramètre
    $query->execute([$userID]);
    
    // Récupère le résultat de la requête sous forme de tableau associatif
    $result = $query->fetch();

    // Vérifie si le résultat est valide et si la somme totale n'est pas nulle
    if ($result && $result['total'] !== null) {
        // Si c'est le cas, met à jour la réponse pour indiquer le succès et la quantité totale d'articles
        $response["success"] = true;
        $response["count"] = intval($result['total']);
    } else {
        // Si le panier est vide ou qu'il n'y a pas de résultat, met à jour la réponse pour indiquer le succès mais avec un panier vide
        $response["success"] = true;
        $response["count"] = 0;
    }
}

// Définit l'en-tête de la réponse HTTP pour indiquer que le contenu est au format JSON
header('Content-Type: application/json');

// Encode le tableau de réponse en JSON et l'envoie en tant que réponse
echo json_encode($response);
?>