<?php
require_once BASE_PATH . '/Services/FactureService.php';

class FactureController {
    private $service;

    public function __construct() {
        $this->service = new FactureService();
    }

    /**
     * Afficher les factures pour un client donné
     */
    public function afficherFactures($id_client = null) {
        $factures = [];

        if (!empty($id_client)) {
            $factures = $this->service->getFacturesByClient((int)$id_client);
            foreach ($factures as &$f) {
                $f['produits'] = $this->service->getProduitsByFacture((int)$f['id_facture']);
            }
        }

        require BASE_PATH . '/Views/Voir_factures.php';
    }

    /**
     * Afficher le formulaire d’ajout
     */
    public function ajouterForm() {
    // Connexion à la base
    $pdo = Database::getConnection();

    // Récupération des produits
    $stmt = $pdo->query("SELECT id_produit, nom, prix FROM produits ORDER BY nom ASC");
    $productsList = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Message et statut par défaut
    $message = null;
    $status = null;

    // On charge la vue avec les données nécessaires
    require BASE_PATH . '/Views/ajouter_facture.php';
}


    /**
     * Créer une facture avec produits
     */
    public function createFacture(int $id_client, string $date_factures, float $total, array $produits) {
    $result = $this->service->createFacture($id_client, $date_factures, $total, $produits);

    session_start();
    $_SESSION['message'] = $result['message'];
    $_SESSION['status'] = $result['success'] ? 'success' : 'error';

    header('Location: /inesbenygzer/public/factures/ajouter');
    exit;
}

}
