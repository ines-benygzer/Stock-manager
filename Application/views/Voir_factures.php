
<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Visualiser les Factures</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f8ff;
            margin: 0; padding: 0; color: #333;
        }
        .container {
            max-width: 800px; margin: 50px auto;
            padding: 20px; background-color: #fff;
            border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        h2 { color: #4682B4; text-align: center; }
        form { display: flex; flex-direction: column; gap: 10px; margin-bottom: 20px; }
        label { font-weight: bold; color: #4682B4; }
        input[type="number"], input[type="submit"] {
            padding: 10px; font-size: 16px; border-radius: 5px; border: 1px solid #4682B4;
        }
        input[type="submit"] {
            background-color: #1e90ff; color: white; cursor: pointer; border: none;
        }
        input[type="submit"]:hover { background-color: #4682b4; }
        .facture { border: 1px solid #1e90ff; border-radius: 5px; padding: 15px; margin-bottom: 20px; background-color: #f8faff; }
        .facture h3 { color: #1e90ff; margin-top: 0; }
        .produits { margin-top: 10px; padding-left: 15px; }
        .produits li { list-style: none; padding: 5px 0; border-bottom: 1px solid #ccc; }
        .produits li:last-child { border-bottom: none; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Afficher les factures d'un client</h2>
        <form method="POST">
            <label for="id_client">ID Client :</label>
            <input type="number" name="id_client" id="id_client" required>
            <input type="submit" value="Voir les factures">
        </form>

        <?php if (!empty($factures)): ?>
            <?php foreach ($factures as $facture): ?>
                <div class="facture">
                    <h3>Facture n° <?= htmlspecialchars($facture['id_facture']) ?> - Date : <?= htmlspecialchars($facture['date_factures']) ?></h3>
                    <p>Total : <?= htmlspecialchars($facture['total']) ?> €</p>
                    <ul class="produits">
                        <?php foreach ($facture['produits'] as $produit): ?>
                            <li><?= htmlspecialchars($produit['nom']) ?> - <?= htmlspecialchars($produit['quantite']) ?> × <?= htmlspecialchars($produit['prix']) ?> €</li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endforeach; ?>
        <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
            <p>Aucune facture trouvée pour ce client.</p>
        <?php endif; ?>
    </div>
</body>
</html>
