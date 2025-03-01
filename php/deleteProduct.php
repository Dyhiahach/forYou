<?php
include("db_connect.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $prodID = $_POST['prodID'];

    $stmt = $conn->prepare("DELETE FROM product WHERE prodID = ?");
    $stmt->bind_param("i", $prodID);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "Erreur lors de la suppression.";
    }

    $stmt->close();
}

$conn->close();
?>
