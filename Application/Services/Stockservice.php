<?php
class StockService {
    private $stockModel;

    public function __construct($stockModel) {
        $this->stockModel = $stockModel;
    }

    public function getAllProducts() {
        return $this->stockModel->getAllProducts();
    }

    public function ajouterProduit($nom, $description, $prix, $quantite_stock) {
        return $this->stockModel->ajouterProduit($nom, $description, $prix, $quantite_stock);
    }

    public function supprimerProduit($id_produit) {
        return $this->stockModel->supprimerProduit($id_produit);
    }

    public function isAdmin($user_id) {
        return $this->stockModel->isAdmin($user_id);
    }
}
?>
