<?php
// routes/web.php
session_start();
require_once __DIR__ . '/../Application/Controllers/Stockcontrolleur.php';
require_once __DIR__ . '/../Application/Controllers/Clientcontrolleur.php';
require_once __DIR__ . '/../Application/Controllers/Facturecontrolleur.php';

$router = new AltoRouter();
$router->setBasePath('/inesbenygzer/public'); // Adjust if your XAMPP URL differs

// Stock routes
$router->map('GET', '/stock', function () {
    $controller = new StockController('localhost', 'inesbenygzer', 'root', '');
    $controller->index();
});

$router->map('POST', '/stock/ajouter', function () {
    $controller = new StockController('localhost', 'inesbenygzer', 'root', '');
    $result= $controller->ajouterProduit($_POST['nom'], $_POST['description'], $_POST['prix'], $_POST['quantite_stock']);
    

    $_SESSION['message'] = $result['success'] ?? $result['error'];
    $_SESSION['status']  = isset($result['success']) ? 'success' : 'error';

    // redirection pour éviter le re-post
    header("Location: /inesbenygzer/public/stock");
    exit;
});

$router->map('POST', '/stock/supprimer', function () {
    $controller = new StockController('localhost', 'inesbenygzer', 'root', '');
    $controller->supprimerProduit($_POST['id_produit']);
    $result = $controller->supprimerProduit($_POST['id_produit']);

    $_SESSION['message'] = $result['success'] ?? $result['error'];
    $_SESSION['status']  = isset($result['success']) ? 'success' : 'error';

    header("Location: /inesbenygzer/public/stock");
    exit;
});

// Client routes
// Supprimer client
$router->map('GET|POST', '/clients/supprimer', function () {
    $controller = new ClientController();

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
        $id_client = $_POST['id_client'];

        $result = $controller->deleteClient($_SESSION['user_id'], $id_client);

        $_SESSION['message'] = $result['success'] ? 'Client supprimé avec succès.' : $result['message'];
        $_SESSION['status'] = $result['success'] ? 'success' : 'error';

        header("Location: /inesbenygzer/public/clients/supprimer");
        exit();
    }

    $controller->supprimerForm();
});

$router->map('GET', '/clients', function () {
    $controller = new ClientController('localhost', 'inesbenygzer', 'root', '');
    $controller->index();
});
$router->map('GET|POST', '/clients/modifier', function() {
    $controller = new ClientController();

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
        $id_client = $_POST['id_client'];
        $telephone = $_POST['telephone'];

        $result = $controller->updateClient($_SESSION['user_id'], $id_client, ['telephone' => $telephone]);

        $_SESSION['message'] = $result['message'];
        $_SESSION['status'] = $result['success'] ? 'success' : 'error';

        header("Location: /inesbenygzer/public/clients/modifier"); // Redirect to avoid resubmission
        exit();
    }

    $controller->modifierForm();
});

$router->map('GET', '/clients/ajouter', function() {
    $controller = new ClientController();
    $controller->ajouterForm();
});
// Handle form submission
$router->map('POST', '/clients/ajouter', function() {
    $controller = new ClientController();

    if (isset($_POST['create'])) {
        $data = [
            'nom' => $_POST['nom'],
            'email' => $_POST['email'],
            'telephone' => $_POST['telephone'],
            'genre' => $_POST['genre'],
            'adresse' => $_POST['adresse']
        ];

        $result = $controller->createClient($data);

        // Store message in session to show on page
        $_SESSION['message'] = $result['message'];
        $_SESSION['status'] = $result['success'] ? 'success' : 'error';

        // Redirect to avoid resubmission
        header("Location: /inesbenygzer/public/clients/ajouter");
        exit();
    }

    $controller->ajouterForm();
});


// Facture routes
// Page de factures avec formulaire (GET) + soumission (POST)
$router->map('GET|POST', '/factures', function () {
    $controller = new FactureController();

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['id_client'])) {
        $controller->afficherFactures($_POST['id_client']);
    } else {
        $controller->afficherFactures(null); // juste le formulaire
    }
});

// Affichage direct via URL /factures/3
$router->map('GET', '/factures/[i:id_client]', function ($id_client) {
    $controller = new FactureController();
    $controller->afficherFactures($id_client);
});
// Afficher le formulaire d'ajout de facture
// Afficher formulaire d'ajout de facture
$router->map('GET', '/factures/ajouter', function() {
    $controller = new FactureController();
    $controller->ajouterForm();
});

// Traiter l'ajout de facture
$router->map('POST', '/factures/ajouter', function() {
    $controller = new FactureController();

    $id_client = (int)$_POST['id_client'];
    $date_factures = $_POST['date_factures'];
    $products = [];

    if (!empty($_POST['product_id']) && !empty($_POST['quantity'])) {
        for ($i = 0; $i < count($_POST['product_id']); $i++) {
            if (!empty($_POST['product_id'][$i]) && !empty($_POST['quantity'][$i])) {
                $products[] = [
                    'id_produit' => (int)$_POST['product_id'][$i],
                    'quantite'   => (int)$_POST['quantity'][$i]
                ];
            }
        }
    }


    // Calcul du total
    $total = 0;
    $pdo = Database::getConnection();
    foreach ($products as $p) {
        $stmt = $pdo->prepare("SELECT prix FROM produits WHERE id_produit = ?");
        $stmt->execute([$p['id_produit']]);
        $prix = (float)$stmt->fetchColumn();
        $total += $prix * $p['quantite'];
    }

    $result = $controller->createFacture($id_client, $date_factures, $total, $products);

    $_SESSION['message'] = $result['message'];
    $_SESSION['status']  = $result['success'] ? 'success' : 'error';

    header("Location: /inesbenygzer/public/factures/ajouter");
    exit;
});


