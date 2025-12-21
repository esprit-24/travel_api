<?php

require_once __DIR__ . '/../Database.php';

class TripModel
{
    private PDO $db;

    public function __construct(){
        $this->db = Database::getConnection();
    }

    /**
     * ➕ Créer un trip
     */
    public function create(array $data): bool {
        $sql = "
            INSERT INTO trips (
                title,
                country,
                image,
                description,
                price,
                rating,
                duration
            ) VALUES (
                :title,
                :country,
                :image,
                :description,
                :price,
                :rating,
                :duration
            )
        ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':title'       => $data['title'],
            ':country'     => $data['country'],
            ':image'       => $data['image'],
            ':description' => $data['description'] ?? null,
            ':price'       => (float) $data['price'],   // ✅ sécurité
            ':rating'      => (float) $data['rating'],  // ✅ sécurité
            ':duration'    => $data['duration'],
        ]);
    }

    /**
     * 📥 Récupérer tous les trips
     */
    public function getAll(): array {
        $sql = "SELECT * FROM trips ORDER BY created_at DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * ✏️ Mettre à jour un trip
     */
    public function update(int $id, array $data): bool {
        $sql = "
            UPDATE trips SET
                title = :title,
                country = :country,
                image = :image,
                description = :description,
                price = :price,
                rating = :rating,
                duration = :duration
            WHERE id = :id
        ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':id'          => $id,
            ':title'       => $data['title'],
            ':country'     => $data['country'],
            ':image'       => $data['image'],
            ':description' => $data['description'] ?? null,
            ':price'       => (float) $data['price'],   // ✅ sécurité
            ':rating'      => (float) $data['rating'],  // ✅ sécurité
            ':duration'    => $data['duration'],
        ]);
    }

    /**
     * 🗑 Supprimer un trip
     */
    public function delete(int $id): bool {
        $sql = "DELETE FROM trips WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}
