<?php
include("db_connect.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $prodID = $_POST['prodID'];
    $quantity = $_POST['quantity'];

    $stmt = $conn->prepare("UPDATE product SET qte = ? WHERE prodID = ?");
    $stmt->bind_param("ii", $quantity, $prodID);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "Erreur lors de la mise à jour.";
    }

    $stmt->close();
}

$conn->close();
?>
