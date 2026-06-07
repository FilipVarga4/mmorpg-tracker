<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

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
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">Sledované postavy</div>
            <div class="stat-value total"><?= $stats['total'] ?></div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Priemerný Item Rating</div>
            <div class="stat-value average"><?= $stats['average'] ?></div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Najvyšší dosiahnutý BiS</div>
            <div class="stat-value max"><?= $stats['max_rating'] ?></div>
        </div>
    </div>

    <h2>Filtrovanie súpisky na Raid</h2>
    <form action="index.php" method="GET" class="filter-form">
        <div class="form-group">
            <label for="search_name">Meno postavy:</label>
            <input type="text" id="search_name" name="search_name" value="<?= htmlspecialchars($searchName) ?>">
        </div>
        <div class="form-group">
            <label for="filter_faction">Frakcia:</label>
            <select id="filter_faction" name="filter_faction">
                <option value="">-- Všetky --</option>
                <option value="Empire" <?= $filterFaction === 'Empire' ? 'selected' : '' ?>>Sith Empire</option>
                <option value="Republic" <?= $filterFaction === 'Republic' ? 'selected' : '' ?>>Galactic Republic</option>
            </select>
        </div>
        <div class="form-group">
            <label for="filter_role">Rola (Role):</label>
            <select id="filter_role" name="filter_role">
                <option value="">-- Všetky --</option>
                <option value="Tank" <?= $filterRole === 'Tank' ? 'selected' : '' ?>>Tank</option>
                <option value="DPS" <?= $filterRole === 'DPS' ? 'selected' : '' ?>>DPS</option>
                <option value="Healer" <?= $filterRole === 'Healer' ? 'selected' : '' ?>>Healer</option>
            </select>
        </div>
        <div class="form-group-lg">
            <label for="filter_style">Discipline:</label>
            <select id="filter_style" name="filter_style">
                <option value="">-- Všetky špecifikácie --</option>
                <?php require 'templates/style_options.php'; ?>
            </select>
        </div>
        <div>
            <input type="submit" value="Aplikovať">
            <?php if (!empty($searchName) || !empty($filterStyle) || !empty($filterFaction) || !empty($filterRole)): ?>
                <a href="index.php" class="btn-reset">Reset</a>
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
                $factionClass = ($char->getFaction() === 'Empire') ? 'badge-empire' : 'badge-republic';
                $roleClass = 'role-' . strtolower($char->getRole());
                ?>
                <tr>
                    <td><span class="badge <?= $factionClass ?>"><?= $char->getFaction() ?></span></td>
                    <td><span class="role-text <?= $roleClass ?>"><?= $char->getRole() ?></span></td>
                    <td><strong><?= htmlspecialchars($char->getName()) ?></strong></td>
                    <td><span class="discipline-tag"><?= htmlspecialchars($char->getCombatStyle()) ?></span></td>
                    <td><?= $char->getGearRating() ?> / <span class="text-muted-sm"><?= $char->getTargetRating() ?></span></td>
                    <td class="progress-cell">
                        <div class="progress-wrapper">
                            <div class="progress-bg">
                                <div class="progress-bar" style="width: <?= $pct ?>%;"></div>
                            </div>
                            <span class="progress-text"><?= $pct ?>%</span>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const activeDiscipline = "<?= htmlspecialchars($filterStyle) ?>";

            if (activeDiscipline) {
                document.getElementById('filter_style').value = activeDiscipline;
            }
        });
    </script>

<?php require 'templates/footer.php'; ?>

<?php require 'templates/footer.php'; ?>