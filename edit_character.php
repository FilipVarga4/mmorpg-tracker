<?php
require_once 'autoload.php';
require 'templates/header.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$repo = new CharacterRepository();
$character = null;
$error = '';
$success = '';

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $character = $repo->findById($id);
    if (!$character) {
        die("Postava nebola nájdená.");
    }
} else {
    die("Nebolo zadané ID postavy.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $char_name = htmlspecialchars(trim($_POST['char_name']));
    $combat_style = htmlspecialchars(trim($_POST['combat_style']));
    $gear_rating = (int)$_POST['gear_rating'];

    $updatedCharacter = new Character($char_name, $combat_style, $gear_rating, $id);

    if ($repo->update($updatedCharacter)) {
        $success = "Postava bola úspešne upravená.";
        $character = $updatedCharacter;
    } else {
        $error = "Nastala chyba pri úprave.";
    }
}
?>

    <h2>Úprava postavy</h2>

<?php
if ($success) echo "<div style='color: green; margin-bottom: 15px;'>$success <a href='index.php'>Späť na prehľad</a></div>";
if ($error) echo "<div style='color: red; margin-bottom: 15px;'>$error</div>";
?>

    <form action="edit_character.php?id=<?= $character->getId() ?>" method="POST">
        <label for="char_name">Meno postavy:</label><br>
        <input type="text" id="char_name" name="char_name" value="<?= htmlspecialchars($character->getName()) ?>" required><br><br>

        <label for="combat_style">Combat Style:</label><br>
        <select id="combat_style" name="combat_style" required>
            <option value="Sith Assassin" <?= $character->getCombatStyle() === 'Sith Assassin' ? 'selected' : '' ?>>Sith Assassin</option>
            <option value="Jedi Shadow" <?= $character->getCombatStyle() === 'Jedi Shadow' ? 'selected' : '' ?>>Jedi Shadow</option>
            <option value="Sith Warrior" <?= $character->getCombatStyle() === 'Sith Warrior' ? 'selected' : '' ?>>Sith Warrior</option>
        </select><br><br>

        <label for="gear_rating">Aktuálny Item Rating:</label><br>
        <input type="number" id="gear_rating" name="gear_rating" min="1" max="400" value="<?= $character->getGearRating() ?>" required><br><br>

        <input type="submit" value="Uložiť zmeny">
    </form>

<?php require 'templates/footer.php'; ?>