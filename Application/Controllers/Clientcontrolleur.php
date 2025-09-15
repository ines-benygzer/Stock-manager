<?php
require_once BASE_PATH . '/Services/ClientService.php';


class ClientController {
    private $clientService;

    public function __construct() {
        $this->clientService = new ClientService();
    }

    // --- Ajouter ---
    public function ajouterForm() {
        require BASE_PATH . '/Views/Ajouter_client.php';
    }

    public function createClient($data) {
        $result = $this->clientService->create($data);
        echo $result ? "Client ajouté avec succès !" : "Erreur lors de l'ajout du client.";
    }
    //testing github 
    // --- Supprimer ---
    public function supprimerForm($client_id = null) {
    if ($client_id) {
        $client = $this->clientService->getClient($client_id);
    }
    require BASE_PATH . '/Views/Supprimer_client.php';
}


    public function deleteClient($employee_id, $client_id) {
        $this->clientService->delete($employee_id, $client_id);
        echo "Client supprimé avec succès.";
    }

    // --- Modifier ---
    
    public function modifierForm($client_id = null, $message = '', $status = '') {
    require BASE_PATH . '/Views/modifier_un_client.php';
}

 public function updateClient($employee_id, $client_id, $data) {
    if (!isset($data['telephone'])) {
        return ['success' => false, 'message' => 'Numéro de téléphone manquant.'];
    }

    $result = $this->clientService->update($employee_id, $client_id, $data['telephone']);
    return $result;
}


}
?>
