<?php

class Database {

    private static ?PDO $connection = null;

    // Lecture du fichier .env
    private static function loadEnv() {
        $envPath = __DIR__ . '/../.env';

        if (!file_exists($envPath)) {
            throw new Exception(".env file not found at $envPath");
        }

        $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {

            // Ignorer commentaires
            if (str_starts_with(trim($line), '#')) {
                continue;
            }

            [$key, $value] = explode("=", $line, 2);

            $key   = trim($key);
            $value = trim($value);

            if (!isset($_ENV[$key])) {
                $_ENV[$key] = $value;
            }
        }
    }

    // Connexion à la DB PostgreSQL via PDO
    public static function getConnection(): PDO
    {
        if (self::$connection === null) {

            self::loadEnv();

            $host = $_ENV['DB_HOST'];
            $port = $_ENV['DB_PORT'];
            $dbname = $_ENV['DB_NAME'];
            $user = $_ENV['DB_USER'];
            $pass = $_ENV['DB_PASSWORD'];

            $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";

            try {
                self::$connection = new PDO($dsn, $user, $pass, [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]);
            } catch (PDOException $e) {
                die("Database connection failed: " . $e->getMessage());
            }
        }

        return self::$connection;
    }
}
