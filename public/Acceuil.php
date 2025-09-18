<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['username'])) {
    // Si l'utilisateur n'est pas connecté, rediriger vers la page de connexion
    header('Location: login.php');
    exit();
}
// Paramètres de connexion
$host = 'localhost';
$dbname = 'inesbenygzer';
$username = 'root';
$password = ''; // Remplace par ton mot de passe

try {
    // Connexion à la base de données avec PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Requête pour récupérer les produits
    $sql = "SELECT id_produit, nom, description, prix, quantite_stock FROM produits WHERE quantite_stock > 0";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    // Récupérer les résultats
    $produits = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - Gestion Stock</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        /* Image en haut de la page - Taille augmentée */
        .header-image {
            background-image: url('/Stock-manager/stand-up-paddle-lexique.jpg.avif'); /* Remplacez par l'image réelle */
            height: 350px;
            background-size: cover;
            background-position: center;
        }

        /* Barre de navigation */
        .navbar {
            position: absolute;
            top: 20px;
            right: 20px;
            background-color: rgba(135, 206, 235, 0.8);
            padding: 10px 20px;
            border-radius: 20px;
            opacity: 0;
            transform: translateX(50px);
            animation: fadeInUp 1s forwards;
            animation-delay: 1.5s;
        }

        .navbar a {
            color: #333;
            text-decoration: none;
            font-weight: bold;
        }

        /* Titre */
        .title {
            text-align: center;
            margin: 20px 0;
            color: #4a90e2;
            font-size: 36px;
            opacity: 0;
            transform: translateY(-50px);
            animation: fadeInUp 1s forwards;
            animation-delay: 0.5s;
        }

        .intro-text {
            text-align: center;
            margin: 10px auto 40px;
            width: 70%;
            font-size: 18px;
            line-height: 1.6;
            color: #333;
            opacity: 0;
            animation: fadeIn 1s forwards;
            animation-delay: 1s;
        }

        /* Galerie des liens */
        .link-gallery {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            margin-top: 50px;
        }

        .link-gallery a {
            text-align: center;
            text-decoration: none;
            color: #333;
            width: 200px;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            cursor: pointer;
            opacity: 0;
            transform: translateY(50px);
            animation: fadeInUp 1s forwards;
            animation-delay: 1.5s;
            transition: all 0.3s ease-in-out;
        }

        .link-gallery a.expanded {
            height: 280px; /* Hauteur ajustée après clic */
        }

        .link-gallery img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            transition: transform 0.3s;
        }

        .link-gallery img:hover {
            transform: scale(1.1);
        }

        .link-gallery span {
            display: block;
            padding: 10px;
            font-weight: bold;
            background: #f9f9f9;
            border-top: 1px solid #ddd;
            transition: all 0.3s ease;
        }

        .link-gallery a.expanded span {
            background: #4a90e2;
            color: #fff;
        }

        /* Animations Keyframes */
        @keyframes fadeInUp {
            0% {
                opacity: 0;
                transform: translateY(50px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            to {
                opacity: 1;
            }
        }
    </style>
</head>
<body>
    <!-- Image en haut -->
    <div class="header-image"></div>

    <!-- Barre de navigation -->
    <div class="navbar">
        <a href="logout.php">Déconnexion</a>
    </div>

    <!-- Titre -->
    <h1 class="title">Bienvenue sur AquaStock</h1>

    <!-- Paragraphe -->
    <p class="intro-text">
        Gérer vos clients, vos produits, vos factures et vos stocks n'a jamais été aussi simple. 
        Naviguez à travers les différentes sections pour effectuer les tâches rapidement et efficacement.
    </p>

    <!-- Galerie des liens -->
    <div class="link-gallery">
        <a href=/Stock-manager/public/clients/ajouter>
            <img src="/Stock-manager/DALL·E 2024-12-15 23.18.41 - A modern and minimalistic design representing the concept of 'Ajouter un client' (Add a client). The image should feature a user profile icon with a '.webp " alt="Ajouter Client">
            <span>Ajouter un Client</span>
        </a>
        <a href=/Stock-manager/public/clients/supprimer>
            <img src="/Stock-manager/DALL·E 2024-12-15 23.18.39 - A modern and minimalistic design representing the concept of 'Supprimer un client' (Remove a client). The image should feature a user profile icon wit.webp " alt="Supprimer Client">
            <span>Supprimer un Client</span>
        </a>
        <a href=/Stock-manager/public/clients/modifier>
            <img src="/Stock-manager/DALL·E 2024-12-15 23.18.32 - A modern and minimalistic design representing the concept of 'Modification d'un client' (Modify a client). The image should feature a user profile ico.webp" alt="Modifier Client">
            <span>Modifier un Client</span>
        </a>
        <a href=/Stock-manager/public/factures>
            <img src="/Stock-manager/DALL·E 2024-12-15 23.42.03 - A clean and modern graphic design representing 'Voir facture' for a web gallery. The image features an icon of a magnifying glass over a document, sur.webp" alt="Voir Factures">
            <span>Voir les Factures</span>
        </a>
        <a href=/Stock-manager/public/factures/ajouter>
            <img src="/Stock-manager/DALL·E 2024-12-15 23.42.01 - A clean and modern graphic design representing 'Ajouter une facture' for a web gallery. The image features an icon of a document with a plus sign, sur.webp" alt="Ajouter Facture">
            <span>Ajouter une Facture</span>
        </a>
        <a href=/Stock-manager/public/stock>
            <img src="/Stock-manager/DALL·E 2024-12-15 23.42.29 - A clean and modern graphic design representing 'Voir les stocks' for a web gallery. The image features an icon of stacked boxes or a warehouse with a .webp" alt="Voir Stock">
            <span>Voir les Stocks</span>
        </a>

    </div>

    <script>
        // Ajouter un événement pour l'expansion des boîtes au clic
        document.querySelectorAll('.link-gallery a').forEach(function (item) {
            item.addEventListener('click', function () {
                // Activer/désactiver la classe "expanded" pour l'élément cliqué
                this.classList.toggle('expanded');
            });
        });
    </script>
</body>
</html>
