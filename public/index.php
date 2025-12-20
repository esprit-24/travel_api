<?php

// =====================================================
// 🌍 CORS — Autoriser Flutter Web / Android
// =====================================================
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// 🔁 Réponse immédiate aux requêtes OPTIONS (preflight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// 🔽 Routing
require_once __DIR__ . '/../routes/users.php';
