<?php

require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../config/database.php';

$action = $_GET['action'] ?? '';
$pdo = getConnection();

if ($action === 'listar' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    requireAdmin();

    $stmt = $pdo->query('SELECT id, nome, cpf, email, cep, telefone, idade, created_at FROM funcionarios ORDER BY nome');
    jsonResponse(['success' => true, 'funcionarios' => $stmt->fetchAll()]);
}

if ($action === 'meu_perfil' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $funcionarioId = requireFuncionario();

    $stmt = $pdo->prepare('SELECT id, nome, cpf, email, cep, telefone, idade FROM funcionarios WHERE id = ?');
    $stmt->execute([$funcionarioId]);
    $funcionario = $stmt->fetch();

    jsonResponse(['success' => true, 'funcionario' => $funcionario]);
}

jsonResponse(['success' => false, 'message' => 'Ação inválida.'], 400);
