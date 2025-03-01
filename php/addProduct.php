<?php
include("db_connect.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $prodName = $_POST['prodName'];
    $prodDesc = $_POST['product-description'];
    $prodPrice = $_POST['product-price'];

    // Gestion de l'upload d'image
    $targetDir = "../images/";
    $fileName = basename($_FILES["product-image"]["name"]);
    $targetFilePath = $targetDir . $fileName;
    
    if (move_uploaded_file($_FILES["product-image"]["tmp_name"], $targetFilePath)) {
        // Insérer le produit dans la base de données
        $stmt = $conn->prepare("INSERT INTO product (prodName, prodDesc, prodPrice, img) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssis", $prodName, $prodDesc, $prodPrice, $targetFilePath);

        if ($stmt->execute()) {
            echo "success";
        } else {
            echo "Erreur lors de l'ajout du produit.";
        }
        $stmt->close();
    } else {
        echo "Erreur lors du téléchargement de l'image.";
    }
}

$conn->close();
?>
