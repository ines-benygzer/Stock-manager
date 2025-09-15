<?php
class Stock {
    private $conn;
    private $table = "produits";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Vérifier si un utilisateur est admin
    public function isAdmin($user_id) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        // Requête pour vérifier le rôle de l'utilisateur
        $query = "SELECT rôle FROM Employee WHERE id_employee = :id_employee LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_employee', $user_id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Retourne true si le rôle est 'Admin', sinon false
        return isset($result['rôle']) && trim($result['rôle']) === 'Admin';
    }

    // Ajouter un produit
    public function ajouterProduit($nom, $description, $prix, $quantite_stock) {
        // Préparer la requête d'insertion
        $query = "INSERT INTO " . $this->table . " (nom, description, prix, quantite_stock) 
                  VALUES (:nom, :description, :prix, :quantite_stock)";
        $stmt = $this->conn->prepare($query);

        // Lier les paramètres
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':prix', $prix);
        $stmt->bindParam(':quantite_stock', $quantite_stock);

        // Exécuter la requête et retourner le résultat
        return $stmt->execute();
    }

    // Récupérer tous les produits
    public function getAllProducts() {
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        // Retourne tous les produits sous forme de tableau associatif
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Supprimer un produit
    public function supprimerProduit($id_produit) {
        $query = "DELETE FROM " . $this->table . " WHERE id_produit = :id_produit";
        $stmt = $this->conn->prepare($query);

        // Lier le paramètre ID du produit
        $stmt->bindParam(':id_produit', $id_produit);

        // Exécuter la requête et retourner le résultat
        return $stmt->execute();
    }
}
?>

