<?php
session_start();
include 'db_connect.php';

$response = ["success" => false, "count" => 0];

if (isset($_SESSION['userID'])) {
    $userID = intval($_SESSION['userID']);

    // Correction : utiliser la table "cart" et bien exécuter la requête
    $query = $conn->prepare("SELECT SUM(qte) AS total FROM cart WHERE userID = ?");
    $query->bind_param("i", $userID);
    $query->execute();
    $result = $query->get_result()->fetch_assoc();

    if ($result && $result['total'] !== null) {
        $response["success"] = true;
        $response["count"] = intval($result['total']);
    } else {
        $response["success"] = true;
        $response["count"] = 0;
    }
}

header('Content-Type: application/json');
echo json_encode($response);
?>
