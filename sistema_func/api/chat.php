<?php

require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../config/database.php';

$action = $_GET['action'] ?? '';
$pdo = getConnection();

if ($action === 'enviar' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true) ?? [];
    $mensagem = trim($data['mensagem'] ?? '');

    if ($mensagem === '') {
        jsonResponse(['success' => false, 'message' => 'Mensagem vazia.'], 400);
    }

    if (isFuncionarioLogged()) {
        $funcionarioId = requireFuncionario();
        $remetente = 'funcionario';
    } elseif (isAdminLogged()) {
        requireAdmin();
        $funcionarioId = trim($data['funcionario_id'] ?? '');
        $remetente = 'admin';

        if ($funcionarioId === '') {
            jsonResponse(['success' => false, 'message' => 'Funcionário não informado.'], 400);
        }
    } else {
        jsonResponse(['success' => false, 'message' => 'Não autenticado.'], 401);
    }

    $stmt = $pdo->prepare(
        'INSERT INTO mensagens_chat (funcionario_id, remetente, mensagem) VALUES (?, ?, ?)'
    );
    $stmt->execute([$funcionarioId, $remetente, $mensagem]);

    jsonResponse(['success' => true, 'message' => 'Mensagem enviada.']);
}

if ($action === 'listar' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isFuncionarioLogged()) {
        $funcionarioId = requireFuncionario();

        $stmt = $pdo->prepare(
            'SELECT id, remetente, mensagem, lida, created_at
             FROM mensagens_chat WHERE funcionario_id = ? ORDER BY created_at ASC'
        );
        $stmt->execute([$funcionarioId]);

        $pdo->prepare(
            'UPDATE mensagens_chat SET lida = 1 WHERE funcionario_id = ? AND remetente = ?'
        )->execute([$funcionarioId, 'admin']);

        jsonResponse(['success' => true, 'mensagens' => $stmt->fetchAll()]);
    }

    if (isAdminLogged()) {
        requireAdmin();
        $funcionarioId = $_GET['funcionario_id'] ?? '';

        if ($funcionarioId !== '') {
            $stmt = $pdo->prepare(
                'SELECT id, remetente, mensagem, lida, created_at
                 FROM mensagens_chat WHERE funcionario_id = ? ORDER BY created_at ASC'
            );
            $stmt->execute([$funcionarioId]);

            $pdo->prepare(
                'UPDATE mensagens_chat SET lida = 1 WHERE funcionario_id = ? AND remetente = ?'
            )->execute([$funcionarioId, 'funcionario']);

            jsonResponse(['success' => true, 'mensagens' => $stmt->fetchAll()]);
        }

        $stmt = $pdo->prepare(
            'SELECT f.id, f.nome,
                    (SELECT mensagem FROM mensagens_chat m WHERE m.funcionario_id = f.id ORDER BY created_at DESC LIMIT 1) AS ultima_mensagem,
                    (SELECT created_at FROM mensagens_chat m WHERE m.funcionario_id = f.id ORDER BY created_at DESC LIMIT 1) AS ultima_data,
                    (SELECT COUNT(*) FROM mensagens_chat m WHERE m.funcionario_id = f.id AND m.remetente = ? AND m.lida = 0) AS nao_lidas
             FROM funcionarios f
             HAVING ultima_mensagem IS NOT NULL
             ORDER BY ultima_data DESC'
        );
        $stmt->execute(['funcionario']);
        $conversas = $stmt->fetchAll();

        jsonResponse(['success' => true, 'conversas' => $conversas]);
    }

    jsonResponse(['success' => false, 'message' => 'Não autenticado.'], 401);
}

jsonResponse(['success' => false, 'message' => 'Ação inválida.'], 400);
