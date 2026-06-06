<!DOCTYPE html>
<html lang=\"sk\">
<head>
    <meta charset=\"UTF-8\">
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
    <title>MMORPG BiS Tracker</title>
</head>
<body>
<header>
    <h1>Tracker Progresie & Gear Rating</h1>
    <nav>
        <a href="index.php">Domov</a>
        <?php if (isset($_SESSION['user_id'])): ?>
            | <a href="add_character.php">Pridať postavu</a>
            | <a href="logout.php">Odhlásiť sa (<?= htmlspecialchars($_SESSION['username']) ?>)</a>
        <?php else: ?>
            | <a href="login.php">Prihlásenie</a>
        <?php endif; ?>
    </nav>
</header>
<main>