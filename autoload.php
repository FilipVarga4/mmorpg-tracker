<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

spl_autoload_register(function ($className) {
    $file = __DIR__ . '/src/' . $className . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});