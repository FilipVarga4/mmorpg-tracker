<?php
require_once 'autoload.php';
require 'templates/header.php';

$error = '';

if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $repo = new UserRepository();
    $user = $repo->findByUsername($username);

    if ($user && password_verify($password, $user->getPassword())) {
        $_SESSION['user_id'] = $user->getId();
        $_SESSION['username'] = $user->getUsername();
        header('Location: index.php');
        exit;
    } else {
        $error = 'Nesprávne meno alebo heslo.';
    }
}
?>

    <h2>Prihlásenie do administrácie</h2>
<?php if ($error): ?>
    <div style="color: red; margin-bottom: 15px;"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

    <form action="login.php" method="POST">
        <label for="username">Používateľské meno:</label><br>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Heslo:</label><br>
        <input type="password" id="password" name="password" required><br><br>

        <input type="submit" value="Prihlásiť sa">
    </form>

<?php require 'templates/footer.php'; ?>