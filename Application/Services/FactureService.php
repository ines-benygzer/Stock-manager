<?php
// chemins cohérents avec ton projet (mêmes conventions que ClientService)
require_once __DIR__ . '/../models/facturemodele.php';
require_once __DIR__ . '/../../config/Database.php';

class FactureService {
    private $factureModel;

    public function __construct() {
        $db = Database::getConnection(); // Doit retourner une instance PDO
        $this->factureModel = new Facture($db);
    }

    public function getFacturesByClient(int $id_client): array {
        return $this->factureModel->getFacturesByClient($id_client);
    }

    public function getProduitsByFacture(int $id_facture): array {
        return $this->factureModel->getProduitsByFacture($id_facture);
    }

    public function createFacture(int $id_client, string $date_factures, float $total, array $produits): array {
        if (!$this->factureModel->isClientExist($id_client)) {
            return ['success' => false, 'message' => 'Client introuvable'];
        }

        $facture_id = $this->factureModel->createFacture($id_client, $date_factures, $total);
        if (!$facture_id) {
            return ['success' => false, 'message' => 'Échec de création de la facture'];
        }

        foreach ($produits as $p) {
            if (!isset($p['id_produit'], $p['quantite'])) continue;
            $this->factureModel->addProduitToFacture((int)$facture_id, (int)$p['id_produit'], (int)$p['quantite']);
        }

        return ['success' => true, 'message' => 'Facture créée avec succès', 'id_facture' => (int)$facture_id];
    }


}
