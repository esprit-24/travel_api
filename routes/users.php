<?php

require_once __DIR__ . '/../src/controllers/UserController.php';

$controller = new UserController();

// Récupération de l’URI SANS query string
$uri = strtok($_SERVER["REQUEST_URI"], '?');
$method = $_SERVER["REQUEST_METHOD"];

// On enlève le préfixe du projet automatiquement
$basePath = "/esprit/travel_api/public/index.php";  // Ajustez selon votre configuration

// On crée la route relative
$route = str_replace($basePath, "", $uri);

// -------------------------------
// 🔥 ROUTES
// -------------------------------

// /users/create   (POST)
if ($route === "/users/create" && $method === "POST") {
    $controller->createUser();
    exit;
}

// /users/get   (GET)
if ($route === "/users/get" && $method === "GET") {
    $controller->getUserByUid();
    exit;
}

// /users/upload-photo   (POST)
if ($route === "/users/upload-photo" && $method === "POST") {
    $controller->uploadPhoto();
    exit;
}


// Si aucune route ne correspond
http_response_code(404);
echo json_encode(["error" => "Route inconnue: $route"]);
exit;
