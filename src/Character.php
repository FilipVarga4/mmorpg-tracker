<?php
declare(strict_types=1);

class Character {
    public function __construct(
        private string $char_name,
        private string $combat_style,
        private int $gear_rating,
        private int $target_rating = 343,
        private string $faction = 'Empire',
        private string $role = 'DPS',
        private ?int $id = null
    ) {}

    public function getName(): string { return $this->char_name; }
    public function getCombatStyle(): string { return $this->combat_style; }
    public function getGearRating(): int { return $this->gear_rating; }
    public function getTargetRating(): int { return $this->target_rating; }
    public function getFaction(): string { return $this->faction; }
    public function getRole(): string { return $this->role; }
    public function getId(): ?int { return $this->id; }

    public function getProgressionPercentage(): float {
        if ($this->target_rating <= 0) return 0.0;
        $percentage = ($this->gear_rating / $this->target_rating) * 100;
        return min(100.0, round($percentage, 1));
    }
}