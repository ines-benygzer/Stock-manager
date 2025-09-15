<?php
require_once  __DIR__ . '/../models/clientmodele.php';
require_once __DIR__ . '/../../config/Database.php';

class ClientService {
    private $client;

    public function __construct() {
        $db = Database::getConnection();
        $this->client = new Client($db);
    }

    public function create($data) {
        $this->client->email = $data['email'];
        $this->client->telephone = $data['telephone'];
        $this->client->genre = $data['genre'];
        $this->client->nom = $data['nom'];
        $this->client->adresse = $data['adresse'];

        return $this->client->create();
    }

    public function delete($employee_id, $client_id) {
        return $this->client->isAdmin($employee_id)
            ? $this->client->deleteClient($client_id)
            : ['success' => false, 'message' => 'Unauthorized'];
    }

    public function update($employee_id, $client_id, $telephone) {
    return $this->client->isAdmin($employee_id)
         ? $this->client->updateClient($client_id, $telephone)
        : ['success' => false, 'message' => 'Action non autorisée (Admin requis)'];
}


    public function getClient($client_id = null) {
        return $client_id 
            ? $this->client->getById($client_id) 
            : $this->client->getAll();
    }
}
?>

