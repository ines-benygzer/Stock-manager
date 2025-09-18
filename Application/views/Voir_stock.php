<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


// Application/Views/stock.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


if (!empty($_SESSION['message'])): ?>
    <p class="message <?= $_SESSION['status'] ?? ''; ?>">
        <?= htmlspecialchars($_SESSION['message']); ?>
    </p>
    <?php unset($_SESSION['message'], $_SESSION['status']); ?>
<?php endif; ?>




<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion du Stock</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f8fb;
            margin: 0;
            padding: 0;
        }

        h2 {
            text-align: center;
            margin: 20px 0;
            color: #333;
        }

        .stock-list, .add-product {
            margin: 20px auto;
            padding: 15px;
            max-width: 800px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .stock-list ul {
            list-style-type: none;
            padding: 0;
        }

        .stock-list li {
            margin-bottom: 15px;
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        .stock-list li:last-child {
            border-bottom: none;
        }

        .stock-list strong {
            font-size: 18px;
            color: #007bff;
        }

        .stock-list form {
            display: inline;
        }

        .add-product label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .add-product input, .add-product textarea {
            width: 100%;
            margin-bottom: 10px;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .add-product input[type="submit"] {
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .add-product input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .message {
            text-align: center;
            margin: 15px;
            font-weight: bold;
        }

        .message.success {
            color: green;
        }

        .message.error {
            color: red;
        }
    </style>
</head>
<body>
    <h2>Bienvenue dans votre gestionnaire de stock</h2>

    <?php if (!empty($_SESSION['message'])): ?>
        <p class="message <?= $_SESSION['status'] ?? ''; ?>">
            <?= htmlspecialchars($_SESSION['message']); ?>
        </p>
        <?php unset($_SESSION['message'], $_SESSION['status']); ?>
    <?php endif; ?>

    <div class="stock-list">
        <h3>Liste des produits :</h3>
        <ul>
            <?php if (!empty($produits)): ?>
                <?php foreach ($produits as $produit): ?>
                    <li>
                        <strong><?= htmlspecialchars($produit['nom']); ?></strong><br>
                        Description: <?= htmlspecialchars($produit['description']); ?><br>
                        Prix: <?= number_format($produit['prix'], 2, ',', ' '); ?> DA<br>
                        Quantité en stock: <?= htmlspecialchars($produit['quantite_stock']); ?><br>
                        <?php if ($isAdmin): ?>
                            <form method="POST" action="/inesbenygzer/public/stock/supprimer">
                                <input type="hidden" name="id_produit" value="<?= htmlspecialchars($produit['id_produit']); ?>">
                                <input type="submit" value="Supprimer" onclick="return confirm('Êtes-vous sûr ?');">
                            </form>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Aucun produit disponible.</p>
            <?php endif; ?>
        </ul>
    </div>

    <?php if ($isAdmin): ?>
        <div class="add-product">
            <h3>Ajouter un produit :</h3>
            <form method="POST" action="/inesbenygzer/public/stock/ajouter">
                <label for="nom">Nom :</label>
                <input type="text" name="nom" required>
                <label for="description">Description :</label>
                <textarea name="description" rows="4" required></textarea>
                <label for="prix">Prix (DA) :</label>
                <input type="number" step="0.01" name="prix" required>
                <label for="quantite_stock">Quantité :</label>
                <input type="number" name="quantite_stock" required>
                <input type="submit" value="Ajouter">
            </form>
        </div>
    <?php endif; ?>
</body>
</html>
