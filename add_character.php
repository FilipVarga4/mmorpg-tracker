<?php require 'templates/header.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
?>


    <h2>Záznam novej postavy a výbavy</h2>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $char_name = htmlspecialchars($_POST['char_name']);
    $combat_style = htmlspecialchars($_POST['combat_style']);
    $gear_rating = htmlspecialchars($_POST['gear_rating']);

    echo "<div style='border: 1px solid green; padding: 10px; margin-bottom: 20px;'>";
    echo "<strong>Dáta úspešne prijaté z formulára:</strong><br>";
    echo "Meno: " . $char_name . "<br>";
    echo "Trieda: " . $combat_style . "<br>";
    echo "Aktuálny Gear Rating: " . $gear_rating;
    echo "</div>";
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

        <label for="gear_rating">Aktuálny Item Rating (napr. 340+):</label><br>
        <input type="number" id="gear_rating" name="gear_rating" min="1" max="400" required><br><br>

        <input type="submit" value="Uložiť progres">
    </form>

<?php require 'templates/footer.php'; ?>