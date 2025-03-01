<?php
// Démarrage de la session pour gérer la connexion utilisateur
session_start();

// Vérification de la connexion et du rôle utilisateur
if (!isset($_SESSION['login']) || $_SESSION['user_role'] !== 'user') {
    header('location: ../html/index.html');
    exit();
}

// Inclusion du fichier de connexion à la base de données
include("db_connect.php");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ForYou | Produits</title>
    <!-- Styles CSS -->
    <link rel="stylesheet" href="../css/body.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/product.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="stylesheet" href="../css/logo.css">
    <!-- Icônes et jQuery -->
    <script src="https://kit.fontawesome.com/a6c1691723.js" crossorigin="anonymous"></script>
    <script src="../jquery-3.7.1.js"></script>
</head>
<body data-id="<?php echo $_SESSION['userID']; ?>">
    <header>
        <div class="container">
            <div class="navbar">
                <div class="logo">
                    <p class="logo">For<span class="highlight">You</span><span id="sp">.</span></p>
                </div>
                <nav>
                    <ul>
                        <li><a href="../html/index.html">Accueil</a></li>
                        <li><a href="#product">Produits</a></li>
                        <li><a href="../html/index.html#contact">Contact</a></li>
                        <li>
                            <a href="panier.php" class="btn-cart">
                                <i class="fa-solid fa-cart-shopping"></i>
                                <span class="nbr">(0)</span> <!-- Compteur du panier -->
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

    <section id="product">
        <div class="product-list">
            <?php
            // Requête pour récupérer les produits
            $stmt = $conn->prepare("SELECT * FROM product");
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $img = htmlspecialchars($row['img']);
                    $prodID = (int) $row['prodID'];
                    $prodName = htmlspecialchars($row['prodName']);
                    $prodDesc = htmlspecialchars($row['prodDesc']);
                    $prodPrice = number_format((float) $row['prodPrice'], 2, ',', ' '); // Formatage du prix
            ?>
                    <div class="product" data-id="<?php echo $prodID; ?>">
                        <div class="product-image">
                            <img src="<?php echo $img; ?>" alt="Image de l'article">
                        </div>
                        <h2 class="product-name"><?php echo $prodName; ?></h2>
                        <div class="product-description"><?php echo $prodDesc; ?></div>
                        <div class="product-price"><?php echo $prodPrice; ?> <span>DA</span></div>
                        <div class="product-actions">
                            <button class="add-to-cart" data-id="<?php echo $prodID; ?>">
                                Ajouter au panier 
                                <i class="fa-solid fa-cart-shopping"></i>
                            </button>
                        </div>
                    </div>
            <?php
                }
            } else {
                echo "<p>Aucun produit disponible.</p>";
            }
            $stmt->close();
            $conn->close();
            ?>
        </div>
    </section>

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

    <!-- Scripts JavaScript -->
    <script src="../js/product.js"></script>
    <script src="../js/header.js"></script>
</body>
</html>
