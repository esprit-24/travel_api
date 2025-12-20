<?php

require_once __DIR__ . '/../models/UserModel.php';

class UserController
{
    private UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    // ============================================================
    // 📌 1) Endpoint : /users/create   (Méthode: POST)
    // ============================================================

    public function createUser(){
        // Récupération du JSON envoyé depuis Flutter
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        // Vérification JSON valide
        if (!$data) {
            return $this->response(400, ["error" => "Requête invalide (JSON non valide)."]);
        }

        // Vérification des champs requis
        $required = ["uid", "firstName", "lastName", "email", "phone"];
        foreach ($required as $field) {
            if (!isset($data[$field]) || empty(trim($data[$field]))) {
                return $this->response(400, ["error" => "Champ requis manquant : $field"]);
            }
        }

        // Vérifier doublon (Firebase déjà OK normalement)
        if ($this->userModel->userExists($data["uid"])) {
            return $this->response(409, ["error" => "Utilisateur déjà enregistré."]);
        }

        // Insertion en BDD
        $success = $this->userModel->createUser($data);

        if (!$success) {
            return $this->response(500, ["error" => "Erreur serveur lors de la création."]);
        }

        return $this->response(201, [
            "success" => true,
            "message" => "Utilisateur créé avec succès.",
            "user" => $data
        ]);
    }

    // ============================================================
    // 📌 2) Endpoint : /users/get?uid=xxx (Méthode: GET)
    // ============================================================

    public function getUserByUid(){
        if (!isset($_GET["uid"])) {
            return $this->response(400, ["error" => "Paramètre UID manquant."]);
        }

        $user = $this->userModel->getUserByUid($_GET["uid"]);

        if (!$user) {
            return $this->response(404, ["error" => "Utilisateur introuvable."]);
        }

        return $this->response(200, $user);
    }

    // ============================================================
    // 📌 3) Endpoint : /users/upload-photo (Méthode: POST)
    // ============================================================

    public function uploadPhoto()
    {
        // Vérifier UID
        if (!isset($_POST["uid"])) {
            return $this->response(400, ["error" => "UID manquant."]);
        }

        $uid = $_POST["uid"];

        // Vérifier si utilisateur existe
        if (!$this->userModel->userExists($uid)) {
            return $this->response(404, ["error" => "Utilisateur introuvable."]);
        }

        // Vérifier fichier
        if (!isset($_FILES["photo"])) {
            return $this->response(400, ["error" => "Fichier photo manquant."]);
        }

        $file = $_FILES["photo"];

        // Vérifier erreurs upload
        if ($file["error"] !== UPLOAD_ERR_OK) {
            return $this->response(400, ["error" => "Erreur lors du téléversement."]);
        }

        // Dossier de destination
        $uploadDir = __DIR__ . "/../../public/upload/";

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Génération du nom final
        $extension = pathinfo($file["name"], PATHINFO_EXTENSION);
        $fileName  = $uid . "_profile." . $extension;
        $filePath  = $uploadDir . $fileName;

        // Déplacement fichier
        move_uploaded_file($file["tmp_name"], $filePath);

        // ✅ Chemin RELATIF (indépendant de la plateforme)
        $relativePath = "upload/" . $fileName;

        // Mise à jour de la BDD avec le CHEMIN
        $this->userModel->updatePhotoUrl($uid, $relativePath);

        // Réponse API
        return $this->response(200, [
            "success" => true,
            "photoPath" => $relativePath
        ]);

    }

    // ============================================================
    // 📌 4) Endpoint : /users/update (Méthode: PUT ou POST)
    // ============================================================

    public function updateUser() {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        if (!$data) {
            return $this->response(400, ["error" => "JSON invalide."]);
        }

        if (!isset($data["uid"]) || empty(trim($data["uid"]))) {
            return $this->response(400, ["error" => "UID manquant."]);
        }

        $uid = $data["uid"];

        if (!$this->userModel->userExists($uid)) {
            return $this->response(404, ["error" => "Utilisateur introuvable."]);
        }

        $allowedFields = ["first_name", "last_name", "phone", "photo_url"];

        $updateData = ["uid" => $uid];

        foreach ($allowedFields as $field) {
            if (isset($data[$field])) {
                $updateData[$field] = $data[$field];
            }
        }

        $success = $this->userModel->updateUser($updateData);

        if (!$success) {
            return $this->response(500, ["error" => "Erreur lors de la mise à jour."]);
        }

        // 🔥 RENVOIE LE USER À JOUR APRÈS UPDATE
        $updatedUser = $this->userModel->getUserByUid($uid);

        return $this->response(200, [
            "success" => true,
            "message" => "Profil mis à jour avec succès.",
            "user" => $updatedUser
        ]);
    }

    // ============================================================
    // 🔧 Fonction helper pour simplifier les réponses
    // ============================================================

    private function response(int $statusCode, array $body){
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($body);
        exit;
    }

}
