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
        $character = new Character($char_name, $combat_style, $gear_rating, $target_rating);
        $repo = new CharacterRepository();

        if ($repo->save($character)) {
            $success = "Postava <strong>$char_name</strong> bola úspešne uložená.";
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

        <label for="combat_style">Combat Style / Discipline:</label><br>
        <select id="combat_style" name="combat_style" required>
            <optgroup label="Assassin / Shadow">
                <option value="Darkness / Kinetic Combat">Darkness / Kinetic Combat (Tank)</option>
                <option value="Deception / Infiltration">Deception / Infiltration (DPS)</option>
                <option value="Hatred / Serenity">Hatred / Serenity (DPS)</option>
            </optgroup>
            <optgroup label="Juggernaut / Guardian">
                <option value="Immortal / Defense">Immortal / Defense (Tank)</option>
                <option value="Vengeance / Vigilance">Vengeance / Vigilance (DPS)</option>
                <option value="Rage / Focus">Rage / Focus (DPS)</option>
            </optgroup>
            <optgroup label="Marauder / Sentinel">
                <option value="Annihilation / Watchman">Annihilation / Watchman (DPS)</option>
                <option value="Carnage / Combat">Carnage / Combat (DPS)</option>
                <option value="Fury / Concentration">Fury / Concentration (DPS)</option>
            </optgroup>
            <optgroup label="Sorcerer / Sage">
                <option value="Lightning / Telekinetics">Lightning / Telekinetics (DPS)</option>
                <option value="Madness / Balance">Madness / Balance (DPS)</option>
                <option value="Corruption / Seer">Corruption / Seer (Healer)</option>
            </optgroup>
            <optgroup label="Powertech / Vanguard">
                <option value="Shield Tech / Shield Specialist">Shield Tech / Shield Specialist (Tank)</option>
                <option value="Advanced Prototype / Tactics">Advanced Prototype / Tactics (DPS)</option>
                <option value="Pyrotech / Plasmatech">Pyrotech / Plasmatech (DPS)</option>
            </optgroup>
            <optgroup label="Mercenary / Commando">
                <option value="Arsenal / Gunnery">Arsenal / Gunnery (DPS)</option>
                <option value="Innovative Ordnance / Assault Spec">Innovative Ordnance / Assault Spec (DPS)</option>
                <option value="Bodyguard / Combat Medic">Bodyguard / Combat Medic (Healer)</option>
            </optgroup>
            <optgroup label="Operative / Scoundrel">
                <option value="Concealment / Scrapper">Concealment / Scrapper (DPS)</option>
                <option value="Lethality / Ruffian">Lethality / Ruffian (DPS)</option>
                <option value="Medicine / Sawbones">Medicine / Sawbones (Healer)</option>
            </optgroup>
            <optgroup label="Sniper / Gunslinger">
                <option value="Marksmanship / Sharpshooter">Marksmanship / Sharpshooter (DPS)</option>
                <option value="Engineering / Saboteur">Engineering / Saboteur (DPS)</option>
                <option value="Virulence / Dirty Fighting">Virulence / Dirty Fighting (DPS)</option>
            </optgroup>
        </select><br><br>

        <label for="gear_rating">Aktuálny Item Rating (Max 343):</label><br>
        <input type="number" id="gear_rating" name="gear_rating" min="1" max="343" value="340" required><br><br>

        <label for="target_rating">Cieľový BiS Item Rating (Max 343):</label><br>
        <input type="number" id="target_rating" name="target_rating" min="1" max="343" value="343" required><br><br>

        <input type="submit" value="Uložiť progres">
    </form>

<?php require 'templates/footer.php'; ?>