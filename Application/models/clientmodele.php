<?php
class Client {
    private $db;
    public $id;
    public $nom;
    public $email;
    public $telephone;
    public $genre;
    public $adresse;

    public function __construct($db) {
        $this->db = $db;
    }

    // Create a new client
    public function create() {
        $sql = "INSERT INTO clients (nom, email, telephone, genre, adresse) 
                VALUES (:nom, :email, :telephone, :genre, :adresse)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':nom' => $this->nom,
            ':email' => $this->email,
            ':telephone' => $this->telephone,
            ':genre' => $this->genre,
            ':adresse' => $this->adresse
        ]);
    }

    // Delete client by ID
    public function deleteClient($client_id) {
        $sql = "DELETE FROM clients WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $client_id]);
    }

    // Update client's telephone
    public function updateClient($client_id, $telephone) {
    try {
        $sql = "UPDATE clients SET telephone = :telephone WHERE id = :id";
        $stmt = $this->db->prepare($sql);

        $success = $stmt->execute([
            ':telephone' => $telephone,
            ':id' => $client_id
        ]);

        if ($success && $stmt->rowCount() > 0) {
            return ['success' => true, 'message' => 'Client modifié avec succès.'];
        } else {
            return ['success' => false, 'message' => 'Aucune modification effectuée (client introuvable ou même numéro).'];
        }
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Erreur SQL : ' . $e->getMessage()];
    }
}


    public function isAdmin($employee_id) {
        $sql = "SELECT rôle FROM Employee WHERE id_employee = :id_employee LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id_employee' => $employee_id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return isset($row['rôle']) && $row['rôle'] === 'Admin';
    }

    // Get all clients
    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM clients");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get client by ID
    public function getById($client_id) {
        $stmt = $this->db->prepare("SELECT * FROM clients WHERE id = :id");
        $stmt->execute([':id' => $client_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>

