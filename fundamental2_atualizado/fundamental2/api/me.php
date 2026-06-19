<?php

declare(strict_types=1);

require __DIR__ . '/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    json_out(['ok' => false, 'error' => 'Método não permitido.'], 405);
}

if (empty($_SESSION['admin_id'])) {
    json_out(['ok' => false, 'authenticated' => false], 200);
}

json_out([
    'ok' => true,
    'authenticated' => true,
    'user' => ['username' => $_SESSION['admin_username'] ?? ''],
]);
