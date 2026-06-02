<?php
declare(strict_types=1);

class Character {
    public function __construct(
        private string $char_name,
        private string $combat_style,
        private int $gear_rating,
        private ?int $id = null
    ) {}

    public function getName(): string { return $this->char_name; }
    public function getCombatStyle(): string { return $this->combat_style; }
    public function getGearRating(): int { return $this->gear_rating; }
    public function getId(): ?int { return $this->id; }
}