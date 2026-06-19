<?php

declare(strict_types=1);

/**
 * Execute UMA VEZ no navegador após importar schema.sql e configurar config.php
 * Ex.: http://localhost/fundamental2/api/setup.php
 * Cria o usuário admin (senha padrão: admin123) se ainda não existir.
 * Remova ou renomeie este arquivo após o uso em produção.
 */

require __DIR__ . '/db.php';

header('Content-Type: text/plain; charset=utf-8');

$username = 'admin';
$plain = 'admin123';

$stmt = $pdo->prepare('SELECT id FROM admins WHERE username = ? LIMIT 1');
$stmt->execute([$username]);
if ($stmt->fetch()) {
    echo "Admin já existe. Nada a fazer.\n";
    exit;
}

$hash = password_hash($plain, PASSWORD_DEFAULT);
$ins = $pdo->prepare('INSERT INTO admins (username, password_hash) VALUES (?, ?)');
$ins->execute([$username, $hash]);

echo "Usuário criado: {$username}\nSenha inicial: {$plain}\nAltere a senha após o primeiro acesso.\n";
echo "\nPor segurança, remova ou bloqueie o acesso a api/setup.php em produção.\n";
