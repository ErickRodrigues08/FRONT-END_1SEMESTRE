<?php

require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../config/database.php';

$action = $_GET['action'] ?? '';
$pdo = getConnection();
$funcionarioId = requireFuncionario();

if ($action === 'salvar' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = $_POST['data'] ?? '';
    $titulo = trim($_POST['titulo'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');

    if ($data === '' || $titulo === '' || $descricao === '') {
        jsonResponse(['success' => false, 'message' => 'Preencha todos os campos obrigatórios.'], 400);
    }

    $anexo = null;
    if (!empty($_FILES['anexo']) && $_FILES['anexo']['error'] !== UPLOAD_ERR_NO_FILE) {
        try {
            $anexo = salvarUploadAtestado($_FILES['anexo']);
        } catch (RuntimeException $e) {
            jsonResponse(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    $stmt = $pdo->prepare(
        'INSERT INTO faltas (funcionario_id, data, titulo, descricao, anexo)
         VALUES (?, ?, ?, ?, ?)'
    );
    $stmt->execute([$funcionarioId, $data, $titulo, $descricao, $anexo]);

    jsonResponse(['success' => true, 'message' => 'Falta registrada com sucesso.']);
}

if ($action === 'listar' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $pdo->prepare(
        'SELECT id, data, titulo, descricao, anexo, status, created_at
         FROM faltas WHERE funcionario_id = ? ORDER BY data DESC'
    );
    $stmt->execute([$funcionarioId]);

    jsonResponse(['success' => true, 'faltas' => $stmt->fetchAll()]);
}

jsonResponse(['success' => false, 'message' => 'Ação inválida.'], 400);
