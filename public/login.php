<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Se connecter</title>
    <style>
        /* Appliquer un fond d'image sur toute la page */
        body {
            margin: 0;
            padding: 0;
            background-image: url('canoe-kayak-gonflable-de-randonnee-23-places.jpg.avif');
            background-size: 100% 100%;
            background-position: center;
            background-repeat: no-repeat;
            font-family: Arial, sans-serif;
            color: #333;
        }

        /* Centrer le formulaire */
        .login-form {
            text-align: center;
            margin: 300px auto;
            background-color: rgba(255, 255, 255, 0.8);
            padding: 20px;
            width: 400px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        /* Marges pour les champs */
        .form-group {
            margin-bottom: 20px;
        }

        /* Style des labels */
        label {
            font-weight: bold;
            color: #4682B4; /* Bleu ciel foncé */
            display: block;
            margin-bottom: 8px;
        }

        /* Style des champs de saisie */
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        /* Style du bouton */
        button {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #87CEEB; /* Bleu ciel */
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            transition: background-color 0.3s ease;
        }

        /* Effet au survol du bouton */
        button:hover {
            background-color: #00BFFF; /* Bleu plus vif */
        }
    </style>
</head>
<body>
    <form class="login-form" action="login.php" method="POST">
        <div class="form-group">
            <label for="email">Email</label>
            <input type="text" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div class="form-group">
            <button type="submit" name="login">Se connecter</button>
        </div>
    </form>
</body>
</html>


<?php
session_start();

// Informations de connexion au serveur MySQL
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "inesbenygzer";

try {
    // Connexion au serveur MySQL
    $conn = new PDO("mysql:host=$servername", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Créer la base de données si elle n'existe pas
    $conn->exec("CREATE DATABASE IF NOT EXISTS $dbname");
    // Utiliser la base de données
    $conn->exec("USE $dbname");

    // Créer les tables si elles n'existent pas
    $sql = "
    CREATE TABLE IF NOT EXISTS clients (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nom VARCHAR(100),
        genre ENUM('femme', 'homme'),
        adresse VARCHAR(100),
        telephone VARCHAR(15),
        email VARCHAR(100)
    );

    CREATE TABLE IF NOT EXISTS Employee (
        id_employee INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        rôle VARCHAR(50) NOT NULL
    );

    CREATE TABLE IF NOT EXISTS factures (
        id_facture INT AUTO_INCREMENT PRIMARY KEY,
        id_client INT,
        date_factures DATE,
        total DECIMAL(10,2),
        FOREIGN KEY (id_client) REFERENCES clients(id) ON DELETE CASCADE
    );

    CREATE TABLE IF NOT EXISTS produits (
        id_produit INT AUTO_INCREMENT PRIMARY KEY,
        nom VARCHAR(100),
        description VARCHAR(255),
        prix DECIMAL(10,2),
        quantite_stock INT
    );

    CREATE TABLE IF NOT EXISTS facture_produit (
        id_facture INT,
        id_produit INT,
        quantite INT,
        PRIMARY KEY (id_facture, id_produit),
        FOREIGN KEY (id_facture) REFERENCES factures(id_facture) ON DELETE CASCADE,
        FOREIGN KEY (id_produit) REFERENCES produits(id_produit) ON DELETE CASCADE
    );
    ";
    $conn->exec($sql);

    // Insérer les données dans clients si elles n'existent pas encore
    $stmt = $conn->prepare("SELECT COUNT(*) FROM clients");
    $stmt->execute();
    if ($stmt->fetchColumn() == 0) {
        $sqlclients = "
        INSERT INTO clients (nom, genre, adresse, telephone, email) 
        VALUES
            ('Dounia gherbi', 'femme', 'centre ville', '0786854390', 'dounia@gmail.com'),
            ('Lilya gadiri', 'femme', 'centre ville', '0678987690', 'lilya@gmail.com'),
            ('Nada mahlia', 'femme', 'canastel', '0564321370', 'nada@gmail.com');
        ";
        $conn->exec($sqlclients);
    }

    // Insérer les données dans Employee si elles n'existent pas encore
    $stmt = $conn->prepare("SELECT COUNT(*) FROM Employee");
    $stmt->execute();
    if ($stmt->fetchColumn() == 0) {
        $sqlemployee = "
        INSERT INTO Employee (username, email, password, rôle)
        VALUES
            ('inesben', 'ines@gmail.com', '12345', 'Admin'),
            ('nadirben', 'nadir@gmail.com', '123456', 'employee');
        ";
        $conn->exec($sqlemployee);
    }

    // Insérer les données dans produits si elles n'existent pas encore
    $stmt = $conn->prepare("SELECT COUNT(*) FROM produits");
    $stmt->execute();
    if ($stmt->fetchColumn() == 0) {
        $sqlproduit = "
        INSERT INTO produits (nom, description, prix, quantite_stock)
        VALUES 
            ('Paddle 11 pieds', 'Paddle de 11 pieds, parfait pour les débutants et les intermédiaires.', 89500.00, 20),
            ('Kayak 3 places', 'Kayak robuste pouvant accueillir trois personnes, idéal pour les aventures en famille.', 120000.00, 10),
            ('Planche', 'Planche légère et pratique pour les sports nautiques.', 3950.00, 50),
            ('Palmes 36/37', 'Palmes adaptées à la taille 36/37, parfaites pour la plongée ou la natation.', 4950.00, 30),
            ('Palmes 40/41', 'Palmes adaptées à la taille 40/41, conçues pour les amateurs de sports aquatiques.', 4950.00, 25);
        ";
        $conn->exec($sqlproduit);
    }

    // Si le formulaire de login est soumis
    if (isset($_POST['login'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Vérifier l'utilisateur
        $requete = $conn->prepare("SELECT * FROM Employee WHERE email = :email AND password = :password");
        $requete->bindParam(':email', $email);
        $requete->bindParam(':password', $password);
        $requete->execute();

        $employee = $requete->fetch(PDO::FETCH_ASSOC);

        if ($employee) {
            // Authentification réussie
            $_SESSION['user_id'] = $employee['id_employee'];
            $_SESSION['username'] = $employee['username'];
            header("Location: Acceuil.php");
            exit;
        } else {
            echo "Email ou mot de passe invalide.";
        }
    }
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
?>

