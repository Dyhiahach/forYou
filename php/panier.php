<?php
// Démarre une session PHP pour accéder aux variables de session
session_start();

// Inclut le fichier de connexion à la base de données
include 'db_connect.php';

// Vérifie si l'utilisateur est connecté et a le rôle 'user'
// Si l'utilisateur n'est pas connecté ou n'a pas le bon rôle, il est redirigé vers la page d'accueil
if (!isset($_SESSION['login']) || $_SESSION['user_role'] !== 'user') {
    header('location: ../html/index.html');
    exit(); // Arrête l'exécution du script
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ForYou | Panier</title>
    <!-- Inclusion des fichiers CSS pour le style -->
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="stylesheet" href="../css/body.css">
    <link rel="stylesheet" href="../css/panier.css">
    <link rel="stylesheet" href="../css/logo.css">
    <!-- Inclusion de jQuery et FontAwesome pour les fonctionnalités interactives et les icônes -->
    <script src="../jquery-3.7.1.js"></script>
    <script src="https://kit.fontawesome.com/a6c1691723.js" crossorigin="anonymous"></script>
</head>
<!-- Le corps de la page contient un attribut data-id avec l'ID de l'utilisateur connecté -->
<body data-id="<?php echo $_SESSION['userID']; ?>">
    <header id="head">
        <div class="navbar">
            <div class="logo">
                <!-- Logo de l'application -->
                <p class="logo">For<span class="highlight">You</span><span id="sp">.</span></p>
            </div>
            <nav>
                <ul>
                    <!-- Liens de navigation -->
                    <li><a href="index.html" class="btn-cnn"><i class="fas fa-home"></i></a></li>
                    <li><a href="product.php"><i class="fas fa-box-open"></i></a></li>
                    <li>
                        <a href="panier.php" class="btn-cart">
                            <i class="fa-solid fa-cart-shopping"></i>
                            <span class="nbr">(0)</span> <!-- Placeholder pour le nombre d'articles dans le panier -->
                        </a>
                    </li>
                    <li><a href="profil.html" class="btn-cnn"><i class="fas fa-user"></i></a></li>
                    <li><a href="index.html#contact"><i class="fa-solid fa-message"></i></a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Section principale pour afficher le panier -->
    <section class="section-cart" id="panier">
        <h1>Les Produits sélectionnés :</h1>
        <table>
            <thead>
                <tr>
                    <!-- En-têtes du tableau -->
                    <th>Image</th>
                    <th>Nom</th>
                    <th>Description</th>
                    <th>Prix</th>
                    <th>Quantité</th>
                    <th>Total</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Vérifie si l'utilisateur est connecté
                if (isset($_SESSION['userID'])) {
                    // Récupère l'ID de l'utilisateur et le convertit en entier
                    $userID = intval($_SESSION['userID']);

                    // Prépare une requête SQL pour récupérer les articles du panier de l'utilisateur
                    // La requête joint les tables 'cart' et 'product' pour obtenir les détails des produits
                    $query = $conn->prepare("SELECT c.cartID, p.img, p.prodName, p.prodDesc, p.prodPrice, c.qte, (p.prodPrice * c.qte) AS total FROM cart c JOIN product p ON c.prodID = p.prodID WHERE c.userID = ?");
                    $query->bind_param("i", $userID); // Lie l'ID de l'utilisateur à la requête
                    $query->execute(); // Exécute la requête
                    $result = $query->get_result(); // Récupère le résultat de la requête
                    $cartItems = $result->fetch_all(MYSQLI_ASSOC); // Convertit le résultat en tableau associatif
                    $totalPrice = 0; // Initialise le prix total à 0

                    // Boucle sur chaque article du panier pour les afficher dans le tableau
                    foreach ($cartItems as $item) {
                        echo "<tr>
                                <td><img src='" . $item['img'] . "' alt='Produit' width='50'></td>
                                <td>" . $item['prodName'] . "</td>
                                <td>" . $item['prodDesc'] . "</td>
                                <td>" . $item['prodPrice'] . " DA</td>
                                <td>" . $item['qte'] . "</td>
                                <td>" . $item['total'] . " DA</td>
                                <td>
                                    <!-- Formulaire pour supprimer un article du panier -->
                                    <form method='POST' action='remove_from_cart.php'>
                                        <input type='hidden' name='cartID' value='" . $item['cartID'] . "'>
                                        <button type='submit'>Supprimer</button>
                                    </form>
                                </td>
                            </tr>";
                        $totalPrice += $item['total']; // Ajoute le total de l'article au prix total
                    }
                    // Affiche le prix total à payer
                    echo "<tr><td colspan='5' align='right'><strong>Total à payer :</strong></td><td colspan='2'><strong>" . $totalPrice . " DA</strong></td></tr>";
                } else {
                    // Si l'utilisateur n'est pas connecté, affiche un message
                    echo "<tr><td colspan='7'>Veuillez vous connecter pour voir votre panier.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </section>

    <!-- Pied de page -->
    <footer>
        <div class="footer-container">
            <div class="footer-links">
                <h2>Liens Utiles</h2>
                <a href="#">Politiques de confidentialité</a>
                <a href="#">Conditions d'utilisation</a>
                <a href="#">Retour & Échanges</a>
                <a href="#">FAQ</a>
            </div>
            <div class="footer-contact">
                <h2>Contact</h2>
                <p>Email: contact@foryou.com</p>
                <p>Téléphone: +213 555 123 456</p>
            </div>
            <div class="footer-social">
                <h2>Suivez-nous</h2>
                <a href="#" class="social-icon"><i class="fa-brands fa-facebook-f"></i></a>
                <a href="#" class="social-icon"><i class="fa-brands fa-instagram"></i></a>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2024 ForYou. Tous droits réservés</p>
        </div>
    </footer>

    <!-- Inclusion du fichier JavaScript pour les fonctionnalités interactives -->
    <script src="../js/product.js"></script>
</body>
</html>