<?php

// =====================================================
// 🌍 CORS — Autoriser Flutter Web / Android
// =====================================================
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Routes
require_once __DIR__ . '/../routes/users.php';
require_once __DIR__ . '/../routes/destinations.php';
require_once __DIR__ . '/../routes/trips.php';

// 🔴 SI AUCUNE ROUTE N’A MATCHÉ
http_response_code(404);
echo json_encode(["error" => "Route inconnue"]);
exit;
