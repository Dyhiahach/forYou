<?php
// Démarre une session PHP pour accéder aux variables de session
session_start();

// Vérifie si l'utilisateur est connecté et a le rôle "user"
// Si l'utilisateur n'est pas connecté ou n'a pas le bon rôle, il est redirigé vers la page d'accueil
if (!isset($_SESSION['login']) || $_SESSION['user_role'] !== 'user') {
    header('location: ../html/index.html');
    exit(); // Arrête l'exécution du script
}

// Inclusion du fichier de connexion à la base de données
include("db_connect.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ForYou | Produits</title>
    <!-- Inclusion des fichiers CSS pour le style -->
    <link rel="stylesheet" href="../css/body.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/product.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="stylesheet" href="../css/logo.css">
    <!-- Inclusion de FontAwesome pour les icônes et jQuery pour les fonctionnalités interactives -->
    <script src="https://kit.fontawesome.com/a6c1691723.js" crossorigin="anonymous"></script>
    <script src="../jquery-3.7.1.js"></script>
</head>
<!-- Le corps de la page contient un attribut data-id avec l'ID de l'utilisateur connecté -->
<body data-id="<?php echo $_SESSION['userID']; ?>">
    <header>
        <div class="container">
            <div class="navbar">
                <div class="logo">
                    <!-- Logo de l'application -->
                    <p class="logo">For<span class="highlight">You</span><span id="sp">.</span></p>
                </div>
                <nav>
                    <ul>
                        <!-- Liens de navigation -->
                        <li><a href="../html/index.html">Accueil</a></li>
                        <li><a href="#product">Produits</a></li>
                        <li><a href="../html/index.html#contact">Contact</a></li>
                        <li>
                            <a href="panier.php" class="btn-cart">
                                <i class="fa-solid fa-cart-shopping"></i>
                                <span class="nbr">(0)</span> <!-- Placeholder pour le nombre d'articles dans le panier -->
                            </a>
                        </li>
                        <li class="exit">
                            <a href="logout.php">
                                Déconnexion
                                <i class="fa-solid fa-right-from-bracket"></i>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <!-- Section principale pour afficher les produits -->
    <section id="product">
        <?php
        // Prépare une requête SQL pour récupérer tous les produits de la base de données
        $stmt = $conn->prepare("SELECT * FROM product");
        $stmt->execute(); // Exécute la requête
        $result = $stmt->get_result(); // Récupère le résultat de la requête

        // Vérifie s'il y a des produits à afficher
        if ($result->num_rows > 0) {
            // Boucle sur chaque produit pour les afficher
            while ($row = $result->fetch_assoc()) {
                $img = $row['img']; // Récupère l'URL de l'image du produit
        ?>
                <!-- Structure HTML pour chaque produit -->
                <div class="product" data-id="<?php echo $row['prodID']; ?>">
                    <div class="product-image">
                        <img src="<?php echo $img; ?>" alt="image de l'article">
                    </div>
                    <h2 class="product-name"><?php echo $row['prodName']; ?></h2>
                    <div class="product-description"><?php echo $row['prodDesc']; ?></div>
                    <div class="product-price"><?php echo $row['prodPrice']; ?> <span>DA</span></div>
                    <div class="product-actions">
                        <!-- Champ pour sélectionner la quantité -->
                        <input type="number" class="product-quantity" value="1" min="1">
                        <!-- Bouton pour ajouter le produit au panier -->
                        <button class="add-to-cart" data-id="<?php echo $row['prodID']; ?>">
                            Ajouter au panier <i class="fa-solid fa-cart-shopping"></i>
                        </button>
                    </div>
                </div>
        <?php
            }
        } else {
            // Si aucun produit n'est disponible, affiche un message
            echo "<tr><td colspan='6'>Aucun produit disponible.</td></tr>";
        }
        // Ferme la requête et la connexion à la base de données
        $stmt->close();
        $conn->close();
        ?>
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

    <!-- Inclusion des fichiers JavaScript pour les fonctionnalités interactives -->
    <script src="../js/product.js"></script>
    <script src="../js/header.js"></script>
</body>
</html>