<?php
require_once 'autoload.php';
require 'templates/header.php';
$isLoggedIn = isset($_SESSION['user_id']);

$repo = new CharacterRepository();
$stats = $repo->getGlobalStats();

$searchName = isset($_GET['search_name']) ? trim($_GET['search_name']) : '';
$filterStyle = isset($_GET['filter_style']) ? trim($_GET['filter_style']) : '';

$characters = $repo->search($searchName, $filterStyle);
?>

    <h2>Dashboard & Štatistiky</h2>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
        <div style="background-color: var(--card-bg); padding: 1.5rem; border-radius: 8px; border: 1px solid var(--border-color); text-align: center;">
            <div style="color: var(--text-muted); font-size: 0.875rem; text-transform: uppercase;">Sledované postavy</div>
            <div style="font-size: 2rem; font-weight: bold; color: var(--accent-color); margin-top: 0.5rem;"><?= $stats['total'] ?></div>
        </div>
        <div style="background-color: var(--card-bg); padding: 1.5rem; border-radius: 8px; border: 1px solid var(--border-color); text-align: center;">
            <div style="color: var(--text-muted); font-size: 0.875rem; text-transform: uppercase;">Priemerný Item Rating</div>
            <div style="font-size: 2rem; font-weight: bold; color: var(--success-color); margin-top: 0.5rem;"><?= $stats['average'] ?></div>
        </div>
        <div style="background-color: var(--card-bg); padding: 1.5rem; border-radius: 8px; border: 1px solid var(--border-color); text-align: center;">
            <div style="color: var(--text-muted); font-size: 0.875rem; text-transform: uppercase;">Najvyšší dosiahnutý BiS</div>
            <div style="font-size: 2rem; font-weight: bold; color: #f59e0b; margin-top: 0.5rem;"><?= $stats['max_rating'] ?></div>
        </div>
    </div>

    <h2>Filtrovanie postáv</h2>
    <form action="index.php" method="GET" style="max-width: 100%; display: flex; flex-wrap: wrap; gap: 1rem; padding: 1.5rem; align-items: flex-end; margin-bottom: 2rem;">
        <div style="flex: 1; min-width: 200px;">
            <label for="search_name" style="margin-bottom: 0.5rem;">Hľadať podľa mena:</label>
            <input type="text" id="search_name" name="search_name" value="<?= htmlspecialchars($searchName) ?>" style="margin-bottom: 0;">
        </div>
        <div style="flex: 1; min-width: 200px;">
            <label for="filter_style" style="margin-bottom: 0.5rem;">Combat Style:</label>
            <select id="filter_style" name="filter_style" style="margin-bottom: 0;">
                <option value="">-- Všetky triedy --</option>
                <optgroup label="Assassin / Shadow">
                    <option value="Darkness / Kinetic Combat" <?= $filterStyle === 'Darkness / Kinetic Combat' ? 'selected' : '' ?>>Darkness / Kinetic Combat</option>
                    <option value="Deception / Infiltration" <?= $filterStyle === 'Deception / Infiltration' ? 'selected' : '' ?>>Deception / Infiltration</option>
                    <option value="Hatred / Serenity" <?= $filterStyle === 'Hatred / Serenity' ? 'selected' : '' ?>>Hatred / Serenity</option>
                </optgroup>
                <optgroup label="Juggernaut / Guardian">
                    <option value="Immortal / Defense" <?= $filterStyle === 'Immortal / Defense' ? 'selected' : '' ?>>Immortal / Defense</option>
                    <option value="Vengeance / Vigilance" <?= $filterStyle === 'Vengeance / Vigilance' ? 'selected' : '' ?>>Vengeance / Vigilance</option>
                    <option value="Rage / Focus" <?= $filterStyle === 'Rage / Focus' ? 'selected' : '' ?>>Rage / Focus</option>
                </optgroup>
                <optgroup label="Marauder / Sentinel">
                    <option value="Annihilation / Watchman" <?= $filterStyle === 'Annihilation / Watchman' ? 'selected' : '' ?>>Annihilation / Watchman</option>
                    <option value="Carnage / Combat" <?= $filterStyle === 'Carnage / Combat' ? 'selected' : '' ?>>Carnage / Combat</option>
                    <option value="Fury / Concentration" <?= $filterStyle === 'Fury / Concentration' ? 'selected' : '' ?>>Fury / Concentration</option>
                </optgroup>
                <optgroup label="Sorcerer / Sage">
                    <option value="Lightning / Telekinetics" <?= $filterStyle === 'Lightning / Telekinetics' ? 'selected' : '' ?>>Lightning / Telekinetics</option>
                    <option value="Madness / Balance" <?= $filterStyle === 'Madness / Balance' ? 'selected' : '' ?>>Madness / Balance</option>
                    <option value="Corruption / Seer" <?= $filterStyle === 'Corruption / Seer' ? 'selected' : '' ?>>Corruption / Seer</option>
                </optgroup>
                <optgroup label="Powertech / Vanguard">
                    <option value="Shield Tech / Shield Specialist" <?= $filterStyle === 'Shield Tech / Shield Specialist' ? 'selected' : '' ?>>Shield Tech / Shield Specialist</option>
                    <option value="Advanced Prototype / Tactics" <?= $filterStyle === 'Advanced Prototype / Tactics' ? 'selected' : '' ?>>Advanced Prototype / Tactics</option>
                    <option value="Pyrotech / Plasmatech" <?= $filterStyle === 'Pyrotech / Plasmatech' ? 'selected' : '' ?>>Pyrotech / Plasmatech</option>
                </optgroup>
                <optgroup label="Mercenary / Commando">
                    <option value="Arsenal / Gunnery" <?= $filterStyle === 'Arsenal / Gunnery' ? 'selected' : '' ?>>Arsenal / Gunnery</option>
                    <option value="Innovative Ordnance / Assault Spec" <?= $filterStyle === 'Innovative Ordnance / Assault Spec' ? 'selected' : '' ?>>Innovative Ordnance / Assault Spec</option>
                    <option value="Bodyguard / Combat Medic" <?= $filterStyle === 'Bodyguard / Combat Medic' ? 'selected' : '' ?>>Bodyguard / Combat Medic</option>
                </optgroup>
                <optgroup label="Operative / Scoundrel">
                    <option value="Concealment / Scrapper" <?= $filterStyle === 'Concealment / Scrapper' ? 'selected' : '' ?>>Concealment / Scrapper</option>
                    <option value="Lethality / Ruffian" <?= $filterStyle === 'Lethality / Ruffian' ? 'selected' : '' ?>>Lethality / Ruffian</option>
                    <option value="Medicine / Sawbones" <?= $filterStyle === 'Medicine / Sawbones' ? 'selected' : '' ?>>Medicine / Sawbones</option>
                </optgroup>
                <optgroup label="Sniper / Gunslinger">
                    <option value="Marksmanship / Sharpshooter" <?= $filterStyle === 'Marksmanship / Sharpshooter' ? 'selected' : '' ?>>Marksmanship / Sharpshooter</option>
                    <option value="Engineering / Saboteur" <?= $filterStyle === 'Engineering / Saboteur' ? 'selected' : '' ?>>Engineering / Saboteur</option>
                    <option value="Virulence / Dirty Fighting" <?= $filterStyle === 'Virulence / Dirty Fighting' ? 'selected' : '' ?>>Virulence / Dirty Fighting</option>
                </optgroup>
            </select>
        </div>
        <div>
            <input type="submit" value="Aplikovať" style="padding: 0.75rem 1.5rem;">
            <?php if (!empty($searchName) || !empty($filterStyle)): ?>
                <a href="index.php" style="margin-left: 1rem; color: var(--text-muted); text-decoration: none; font-weight: 500;">Reset</a>
            <?php endif; ?>
        </div>
    </form>

    <h2>Prehľad progresie postáv</h2>
    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Meno postavy</th>
            <th>Combat Style</th>
            <th>Gear Rating</th>
            <th>BiS Progres (Max 343)</th>
            <?php if ($isLoggedIn): ?><th>Akcie</th><?php endif; ?>
        </tr>
        </thead>
        <tbody>
        <?php if (empty($characters)): ?>
            <tr><td colspan="<?= $isLoggedIn ? 6 : 5 ?>">Žiadne postavy nevyhovujú kritériám vyhľadávania.</td></tr>
        <?php else: ?>
            <?php foreach ($characters as $char):
                $pct = $char->getProgressionPercentage();
                ?>
                <tr>
                    <td><?= $char->getId() ?></td>
                    <td><strong><?= htmlspecialchars($char->getName()) ?></strong></td>
                    <td><span style="background-color: #242429; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.875rem;"><?= htmlspecialchars($char->getCombatStyle()) ?></span></td>
                    <td><?= $char->getGearRating() ?> / <span style="color: var(--text-muted); font-size: 0.875rem;"><?= $char->getTargetRating() ?></span></td>
                    <td style="width: 250px;">
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <div style="background-color: var(--bg-color); border: 1px solid var(--border-color); width: 100%; height: 12px; border-radius: 6px; overflow: hidden;">
                                <div style="background: linear-gradient(90deg, var(--accent-color), var(--accent-hover)); width: <?= $pct ?>%; height: 100%;"></div>
                            </div>
                            <span style="font-size: 0.875rem; font-weight: 600; min-width: 45px; text-align: right;"><?= $pct ?>%</span>
                        </div>
                    </td>
                    <?php if ($isLoggedIn): ?>
                        <td class="action-links">
                            <a href="edit_character.php?id=<?= $char->getId() ?>">Upraviť</a> |
                            <a href="delete_character.php?id=<?= $char->getId() ?>" onclick="return confirm('Naozaj chcete vymazať túto postavu?');">Vymazať</a>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>

<?php require 'templates/footer.php'; ?>