<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once 'autoload.php';

$repo = new UserRepository();

if ($repo->countUsers() === 0) {
    $repo->create('admin', 'admin123');
    echo "Inicializácia úspešná: Používateľ 'admin' s heslom 'admin123' bol vytvorený.";
} else {
    echo "Inicializácia zlyhala: Používatelia už v databáze existujú. Skript bol zablokovaný.";
}