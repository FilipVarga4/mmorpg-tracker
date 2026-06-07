<?php
require_once 'autoload.php';
require 'templates/header.php';
$isLoggedIn = isset($_SESSION['user_id']);

$repo = new CharacterRepository();
$stats = $repo->getGlobalStats();
$characters = $repo->getAll();
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
            <tr><td colspan="<?= $isLoggedIn ? 6 : 5 ?>">Zatiaľ neboli pridané žiadne postavy.</td></tr>
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
                            <a href="delete_character.php?id=<?= $char->getId() ?>" onclick="return confirm('Naozaj chcete vymazať túto postavu?');\">Vymazať</a>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>

<?php require 'templates/footer.php'; ?>