<?php
declare(strict_types=1);

class CharacterRepository {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function save(Character $character): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO characters (char_name, combat_style, gear_rating) VALUES (:name, :style, :rating)"
        );
        return $stmt->execute([
            ':name' => $character->getName(),
            ':style' => $character->getCombatStyle(),
            ':rating' => $character->getGearRating()
        ]);
    }

    public function getAll(): array {
        $stmt = $this->db->query("SELECT * FROM characters ORDER BY gear_rating DESC");
        $results = [];
        while ($row = $stmt->fetch()) {
            $results[] = new Character(
                $row['char_name'],
                $row['combat_style'],
                (int)$row['gear_rating'],
                (int)$row['id']
            );
        }
        return $results;
    }

    public function findById(int $id): ?Character {
        $stmt = $this->db->prepare("SELECT * FROM characters WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();

        if ($row) {
            return new Character(
                $row['char_name'],
                $row['combat_style'],
                (int)$row['gear_rating'],
                (int)$row['id']
            );
        }
        return null;
    }

    public function update(Character $character): bool {
        $stmt = $this->db->prepare(
            "UPDATE characters SET char_name = :name, combat_style = :style, gear_rating = :rating WHERE id = :id"
        );
        return $stmt->execute([
            ':name' => $character->getName(),
            ':style' => $character->getCombatStyle(),
            ':rating' => $character->getGearRating(),
            ':id' => $character->getId()
        ]);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM characters WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}