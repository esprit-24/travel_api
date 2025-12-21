<?php

require_once __DIR__ . '/../src/controllers/TripController.php';

$controller = new TripController();

// --------------------------------------------------
// 🔹 Récupération URI sans query string
// --------------------------------------------------
$uri = strtok($_SERVER["REQUEST_URI"], '?');
$method = $_SERVER["REQUEST_METHOD"];

// --------------------------------------------------
// 🔹 Préfixe fixe (IDENTIQUE à users.php / destinations.php)
// ⚠️ Très important : DOIT correspondre à ApiConfig.baseUrl
// --------------------------------------------------
$basePath = "/esprit/travel_api/public/index.php";

// Route relative réelle
$route = str_replace($basePath, "", $uri);

// =======================
// CREATE
// POST /trips
// =======================
if ($route === "/trips" && $method === "POST") {
    $controller->create();
    exit;
}

// =======================
// READ (liste)
// GET /trips
// =======================
if ($route === "/trips" && $method === "GET") {
    $controller->index();
    exit;
}

// =======================
// UPDATE
// PUT /trips/{id}
// =======================
if (preg_match("#^/trips/(\d+)$#", $route, $matches) && $method === "PUT") {
    $id = (int)$matches[1];
    $controller->update($id);
    exit;
}

// =======================
// DELETE
// DELETE /trips/{id}
// =======================
if (preg_match("#^/trips/(\d+)$#", $route, $matches) && $method === "DELETE") {
    $id = (int)$matches[1];
    $controller->delete($id);
    exit;
}
