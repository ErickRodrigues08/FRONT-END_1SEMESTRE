<?php

declare(strict_types=1);

require __DIR__ . '/bootstrap.php';
require __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_out(['ok' => false, 'error' => 'Método não permitido.'], 405);
}

$body = read_json_body();
$user = trim((string)($body['username'] ?? ''));
$pass = (string)($body['password'] ?? '');

if ($user === '' || $pass === '') {
    json_out(['ok' => false, 'error' => 'Informe usuário e senha.'], 422);
}

$stmt = $pdo->prepare('SELECT id, username, password_hash FROM admins WHERE username = ? LIMIT 1');
$stmt->execute([$user]);
$row = $stmt->fetch();
if (!$row || !password_verify($pass, $row['password_hash'])) {
    json_out(['ok' => false, 'error' => 'Usuário ou senha incorretos.'], 401);
}

session_regenerate_id(true);
$_SESSION['admin_id'] = (int)$row['id'];
$_SESSION['admin_username'] = $row['username'];

json_out(['ok' => true, 'user' => ['username' => $row['username']]]);
