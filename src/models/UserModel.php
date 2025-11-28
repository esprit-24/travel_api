<?php

require_once __DIR__ . '/../Database.php';

class UserModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function createUser(array $data): bool
    {
        $sql = "INSERT INTO users (uid, first_name, last_name, email, phone, photo_url, role, created_at)
                VALUES (:uid, :first_name, :last_name, :email, :phone, :photo_url, :role, NOW())";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':uid'        => $data['uid'],
            ':first_name' => $data['firstName'],
            ':last_name'  => $data['lastName'],
            ':email'      => $data['email'],
            ':phone'      => $data['phone'],
            ':photo_url'  => $data['photoUrl'] ?? null,
            ':role'       => $data['role'] ?? 'user',
        ]);
    }

    public function getUserByUid(string $uid)
    {
        $sql = "SELECT * FROM users WHERE uid = :uid LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':uid' => $uid]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function userExists(string $uid): bool
    {
        $sql = "SELECT COUNT(*) AS total FROM users WHERE uid = :uid";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':uid' => $uid]);

        $result = $stmt->fetch();
        return $result['total'] > 0;
    }

    public function updatePhotoUrl(string $uid, string $url): bool
    {
        $sql = "UPDATE users SET photo_url = :url WHERE uid = :uid";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':url' => $url,
            ':uid' => $uid
        ]);
    }

}
