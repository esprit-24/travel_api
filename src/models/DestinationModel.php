<?php

require_once __DIR__ . '/../Database.php';

class DestinationModel
{
    private PDO $db;

    public function __construct(){
        $this->db = Database::getConnection();
    }

    /**
     * Créer une destination
     * Correspond EXACTEMENT au modèle Flutter Destination
     */
    public function create(array $data): bool {
        $sql = "
            INSERT INTO destinations (
                name,
                country,
                type,
                image,
                price,
                rating,
                reviews,
                description
            ) VALUES (
                :name,
                :country,
                :type,
                :image,
                :price,
                :rating,
                :reviews,
                :description
            )
        ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':name'        => $data['name'],
            ':country'     => $data['country'],
            ':type'        => $data['type'],
            ':image'       => $data['image'],
            ':price'       => $data['price'],
            ':rating'      => $data['rating'],
            ':reviews'     => $data['reviews'],
            ':description' => $data['description'] ?? null,
        ]);
    }

    /**
     * Récupérer toutes les destinations
     */
    public function getAll(): array {
        $sql = "SELECT * FROM destinations ORDER BY created_at DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update(int $id, array $data): bool {
        $sql = "UPDATE destinations SET
                    name = :name,
                    country = :country,
                    type = :type,
                    image = :image,
                    price = :price,
                    rating = :rating,
                    reviews = :reviews,
                    description = :description
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':id' => $id,
            ':name' => $data['name'],
            ':country' => $data['country'],
            ':type' => $data['type'],
            ':image' => $data['image'],
            ':price' => $data['price'],
            ':rating' => $data['rating'],
            ':reviews' => $data['reviews'],
            ':description' => $data['description'] ?? null,
        ]);
    }

    public function delete(int $id): bool {
        $sql = "DELETE FROM destinations WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

}
