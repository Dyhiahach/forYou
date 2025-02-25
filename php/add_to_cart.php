<?php
// Démarre la session pour accéder aux variables de session
session_start();

// Inclut le fichier de connexion à la base de données
include 'db_connect.php';

// Définit le type de contenu de la réponse comme JSON
header('Content-Type: application/json');

// Vérifie si l'utilisateur est connecté en vérifiant la présence de 'userID' dans la session
if (!isset($_SESSION['userID'])) {
    http_response_code(401); // Retourne un code d'état HTTP 401 (Non autorisé)
    echo json_encode(["success" => false, "message" => "Utilisateur non connecté."]); // Retourne un message d'erreur
    exit; // Arrête l'exécution du script
}

// Vérifie si l'ID du produit est fourni dans la requête POST
if (!isset($_POST['prodID'])) {
    http_response_code(400); // Retourne un code d'état HTTP 400 (Mauvaise requête)
    echo json_encode(["success" => false, "message" => "ID du produit non spécifié."]); // Retourne un message d'erreur
    exit; // Arrête l'exécution du script
}

// Récupère l'ID de l'utilisateur à partir de la session et le convertit en entier
$userID = intval($_SESSION['userID']);

// Récupère l'ID du produit à partir de la requête POST et le convertit en entier
$prodID = intval($_POST['prodID']);

// Récupère la quantité à partir de la requête POST, ou utilise 1 par défaut si non spécifiée
$qte = isset($_POST['qte']) ? intval($_POST['qte']) : 1;

// Vérifie que la quantité est valide (au moins 1)
if ($qte < 1) {
    http_response_code(400); // Retourne un code d'état HTTP 400 (Mauvaise requête)
    echo json_encode(["success" => false, "message" => "La quantité doit être au moins 1."]); // Retourne un message d'erreur
    exit; // Arrête l'exécution du script
}

try {
    // Vérifie si le produit existe dans la base de données
    $checkProduct = $conn->prepare("SELECT prodID FROM product WHERE prodID = ?");
    $checkProduct->bind_param("i", $prodID); // Lie l'ID du produit à la requête
    $checkProduct->execute(); // Exécute la requête
    $checkProduct->store_result(); // Stocke le résultat pour vérification

    // Si le produit n'existe pas
    if ($checkProduct->num_rows === 0) {
        http_response_code(404); // Retourne un code d'état HTTP 404 (Non trouvé)
        echo json_encode(["success" => false, "message" => "Produit non trouvé."]); // Retourne un message d'erreur
        exit; // Arrête l'exécution du script
    }

    // Vérifie si le produit est déjà dans le panier de l'utilisateur
    $checkCart = $conn->prepare("SELECT qte FROM cart WHERE userID = ? AND prodID = ?");
    $checkCart->bind_param("ii", $userID, $prodID); // Lie l'ID de l'utilisateur et l'ID du produit à la requête
    $checkCart->execute(); // Exécute la requête
    $checkCart->store_result(); // Stocke le résultat pour vérification

    // Si le produit est déjà dans le panier
    if ($checkCart->num_rows > 0) {
        // Met à jour la quantité du produit dans le panier
        $updateCart = $conn->prepare("UPDATE cart SET qte = qte + ? WHERE userID = ? AND prodID = ?");
        $updateCart->bind_param("iii", $qte, $userID, $prodID); // Lie la quantité, l'ID de l'utilisateur et l'ID du produit à la requête
        $updateCart->execute(); // Exécute la requête
    } else {
        // Si le produit n'est pas dans le panier, l'ajoute
        $stmt = $conn->prepare("INSERT INTO cart (userID, prodID, qte) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $userID, $prodID, $qte); // Lie l'ID de l'utilisateur, l'ID du produit et la quantité à la requête
        $stmt->execute(); // Exécute la requête
    }

    // Récupère le nombre total d'articles dans le panier de l'utilisateur
    $cartCountQuery = $conn->prepare("SELECT SUM(qte) AS total FROM cart WHERE userID = ?");
    $cartCountQuery->bind_param("i", $userID); // Lie l'ID de l'utilisateur à la requête
    $cartCountQuery->execute(); // Exécute la requête
    $cartCountQuery->bind_result($total); // Lie le résultat à la variable $total
    $cartCountQuery->fetch(); // Récupère le résultat

    // Retourne une réponse JSON indiquant que l'opération a réussi
    http_response_code(200); // Retourne un code d'état HTTP 200 (Succès)
    echo json_encode([
        "success" => true,
        "message" => "Produit ajouté au panier.",
        "cartCount" => $total ?? 0 // Retourne le nombre total d'articles dans le panier (ou 0 si aucun)
    ]);

} catch (Exception $e) {
    // En cas d'erreur, retourne un message d'erreur
    http_response_code(500); // Retourne un code d'état HTTP 500 (Erreur serveur)
    echo json_encode(["success" => false, "message" => "Erreur serveur : " . $e->getMessage()]); // Retourne un message d'erreur
}
?>