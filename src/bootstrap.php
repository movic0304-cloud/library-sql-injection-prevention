<?php
declare(strict_types=1);
function database(?string $path = null): PDO {
    $path ??= getenv('LIBRARY_DB') ?: __DIR__ . '/../library.sqlite';
    $pdo = new PDO('sqlite:' . $path);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->exec('PRAGMA foreign_keys = ON');
    return $pdo;
}
function initialize(PDO $db): void {
    $db->exec(file_get_contents(__DIR__ . '/../schema.sql'));
}
