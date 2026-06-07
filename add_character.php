<?php
require_once 'autoload.php';
require 'templates/header.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
?>

    <h2>Záznam novej postavy a výbavy</h2>

<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $char_name = htmlspecialchars(trim($_POST['char_name']));
    $combat_style = htmlspecialchars(trim($_POST['combat_style']));
    $gear_rating = (int)$_POST['gear_rating'];
    $target_rating = (int)$_POST['target_rating'];

    $character = new Character($char_name, $combat_style, $gear_rating, $target_rating);
    $repo = new CharacterRepository();

    if ($repo->save($character)) {
        echo "<div class='alert alert-success'>";
        echo "Postava <strong>$char_name</strong> bola úspešne uložená. <a href='index.php'>Zobraziť prehľad</a>";
        echo "</div>";
    } else {
        echo "<div class='alert alert-danger'>Nastala chyba pri ukladaní.</div>";
    }
}
?>

    <form action="add_character.php" method="POST">
        <label for="char_name">Meno postavy:</label><br>
        <input type="text" id="char_name" name="char_name" required><br><br>

        <label for="combat_style">Combat Style:</label><br>
        <select id="combat_style" name="combat_style" required>
            <option value="Sith Assassin">Sith Assassin</option>
            <option value="Jedi Shadow">Jedi Shadow</option>
            <option value="Sith Warrior">Sith Warrior</option>
        </select><br><br>

        <label for="gear_rating">Aktuálny Item Rating:</label><br>
        <input type="number" id="gear_rating" name="gear_rating" min="1" max="400" required><br><br>

        <label for="target_rating">Cieľový BiS Item Rating:</label><br>
        <input type="number" id="target_rating" name="target_rating" min="1" max="400" value="343" required><br><br>

        <input type="submit" value="Uložiť progres">
    </form>

<?php require 'templates/footer.php'; ?>