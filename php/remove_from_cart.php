<?php
// Démarre une session PHP pour accéder aux variables de session
session_start();

// Inclut le fichier de connexion à la base de données
include 'db_connect.php';

// Définit l'en-tête de la réponse HTTP pour indiquer que le contenu est au format JSON
header('Content-Type: application/json');

// Vérifie si l'utilisateur est connecté en vérifiant la présence de 'userID' dans la session
if (!isset($_SESSION['userID'])) {
    http_response_code(401); // Code HTTP 401 : Non autorisé
    echo json_encode(["success" => false, "message" => "Utilisateur non connecté."]);
    exit; // Arrête l'exécution du script
}

// Vérifie si l'ID du produit est fourni dans la requête POST
if (!isset($_POST['prodID'])) {
    http_response_code(400); // Code HTTP 400 : Mauvaise requête
    echo json_encode(["success" => false, "message" => "ID du produit non spécifié."]);
    exit; // Arrête l'exécution du script
}

// Récupère l'ID de l'utilisateur et l'ID du produit depuis les données de session et POST
$userID = intval($_SESSION['userID']); // Convertit l'ID de l'utilisateur en entier
$prodID = intval($_POST['prodID']); // Convertit l'ID du produit en entier

try {
    // Vérifie si le produit est bien présent dans le panier de l'utilisateur
    $checkCart = $conn->prepare("SELECT * FROM cart WHERE userID = ? AND prodID = ?");
    $checkCart->bind_param("ii", $userID, $prodID); // Lie les paramètres à la requête
    $checkCart->execute(); // Exécute la requête
    $checkCart->store_result(); // Stocke le résultat pour vérifier le nombre de lignes

    // Si le produit n'est pas trouvé dans le panier, renvoie une erreur 404
    if ($checkCart->num_rows === 0) {
        http_response_code(404); // Code HTTP 404 : Non trouvé
        echo json_encode(["success" => false, "message" => "Produit non trouvé dans le panier."]);
        exit; // Arrête l'exécution du script
    }

    // Supprime le produit du panier de l'utilisateur
    $deleteCart = $conn->prepare("DELETE FROM cart WHERE userID = ? AND prodID = ?");
    $deleteCart->bind_param("ii", $userID, $prodID); // Lie les paramètres à la requête
    $deleteCart->execute(); // Exécute la requête

    // Vérifie si la suppression a réussi en vérifiant le nombre de lignes affectées
    if ($deleteCart->affected_rows === 0) {
        http_response_code(500); // Code HTTP 500 : Erreur serveur
        echo json_encode(["success" => false, "message" => "Échec de la suppression du produit."]);
        exit; // Arrête l'exécution du script
    }

    // Récupère le nombre total d'articles restants dans le panier de l'utilisateur
    $cartCountQuery = $conn->prepare("SELECT SUM(qte) AS total FROM cart WHERE userID = ?");
    $cartCountQuery->bind_param("i", $userID); // Lie l'ID de l'utilisateur à la requête
    $cartCountQuery->execute(); // Exécute la requête
    $cartCountQuery->bind_result($total); // Lie le résultat à la variable $total
    $cartCountQuery->fetch(); // Récupère la valeur du résultat

    // Renvoie une réponse JSON indiquant le succès de l'opération
    http_response_code(200); // Code HTTP 200 : Succès
    echo json_encode([
        "success" => true,
        "message" => "Produit supprimé du panier.",
        "cartCount" => $total ?? 0 // Renvoie le nombre total d'articles dans le panier (ou 0 si vide)
    ]);

} catch (Exception $e) {
    // En cas d'erreur, renvoie une réponse JSON avec un message d'erreur
    http_response_code(500); // Code HTTP 500 : Erreur serveur
    echo json_encode(["success" => false, "message" => "Erreur serveur : " . $e->getMessage()]);
}
?>