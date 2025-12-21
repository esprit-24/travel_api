<?php

require_once __DIR__ . '/../src/controllers/DestinationController.php';

$controller = new DestinationController();

// --------------------------------------------------
// 🔹 Récupération URI sans query string
// --------------------------------------------------
$uri = strtok($_SERVER["REQUEST_URI"], '?');
$method = $_SERVER["REQUEST_METHOD"];

// --------------------------------------------------
// 🔹 Préfixe fixe (IDENTIQUE à users.php)
// ⚠️ Très important : DOIT correspondre à ApiConfig.baseUrl
// --------------------------------------------------
$basePath = "/esprit/travel_api/public/index.php";

// Route relative réelle
$route = str_replace($basePath, "", $uri);

// --------------------------------------------------
// 🔥 ROUTES DESTINATIONS (CRUD)
// --------------------------------------------------

// =======================
// CREATE
// POST /destinations
// =======================
if ($route === "/destinations" && $method === "POST") {
    $controller->create();
    exit;
}

// =======================
// READ (liste)
// GET /destinations
// =======================
if ($route === "/destinations" && $method === "GET") {
    $controller->index();
    exit;
}

// =======================
// UPDATE
// PUT /destinations/{id}
// =======================
if (preg_match("#^/destinations/(\d+)$#", $route, $matches) && $method === "PUT") {
    $id = (int)$matches[1];
    $controller->update($id);
    exit;
}

// =======================
// DELETE
// DELETE /destinations/{id}
// =======================
if (preg_match("#^/destinations/(\d+)$#", $route, $matches) && $method === "DELETE") {
    $id = (int)$matches[1];
    $controller->delete($id);
    exit;
}
