<?php

require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../config/database.php';

$action = $_GET['action'] ?? '';
$pdo = getConnection();

if ($action === 'listar_funcionario' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $funcionarioId = requireFuncionario();

    $stmt = $pdo->prepare(
        'SELECT * FROM servicos WHERE funcionario_id = ? ORDER BY data_hora_solicitado DESC'
    );
    $stmt->execute([$funcionarioId]);

    jsonResponse(['success' => true, 'servicos' => $stmt->fetchAll()]);
}

if ($action === 'atualizar_funcionario' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $funcionarioId = requireFuncionario();
    $data = json_decode(file_get_contents('php://input'), true) ?? [];

    $id = (int) ($data['id'] ?? 0);
    $horaExecucao = $data['hora_execucao'] ?? null;
    $emAndamento = !empty($data['em_andamento']) ? 1 : 0;
    $dataTermino = $data['data_termino'] ?? null;

    $stmt = $pdo->prepare(
        'UPDATE servicos SET hora_execucao = ?, em_andamento = ?, data_termino = ?
         WHERE id = ? AND funcionario_id = ?'
    );
    $stmt->execute([$horaExecucao ?: null, $emAndamento, $dataTermino ?: null, $id, $funcionarioId]);

    jsonResponse(['success' => true, 'message' => 'Serviço atualizado.']);
}

if ($action === 'listar_admin' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    requireAdmin();

    $stmt = $pdo->query(
        'SELECT s.*, f.nome AS funcionario_nome
         FROM servicos s
         INNER JOIN funcionarios f ON f.id = s.funcionario_id
         ORDER BY s.data_hora_solicitado DESC'
    );

    jsonResponse(['success' => true, 'servicos' => $stmt->fetchAll()]);
}

if ($action === 'criar' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    requireAdmin();
    $data = json_decode(file_get_contents('php://input'), true) ?? [];

    $funcionarioId = trim($data['funcionario_id'] ?? '');
    $nome = trim($data['nome'] ?? '');
    $descricao = trim($data['descricao'] ?? '');
    $dataHora = $data['data_hora_solicitado'] ?? '';

    if ($funcionarioId === '' || $nome === '' || $descricao === '' || $dataHora === '') {
        jsonResponse(['success' => false, 'message' => 'Preencha todos os campos.'], 400);
    }

    $stmt = $pdo->prepare(
        'INSERT INTO servicos (funcionario_id, nome, descricao, data_hora_solicitado)
         VALUES (?, ?, ?, ?)'
    );
    $stmt->execute([$funcionarioId, $nome, $descricao, $dataHora]);

    jsonResponse(['success' => true, 'message' => 'Serviço enviado com sucesso.']);
}

jsonResponse(['success' => false, 'message' => 'Ação inválida.'], 400);
