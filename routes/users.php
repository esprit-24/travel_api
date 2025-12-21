<?php

require_once __DIR__ . '/../src/controllers/UserController.php';

$controller = new UserController();

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

if ($route === "/users" && $method === "GET") {
    $controller->getAllUsers();
    exit;
}
