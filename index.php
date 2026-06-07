<?php
require_once 'autoload.php';
require 'templates/header.php';
$isLoggedIn = isset($_SESSION['user_id']);

$repo = new CharacterRepository();
$stats = $repo->getGlobalStats();

$searchName = isset($_GET['search_name']) ? trim($_GET['search_name']) : '';
$filterStyle = isset($_GET['filter_style']) ? trim($_GET['filter_style']) : '';
$filterFaction = isset($_GET['filter_faction']) ? trim($_GET['filter_faction']) : '';
$filterRole = isset($_GET['filter_role']) ? trim($_GET['filter_role']) : '';

$characters = $repo->search($searchName, $filterStyle, $filterFaction, $filterRole);
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

    <h2>Filtrovanie súpisky na Raid</h2>
    <form action="index.php" method="GET" style="max-width: 100%; display: flex; flex-wrap: wrap; gap: 1rem; padding: 1.5rem; align-items: flex-end; margin-bottom: 2rem;">
        <div style="flex: 1; min-width: 130px;">
            <label for="search_name">Meno postavy:</label>
            <input type="text" id="search_name" name="search_name" value="<?= htmlspecialchars($searchName) ?>" style="margin-bottom: 0;">
        </div>
        <div style="flex: 1; min-width: 130px;">
            <label for="filter_faction">Frakcia:</label>
            <select id="filter_faction" name="filter_faction" style="margin-bottom: 0;">
                <option value="">-- Všetky --</option>
                <option value="Empire" <?= $filterFaction === 'Empire' ? 'selected' : '' ?>>Sith Empire</option>
                <option value="Republic" <?= $filterFaction === 'Republic' ? 'selected' : '' ?>>Galactic Republic</option>
            </select>
        </div>
        <div style="flex: 1; min-width: 130px;">
            <label for="filter_role">Rola (Role):</label>
            <select id="filter_role" name="filter_role" style="margin-bottom: 0;">
                <option value="">-- Všetky --</option>
                <option value="Tank" <?= $filterRole === 'Tank' ? 'selected' : '' ?>>Tank</option>
                <option value="DPS" <?= $filterRole === 'DPS' ? 'selected' : '' ?>>DPS</option>
                <option value="Healer" <?= $filterRole === 'Healer' ? 'selected' : '' ?>>Healer</option>
            </select>
        </div>
        <div style="flex: 1; min-width: 150px;">
            <label for="filter_style">Discipline:</label>
            <select id="filter_style" name="filter_style" style="margin-bottom: 0;">
                <option value="">-- Všetky špecifikácie --</option>
                <?php require 'templates/style_options.php'; ?>
            </select>
        </div>
        <div>
            <input type="submit" value="Aplikovať" style="padding: 0.75rem 1.5rem;">
            <?php if (!empty($searchName) || !empty($filterStyle) || !empty($filterFaction) || !empty($filterRole)): ?>
                <a href="index.php" style="margin-left: 1rem; color: var(--text-muted); text-decoration: none; font-weight: 500;">Reset</a>
            <?php endif; ?>
        </div>
    </form>

    <h2>Prehľad progresie postáv</h2>
    <table>
        <thead>
        <tr>
            <th>Frakcia</th>
            <th>Rola</th>
            <th>Meno postavy</th>
            <th>Discipline</th>
            <th>Gear Rating</th>
            <th>BiS Progres</th>
            <?php if ($isLoggedIn): ?><th>Akcie</th><?php endif; ?>
        </tr>
        </thead>
        <tbody>
        <?php if (empty($characters)): ?>
            <tr><td colspan="<?= $isLoggedIn ? 7 : 6 ?>">Žiadne postavy nevyhovujú kritériám vyhľadávania.</td></tr>
        <?php else: ?>
            <?php foreach ($characters as $char):
                $pct = $char->getProgressionPercentage();
                $facColor = ($char->getFaction() === 'Empire') ? '#ef4444' : '#3b82f6';


                $roleColor = '#a1a1aa';
                if ($char->getRole() === 'Tank') $roleColor = '#c084fc';
                if ($char->getRole() === 'Healer') $roleColor = '#4ade80';
                if ($char->getRole() === 'DPS') $roleColor = '#f87171';
                ?>
                <tr>
                    <td><span style="color: <?= $facColor ?>; border: 1px solid <?= $facColor ?>; padding: 0.15rem 0.4rem; border-radius: 4px; font-size: 0.75rem; font-weight: bold; text-transform: uppercase;"><?= $char->getFaction() ?></span></td>
                    <td><span style="color: <?= $roleColor ?>; font-weight: bold; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.05em;"><?= $char->getRole() ?></span></td>
                    <td><strong><?= htmlspecialchars($char->getName()) ?></strong></td>
                    <td><span style="background-color: #242429; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.875rem;"><?= htmlspecialchars($char->getCombatStyle()) ?></span></td>
                    <td><?= $char->getGearRating() ?> / <span style="color: var(--text-muted); font-size: 0.875rem;"><?= $char->getTargetRating() ?></span></td>
                    <td style="width: 220px;">
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