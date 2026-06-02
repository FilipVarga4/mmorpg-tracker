<?php

declare(strict_types=1);
require_once 'autoload.php';

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $repo = new CharacterRepository();
    $repo->delete($id);
}

header("Location: index.php");
exit;