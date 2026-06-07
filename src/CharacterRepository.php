<?php
declare(strict_types=1);

class CharacterRepository {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function save(Character $character): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO characters (char_name, combat_style, gear_rating, target_rating, faction, role) VALUES (:name, :style, :rating, :target_rating, :faction, :role)"
        );
        return $stmt->execute([
            ':name' => $character->getName(),
            ':style' => $character->getCombatStyle(),
            ':rating' => $character->getGearRating(),
            ':target_rating' => $character->getTargetRating(),
            ':faction' => $character->getFaction(),
            ':role' => $character->getRole()
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
                (int)$row['target_rating'],
                $row['faction'],
                $row['role'],
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
                (int)$row['target_rating'],
                $row['faction'],
                $row['role'],
                (int)$row['id']
            );
        }
        return null;
    }

    public function update(Character $character): bool {
        $stmt = $this->db->prepare(
            "UPDATE characters SET char_name = :name, combat_style = :style, gear_rating = :rating, target_rating = :target_rating, faction = :faction, role = :role WHERE id = :id"
        );
        return $stmt->execute([
            ':name' => $character->getName(),
            ':style' => $character->getCombatStyle(),
            ':rating' => $character->getGearRating(),
            ':target_rating' => $character->getTargetRating(),
            ':faction' => $character->getFaction(),
            ':role' => $character->getRole(),
            ':id' => $character->getId()
        ]);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM characters WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function search(string $name = '', string $style = '', string $faction = '', string $role = ''): array {
        $sql = "SELECT * FROM characters WHERE 1=1";
        $params = [];

        if (!empty($name)) {
            $sql .= " AND char_name LIKE :name";
            $params[':name'] = '%' . $name . '%';
        }
        if (!empty($style)) {
            $sql .= " AND combat_style = :style";
            $params[':style'] = $style;
        }
        if (!empty($faction)) {
            $sql .= " AND faction = :faction";
            $params[':faction'] = $faction;
        }
        if (!empty($role)) {
            $sql .= " AND role = :role";
            $params[':role'] = $role;
        }

        $sql .= " ORDER BY gear_rating DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        $results = [];
        while ($row = $stmt->fetch()) {
            $results[] = new Character(
                $row['char_name'],
                $row['combat_style'],
                (int)$row['gear_rating'],
                (int)$row['target_rating'],
                $row['faction'],
                $row['role'],
                (int)$row['id']
            );
        }
        return $results;
    }

    public function getGlobalStats(): array {
        $stmt = $this->db->query("SELECT COUNT(*) as total, AVG(gear_rating) as average, MAX(gear_rating) as max_rating FROM characters");
        $stats = $stmt->fetch();
        return [
            'total' => (int)($stats['total'] ?? 0),
            'average' => $stats['average'] ? round((float)$stats['average'], 1) : 0.0,
            'max_rating' => (int)($stats['max_rating'] ?? 0)
        ];
    }
}