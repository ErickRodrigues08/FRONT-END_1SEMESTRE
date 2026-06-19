<?php
declare(strict_types=1);

require_once __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirectWithMessage('clientes.php', 'Método inválido.', 'error');
}

$nome = trim((string) ($_POST['nome'] ?? ''));
$email = trim((string) ($_POST['email'] ?? ''));
$telefone = trim((string) ($_POST['telefone'] ?? ''));
$documento = trim((string) ($_POST['documento'] ?? ''));

if ($nome === '' || $email === '' || $telefone === '' || $documento === '') {
    redirectWithMessage('clientes.php', 'Preencha todos os campos obrigatórios.', 'error');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    redirectWithMessage('clientes.php', 'Informe um e-mail válido.', 'error');
}

try {
    $sql = 'INSERT INTO clientes (nome, email, telefone, documento) VALUES (:nome, :email, :telefone, :documento)';
    $stmt = db()->prepare($sql);
    $stmt->execute([
        'nome' => $nome,
        'email' => $email,
        'telefone' => $telefone,
        'documento' => $documento,
    ]);
} catch (PDOException $e) {
    $message = str_contains($e->getMessage(), 'Duplicate') || str_contains($e->getMessage(), 'duplicate')
        ? 'E-mail ou documento já cadastrado.'
        : 'Erro ao cadastrar cliente.';
    redirectWithMessage('clientes.php', $message, 'error');
}

redirectWithMessage('clientes.php', 'Cliente cadastrado com sucesso.');
