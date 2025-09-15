<?php
class Facture {
    private $conn;
    private $table_factures = "factures";
    private $table_facture_produit = "facture_produit";
    private $table_produits = "produits";
    private $table_clients = "clients";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Créer une facture
    public function createFacture($id_client, $date_factures, $total) {
        $sql = "INSERT INTO {$this->table_factures} (id_client, date_factures, total) 
                VALUES (:id_client, :date_factures, :total)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':id_client' => $id_client,
            ':date_factures' => $date_factures,
            ':total' => $total
        ]) ? $this->conn->lastInsertId() : false;
    }

    // Ajouter un produit à une facture
    public function addProduitToFacture($id_facture, $id_produit, $quantite) {
        $sql = "INSERT INTO {$this->table_facture_produit} (id_facture, id_produit, quantite)
                VALUES (:id_facture, :id_produit, :quantite)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':id_facture' => $id_facture,
            ':id_produit' => $id_produit,
            ':quantite' => $quantite
        ]);
    }

    // Vérifier si le client existe
    public function isClientExist($id_client) {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM {$this->table_clients} WHERE id = ?");
        $stmt->execute([$id_client]);
        return $stmt->fetchColumn() > 0;
    }

    // Récupérer les factures d’un client
    public function getFacturesByClient($id_client) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table_factures} WHERE id_client = ?");
        $stmt->execute([$id_client]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupérer les produits d’une facture
    public function getProduitsByFacture($id_facture) {
        $sql = "SELECT p.nom, p.prix, fp.quantite
                FROM {$this->table_facture_produit} fp
                JOIN {$this->table_produits} p ON p.id_produit = fp.id_produit
                WHERE fp.id_facture = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id_facture]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
