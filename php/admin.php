<?php 
    session_start();
    if (!isset($_SESSION['login']) || $_SESSION['user_role'] !== 'admin') {
        header('location: ../html/index.html');
        exit();
    }

    include("db_connect.php");
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/body.css">
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="../css/logo.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/panier.css">
    <link rel="stylesheet" href="../css/admin-responsive.css">
    <script src="https://kit.fontawesome.com/a6c1691723.js" crossorigin="anonymous"></script>
    <script src="../jquery-3.7.1.js"></script>
    <title>ForYou | Admin</title>
</head>

<body>
    <header id="head">
        <div class="navbar">
            <div class="logo">
                <p class="logo">For<span class="highlight">You</span><span id="sp">.</span></p>
            </div>
            <nav class="main-nav">
                <ul>
                    <li><a href="#panier">Articles</a></li>
                    <li class="exit">
                        <a href="logout.php">Déconnexion <i class="fa-solid fa-right-from-bracket"></i></a>
                    </li>
                </ul>
                <div class="icon-container">
                    <p class="icon open"><i class="fas fa-bars"></i></p>
                </div>
            </nav>
        </div>
    </header>

    <section id="product-add">
        <div class="container">
            <h2>Ajouter un nouveau produit:</h2>
            <form action="../php/addProduct.php" enctype="multipart/form-data" method="post" class="add-form" id="add-form">
                <label for="prodName">Nom du produit:</label>
                <input type="text" name="prodName" placeholder="Nom du produit" required>

                <label for="product-description">Description du produit:</label>
                <textarea name="product-description" id="product-description" placeholder="Description du produit"></textarea>

                <label for="product-price">Prix du produit:</label>
                <input type="text" name="product-price" placeholder="Prix du produit" required>

                <label for="product-image">Image du produit:</label>
                <input type="file" name="product-image" accept="image/*">

                <button type="submit" class="btn btn2">Valider</button>
            </form>
        </div>
    </section>

    <section id="panier">
        <h1>Les éléments ajoutés :</h1>
        <table id="product-table">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Nom</th>
                    <th>Description</th>
                    <th>Prix</th>
                    <th>Quantité</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php
                $result = $conn->query("SELECT * FROM product");

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr data-id='" . $row['prodID'] . "'>
                                <td><img src='" . $row['img'] . "' alt='" . $row['prodName'] . "'></td>
                                <td>" . $row['prodName'] . "</td>
                                <td>" . $row['prodDesc'] . "</td>
                                <td>" . $row['prodPrice'] . " DA</td>
                                <td><input type='number' class='quantity' value='1' min='1'></td>
                                <td>
                                    <button class='update-btn'>Mettre à jour</button>
                                    <button class='delete-btn'>Supprimer</button>
                                </td>
                            </tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>Aucun produit trouvé</td></tr>";
                }

                $conn->close();
            ?>
            </tbody>
        </table>
    </section>

    <script src="../js/admin.js"></script>
    <script src="../js/responsive.js"></script>
    <script src="../js/header.js"></script>
</body>

</html>
