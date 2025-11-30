<?php

require_once __DIR__ . '/../src/controllers/UserController.php';

$controller = new UserController();

// --- CORS / Flutter Web FIX ---
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Récupération URI sans query string
$uri = strtok($_SERVER["REQUEST_URI"], '?');
$method = $_SERVER["REQUEST_METHOD"];

// Préfixe fixe
$basePath = "/esprit/travel_api/public/index.php";

// Route relative
$route = str_replace($basePath, "", $uri);

// -------------------------------
// 🔥 ROUTES
// -------------------------------

if ($route === "/users/create" && $method === "POST") {
    $controller->createUser();
    exit;
}

if ($route === "/users/get" && $method === "GET") {
    $controller->getUserByUid();
    exit;
}

if ($route === "/users/upload-photo" && $method === "POST") {
    $controller->uploadPhoto();
    exit;
}

if ($route === "/users/update" && ($method === "PUT" || $method === "POST")) {
    $controller->updateUser();
    exit;
}

// 404
http_response_code(404);
echo json_encode(["error" => "Route inconnue: $route"]);
exit;
