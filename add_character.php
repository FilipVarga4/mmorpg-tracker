<?php
require_once 'autoload.php';
require 'templates/header.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $char_name = htmlspecialchars(trim($_POST['char_name']));
    $combat_style = htmlspecialchars(trim($_POST['combat_style']));
    $faction = htmlspecialchars(trim($_POST['faction']));
    $gear_rating = (int)$_POST['gear_rating'];
    $target_rating = (int)$_POST['target_rating'];
    $role = RoleMapper::getRoleByStyle($combat_style);

    if (empty($char_name) || strlen($char_name) < 2) {
        $error = "Meno postavy musí mať aspoň 2 znaky.";
    } elseif ($gear_rating > 343 || $target_rating > 343) {
        $error = "Item Rating nemôže prekročiť maximálny herný strop 343.";
    } elseif ($gear_rating > $target_rating) {
        $error = "Aktuálny rating nemôže byť vyšší ako cieľový BiS rating.";
    } else {
        $character = new Character($char_name, $combat_style, $gear_rating, $target_rating, $faction, $role);
        $repo = new CharacterRepository();

        if ($repo->save($character)) {
            $success = "Postava <strong>$char_name</strong> ($role) bola úspešne uložená.";
        } else {
            $error = "Nastala chyba pri ukladaní do databázy.";
        }
    }
}
?>

    <h2>Záznam novej postavy a výbavy</h2>

<?php
if ($success) echo "<div class='alert alert-success'>$success <a href='index.php'>Zobraziť prehľad</a></div>";
if ($error) echo "<div class='alert alert-danger'>$error</div>";
?>

    <form action="add_character.php" method="POST">
        <label for="char_name">Meno postavy:</label><br>
        <input type="text" id="char_name" name="char_name" required><br><br>

        <label for="faction">Frakcia:</label><br>
        <select id="faction" name="faction" required>
            <option value="Empire">Sith Empire</option>
            <option value="Republic">Galactic Republic</option>
        </select><br><br>

        <label for="combat_style">Combat Style / Discipline:</label><br>
        <select id="combat_style" name="combat_style" required>
            <?php require 'templates/style_options.php'; ?>
        </select><br><br>

        <label for="gear_rating">Aktuálny Item Rating (Max 343):</label><br>
        <input type="number" id="gear_rating" name="gear_rating" min="1" max="343" value="340" required><br><br>

        <label for="target_rating">Cieľový BiS Item Rating (Max 343):</label><br>
        <input type="number" id="target_rating" name="target_rating" min="1" max="343" value="343" required><br><br>

        <input type="submit" value="Uložiť progres">
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const factionSelect = document.getElementById('faction');
            const styleSelect = document.getElementById('combat_style');
            const allOptions = Array.from(styleSelect.options);

            function filterStyles() {
                const selectedFaction = factionSelect.value;
                styleSelect.innerHTML = '';

                allOptions.forEach(opt => {
                    if (opt.getAttribute('data-faction') === selectedFaction) {
                        styleSelect.appendChild(opt);
                    }
                });
            }

            factionSelect.addEventListener('change', filterStyles);
            filterStyles();
        });
    </script>

<?php require 'templates/footer.php'; ?>