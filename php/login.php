<?php
session_start();
session_unset();
include('db_connect.php');

$email = htmlspecialchars($_POST["userEmail"]);
$pwd = htmlspecialchars($_POST["pwd"]);
$role = $email === "admin@gmail.com" ? "admin" : "user";

$stmt = $conn->prepare("SELECT userID, userName, password FROM user WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($userID, $userName, $hashedPwd);
    $stmt->fetch();

    if (password_verify($pwd, $hashedPwd)) {
        $_SESSION["userID"] = $userID;
        $_SESSION["userName"] = $userName;
        $_SESSION["email"] = $email;
        $_SESSION["login"] = true;
        $_SESSION["user_role"] = $role;
        
        echo "success";
    } else {
        echo "Mot de passe incorrect.";
    }
} else {
    echo "Email ou mot de passe incorrect.";
}

$stmt->close();
$conn->close();
?>
