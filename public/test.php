<?php

require_once __DIR__ . '/../src/Database.php';

try {
    $db = Database::getConnection();
    echo "Connexion OK à PostgreSQL !";
} catch (Exception $e) {
    echo $e->getMessage();
}
