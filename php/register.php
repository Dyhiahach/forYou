<?php
session_start();
include('db_connect.php');

$userName = htmlspecialchars($_POST["userName"]);
$userAddress = htmlspecialchars($_POST["userAddress"]);
$userPhone = htmlspecialchars($_POST["userPhone"]);
$email = htmlspecialchars($_POST["userEmail"]);
$pwd = htmlspecialchars($_POST["pwd"]);

// Vérifier si l'email existe déjà
$check_stmt = $conn->prepare("SELECT email FROM user WHERE email = ?");
$check_stmt->bind_param("s", $email);
$check_stmt->execute();
$check_stmt->store_result();

if ($check_stmt->num_rows > 0) {
    echo "Cet email est déjà utilisé. Veuillez en choisir un autre.";
    $check_stmt->close();
    $conn->close();
    exit();
}
$check_stmt->close();

// Hashage du mot de passe
$hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);

// Insérer l'utilisateur si l'email n'existe pas
$stmt = $conn->prepare("INSERT INTO user (userName, userAddress, userPhone, email, password) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $userName, $userAddress, $userPhone, $email, $hashedPwd);

if ($stmt->execute()) {
    echo "Inscription réussie! Vous pouvez maintenant vous connecter.";
} else { 
    echo "Erreur lors de l'inscription. Veuillez réessayer.";
}

$stmt->close();
$conn->close();
?>
