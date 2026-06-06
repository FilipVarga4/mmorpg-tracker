<?php
require_once 'autoload.php';
require 'templates/header.php';
$isLoggedIn = isset($_SESSION['user_id']);
?>

    <h2>Prehľad postáv</h2>
    <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; text-align: left;">
        <thead>
        <tr>
            <th>ID</th>
            <th>Meno postavy</th>
            <th>Combat Style</th>
            <th>Gear Rating</th>
            <?php if ($isLoggedIn): ?><th>Akcie</th><?php endif; ?>
        </tr>
        </thead>
        <tbody>
        <?php
        $repo = new CharacterRepository();
        $characters = $repo->getAll();

        if (empty($characters)) {
            echo "<tr><td colspan='" . ($isLoggedIn ? 5 : 4) . "'>Zatiaľ neboli pridané žiadne postavy.</td></tr>";
        } else {
            foreach ($characters as $char) {
                echo "<tr>";
                echo "<td>" . $char->getId() . "</td>";
                echo "<td>" . htmlspecialchars($char->getName()) . "</td>";
                echo "<td>" . htmlspecialchars($char->getCombatStyle()) . "</td>";
                echo "<td>" . $char->getGearRating() . "</td>";
                if ($isLoggedIn) {
                    echo "<td>
                        <a href='edit_character.php?id=" . $char->getId() . "'>Upraviť</a> | 
                        <a href='delete_character.php?id=" . $char->getId() . "' onclick=\"return confirm('Naozaj chcete vymazať túto postavu?');\">Vymazať</a>
                      </td>";
                }
                echo "</tr>";
            }
        }
        ?>
        </tbody>
    </table>

<?php require 'templates/footer.php'; ?>