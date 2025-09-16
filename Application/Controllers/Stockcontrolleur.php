<?php
require_once __DIR__ . '/../models/stockmodele.php';


require_once  __DIR__ .'/../Services/Stockservice.php' ;

class StockController {
    private $db;
    private $stockService;

    public function __construct($host, $dbname, $username, $password) {
        try {
            $this->db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stockModel = new Stock($this->db);
            $this->stockService = new StockService($stockModel);
        } catch (PDOException $e) {
            die("Erreur de connexion : " . $e->getMessage());
        }
    }

    // Nouvelle méthode pour afficher le stock
    public function index() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Récupérer la liste des produits
        $produits = $this->stockService->getAllProducts();

        // Vérifier si l'utilisateur est admin
        $isAdmin = isset($_SESSION['user_id']) && $this->stockService->isAdmin($_SESSION['user_id']);

        // Charger la vue
        require __DIR__ . '/../views/Voir_stock.php';
    }

    public function ajouterProduit($nom, $description, $prix, $quantite_stock) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            return ["error" => "Vous devez être connecté pour ajouter un produit."];
        }

        if (!$this->stockService->isAdmin($_SESSION['user_id'])) {
            return ["error" => "Accès interdit. Seuls les administrateurs peuvent ajouter des produits."];
        }

        $success = $this->stockService->ajouterProduit($nom, $description, $prix, $quantite_stock);
        return $success ? ["success" => "Produit ajouté avec succès !"] : ["error" => "Erreur lors de l'ajout du produit."];
    }

    public function supprimerProduit($id_produit) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            return ["error" => "Vous devez être connecté pour supprimer un produit."];
        }

        if (!$this->stockService->isAdmin($_SESSION['user_id'])) {
            return ["error" => "Accès interdit. Seuls les administrateurs peuvent supprimer des produits."];
        }

        $success = $this->stockService->supprimerProduit($id_produit);
        return $success ? ["success" => "Produit supprimé avec succès !"] : ["error" => "Erreur lors de la suppression du produit."];
    }
}

