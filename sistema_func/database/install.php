<?php

require_once __DIR__ . '/../config/database.php';

try {
    $sql = file_get_contents(__DIR__ . '/schema.sql');
    $pdo = new PDO('mysql:host=' . DB_HOST . ';charset=utf8mb4', DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);

    foreach (array_filter(array_map('trim', explode(';', $sql))) as $statement) {
        if ($statement !== '') {
            $pdo->exec($statement);
        }
    }

    $pdo = getConnection();
    $hash = password_hash('admin123', PASSWORD_DEFAULT);
    $stmt = $pdo->prepare('INSERT INTO admins (usuario, senha_hash) VALUES (?, ?) ON DUPLICATE KEY UPDATE senha_hash = VALUES(senha_hash)');
    $stmt->execute(['admin', $hash]);

    echo 'Banco instalado com sucesso! Admin: admin / admin123';
} catch (Throwable $e) {
    http_response_code(500);
    echo 'Erro na instalação: ' . $e->getMessage();
}
