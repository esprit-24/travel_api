<?php

require_once __DIR__ . '/../models/TripModel.php';

class TripController
{
    private TripModel $tripModel;

    public function __construct() {
        $this->tripModel = new TripModel();
    }

    // ============================================================
    // ➕ CREATE — POST /trips
    // ============================================================
    public function create() {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        if (!$data) {
            return $this->response(400, [
                'error' => 'JSON invalide.'
            ]);
        }

        $requiredFields = [
            'title',
            'country',
            'image',
            'price',
            'rating',
            'duration'
        ];

        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || $data[$field] === '') {
                return $this->response(400, [
                    'error' => "Champ requis manquant : $field"
                ]);
            }
        }

        // ✅ NORMALISATION DES TYPES (clé pour Flutter)
        $data['price']  = (float) $data['price'];
        $data['rating'] = (float) $data['rating'];

        $success = $this->tripModel->create($data);

        if (!$success) {
            return $this->response(500, [
                'error' => 'Erreur lors de la création du trip.'
            ]);
        }

        return $this->response(201, [
            'success' => true,
            'message' => 'Trip créé avec succès.'
        ]);
    }

    // ============================================================
    // 📥 READ — GET /trips
    // ============================================================
    public function index() {
        $trips = $this->tripModel->getAll();
        return $this->response(200, $trips);
    }

    // ============================================================
    // ✏️ UPDATE — PUT /trips/{id}
    // ============================================================
    public function update($id) {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        if (!$data) {
            return $this->response(400, ["error" => "JSON invalide"]);
        }

        // ✅ NORMALISATION DES TYPES
        if (isset($data['price'])) {
            $data['price'] = (float) $data['price'];
        }

        if (isset($data['rating'])) {
            $data['rating'] = (float) $data['rating'];
        }

        $success = $this->tripModel->update((int)$id, $data);

        if (!$success) {
            return $this->response(500, ["error" => "Échec de la mise à jour"]);
        }

        return $this->response(200, [
            "success" => true,
            "message" => "Trip mis à jour"
        ]);
    }

    // ============================================================
    // 🗑 DELETE — DELETE /trips/{id}
    // ============================================================
    public function delete($id) {
        $success = $this->tripModel->delete((int)$id);

        if (!$success) {
            return $this->response(500, ["error" => "Suppression échouée"]);
        }

        return $this->response(200, [
            "success" => true,
            "message" => "Trip supprimé"
        ]);
    }

    // ============================================================
    // 🔧 Réponse JSON standard
    // ============================================================
    private function response(int $statusCode, array $body) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($body);
        exit;
    }
}
