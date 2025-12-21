<?php

require_once __DIR__ . '/../models/DestinationModel.php';

class DestinationController
{
    private DestinationModel $destinationModel;

    public function __construct() {
        $this->destinationModel = new DestinationModel();
    }

    // ============================================================
    // 📌 Endpoint : /destinations/create  (Méthode: POST)
    // ============================================================

    public function create() {
        // 1️⃣ Récupération du JSON
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        if (!$data) {
            return $this->response(400, [
                'error' => 'JSON invalide.'
            ]);
        }

        // 2️⃣ Champs obligatoires (alignés avec Flutter)
        $requiredFields = [
            'name',
            'country',
            'type',
            'image',
            'price',
            'rating',
            'reviews'
        ];

        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || $data[$field] === '') {
                return $this->response(400, [
                    'error' => "Champ requis manquant : $field"
                ]);
            }
        }

        // 3️⃣ Insertion en base
        $success = $this->destinationModel->create($data);

        if (!$success) {
            return $this->response(500, [
                'error' => 'Erreur lors de la création de la destination.'
            ]);
        }

        // 4️⃣ Succès
        return $this->response(201, [
            'success' => true,
            'message' => 'Destination créée avec succès.'
        ]);
    }

    // ============================================================
    // 📌 Endpoint : /destinations (Méthode: GET)
    // ============================================================

    public function index(){
        $destinations = $this->destinationModel->getAll();

        return $this->response(200, $destinations);
    }

    public function update($id) {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        if (!$data) {
            return $this->response(400, ["error" => "JSON invalide"]);
        }

        $success = $this->destinationModel->update((int)$id, $data);

        if (!$success) {
            return $this->response(500, ["error" => "Échec de la mise à jour"]);
        }

        return $this->response(200, [
            "success" => true,
            "message" => "Destination mise à jour"
        ]);
    }

    public function delete($id) {
        $success = $this->destinationModel->delete((int)$id);

        if (!$success) {
            return $this->response(500, ["error" => "Suppression échouée"]);
        }

        return $this->response(200, [
            "success" => true,
            "message" => "Destination supprimée"
        ]);
    }


    // ============================================================
    // 🔧 Helper réponse JSON
    // ============================================================

    private function response(int $statusCode, array $body) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($body);
        exit;
    }
}
