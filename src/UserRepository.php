<?php
declare(strict_types=1);

class UserRepository {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function findByUsername(string $username): ?User {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute([':username' => $username]);
        $row = $stmt->fetch();

        if ($row) {
            return new User(
                $row['username'],
                $row['password'],
                (int)$row['id']
            );
        }
        return null;
    }

    public function create(string $username, string $plainPassword): bool {
        $hashedPassword = password_hash($plainPassword, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
        return $stmt->execute([
            ':username' => $username,
            ':password' => $hashedPassword
        ]);
    }

    public function countUsers(): int {
        return (int)$this->db->query("SELECT COUNT(*) FROM users")->fetchColumn();
    }
}