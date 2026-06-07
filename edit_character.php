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
    $target_rating = (int)$_POST['target_rating'];

    if (empty($char_name) || strlen($char_name) < 2) {
        $error = "Meno postavy musí mať aspoň 2 znaky.";
    } elseif ($gear_rating > 343 || $target_rating > 343) {
        $error = "Item Rating nemôže prekročiť maximálny herný strop 343.";
    } elseif ($gear_rating < 1 || $target_rating < 1) {
        $error = "Item Rating musí byť kladné číslo.";
    } elseif ($gear_rating > $target_rating) {
        $error = "Aktuálny rating nemôže byť vyšší ako cieľový BiS rating.";
    } else {
        $updatedCharacter = new Character($char_name, $combat_style, $gear_rating, $target_rating, $id);

        if ($repo->update($updatedCharacter)) {
            $success = "Postava bola úspešne upravená.";
            $character = $updatedCharacter;
        } else {
            $error = "Nastala chyba pri úprave v databáze.";
        }
    }
}

function isSelected(string $value, Character $char): string {
    return $char->getCombatStyle() === $value ? 'selected' : '';
}
?>

    <h2>Úprava postavy</h2>

<?php
if ($success) echo "<div class='alert alert-success'>$success <a href='index.php'>Späť na prehľad</a></div>";
if ($error) echo "<div class='alert alert-danger'>$error</div>";
?>

    <form action="edit_character.php?id=<?= $character->getId() ?>" method="POST">
        <label for="char_name">Meno postavy:</label><br>
        <input type="text" id="char_name" name="char_name" value="<?= htmlspecialchars($character->getName()) ?>" required><br><br>

        <label for="combat_style">Combat Style / Discipline:</label><br>
        <select id="combat_style" name="combat_style" required>
            <optgroup label="Assassin / Shadow">
                <option value="Darkness / Kinetic Combat" <?= isSelected("Darkness / Kinetic Combat", $character) ?>>Darkness / Kinetic Combat (Tank)</option>
                <option value="Deception / Infiltration" <?= isSelected("Deception / Infiltration", $character) ?>>Deception / Infiltration (DPS)</option>
                <option value="Hatred / Serenity" <?= isSelected("Hatred / Serenity", $character) ?>>Hatred / Serenity (DPS)</option>
            </optgroup>
            <optgroup label="Juggernaut / Guardian">
                <option value="Immortal / Defense" <?= isSelected("Immortal / Defense", $character) ?>>Immortal / Defense (Tank)</option>
                <option value="Vengeance / Vigilance" <?= isSelected("Vengeance / Vigilance", $character) ?>>Vengeance / Vigilance (DPS)</option>
                <option value="Rage / Focus" <?= isSelected("Rage / Focus", $character) ?>>Rage / Focus (DPS)</option>
            </optgroup>
            <optgroup label="Marauder / Sentinel">
                <option value="Annihilation / Watchman" <?= isSelected("Annihilation / Watchman", $character) ?>>Annihilation / Watchman (DPS)</option>
                <option value="Carnage / Combat" <?= isSelected("Carnage / Combat", $character) ?>>Carnage / Combat (DPS)</option>
                <option value="Fury / Concentration" <?= isSelected("Fury / Concentration", $character) ?>>Fury / Concentration (DPS)</option>
            </optgroup>
            <optgroup label="Sorcerer / Sage">
                <option value="Lightning / Telekinetics" <?= isSelected("Lightning / Telekinetics", $character) ?>>Lightning / Telekinetics (DPS)</option>
                <option value="Madness / Balance" <?= isSelected("Madness / Balance", $character) ?>>Madness / Balance (DPS)</option>
                <option value="Corruption / Seer" <?= isSelected("Corruption / Seer", $character) ?>>Corruption / Seer (Healer)</option>
            </optgroup>
            <optgroup label="Powertech / Vanguard">
                <option value="Shield Tech / Shield Specialist" <?= isSelected("Shield Tech / Shield Specialist", $character) ?>>Shield Tech / Shield Specialist (Tank)</option>
                <option value="Advanced Prototype / Tactics" <?= isSelected("Advanced Prototype / Tactics", $character) ?>>Advanced Prototype / Tactics (DPS)</option>
                <option value="Pyrotech / Plasmatech" <?= isSelected("Pyrotech / Plasmatech", $character) ?>>Pyrotech / Plasmatech (DPS)</option>
            </optgroup>
            <optgroup label="Mercenary / Commando">
                <option value="Arsenal / Gunnery" <?= isSelected("Arsenal / Gunnery", $character) ?>>Arsenal / Gunnery (DPS)</option>
                <option value="Innovative Ordnance / Assault Spec" <?= isSelected("Innovative Ordnance / Assault Spec", $character) ?>>Innovative Ordnance / Assault Spec (DPS)</option>
                <option value="Bodyguard / Combat Medic" <?= isSelected("Bodyguard / Combat Medic", $character) ?>>Bodyguard / Combat Medic (Healer)</option>
            </optgroup>
            <optgroup label="Operative / Scoundrel">
                <option value="Concealment / Scrapper" <?= isSelected("Concealment / Scrapper", $character) ?>>Concealment / Scrapper (DPS)</option>
                <option value="Lethality / Ruffian" <?= isSelected("Lethality / Ruffian", $character) ?>>Lethality / Ruffian (DPS)</option>
                <option value="Medicine / Sawbones" <?= isSelected("Medicine / Sawbones", $character) ?>>Medicine / Sawbones (Healer)</option>
            </optgroup>
            <optgroup label="Sniper / Gunslinger">
                <option value="Marksmanship / Sharpshooter" <?= isSelected("Marksmanship / Sharpshooter", $character) ?>>Marksmanship / Sharpshooter (DPS)</option>
                <option value="Engineering / Saboteur" <?= isSelected("Engineering / Saboteur", $character) ?>>Engineering / Saboteur (DPS)</option>
                <option value="Virulence / Dirty Fighting" <?= isSelected("Virulence / Dirty Fighting", $character) ?>>Virulence / Dirty Fighting (DPS)</option>
            </optgroup>
        </select><br><br>

        <label for="gear_rating">Aktuálny Item Rating (Max 343):</label><br>
        <input type="number" id="gear_rating" name="gear_rating" min="1" max="343" value="<?= $character->getGearRating() ?>" required><br><br>

        <label for="target_rating">Cieľový BiS Item Rating (Max 343):</label><br>
        <input type="number" id="target_rating" name="target_rating" min="1" max="343" value="<?= $character->getTargetRating() ?>" required><br><br>

        <input type="submit" value="Uložiť zmeny">
    </form>

<?php require 'templates/footer.php'; ?>