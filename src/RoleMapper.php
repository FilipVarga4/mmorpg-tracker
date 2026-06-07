<?php
declare(strict_types=1);

class RoleMapper {
    private static array $mapping = [
        // Tanks
        'Darkness' => 'Tank', 'Kinetic Combat' => 'Tank',
        'Immortal' => 'Tank', 'Defense' => 'Tank',
        'Shield Tech' => 'Tank', 'Shield Specialist' => 'Tank',

        // Healers
        'Corruption' => 'Healer', 'Seer' => 'Healer',
        'Bodyguard' => 'Healer', 'Combat Medic' => 'Healer',
        'Medicine' => 'Healer', 'Sawbones' => 'Healer',

        // DPS (Všetko ostatné)
        'Deception' => 'DPS', 'Hatred' => 'DPS', 'Infiltration' => 'DPS', 'Serenity' => 'DPS',
        'Vengeance' => 'DPS', 'Rage' => 'DPS', 'Vigilance' => 'DPS', 'Focus' => 'DPS',
        'Annihilation' => 'DPS', 'Carnage' => 'DPS', 'Fury' => 'DPS', 'Watchman' => 'DPS', 'Combat' => 'DPS', 'Concentration' => 'DPS',
        'Lightning' => 'DPS', 'Madness' => 'DPS', 'Telekinetics' => 'DPS', 'Balance' => 'DPS',
        'Advanced Prototype' => 'DPS', 'Pyrotech' => 'DPS', 'Tactics' => 'DPS', 'Plasmatech' => 'DPS',
        'Arsenal' => 'DPS', 'Innovative Ordnance' => 'DPS', 'Gunnery' => 'DPS', 'Assault Specialist' => 'DPS',
        'Concealment' => 'DPS', 'Lethality' => 'DPS', 'Scrapper' => 'DPS', 'Ruffian' => 'DPS',
        'Marksmanship' => 'DPS', 'Engineering' => 'DPS', 'Virulence' => 'DPS', 'Sharpshooter' => 'DPS', 'Saboteur' => 'DPS', 'Dirty Fighting' => 'DPS'
    ];

    public static function getRoleByStyle(string $style): string {
        return self::$mapping[$style] ?? 'DPS';
    }
}