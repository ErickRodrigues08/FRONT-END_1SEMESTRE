<?php

require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../config/database.php';

$action = $_GET['action'] ?? '';
$pdo = getConnection();
$funcionarioId = requireFuncionario();

if ($action === 'calendario' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $mes = (int) ($_GET['mes'] ?? date('n'));
    $ano = (int) ($_GET['ano'] ?? date('Y'));

    $stmt = $pdo->prepare(
        'SELECT data, hora_entrada, hora_saida FROM registro_ponto
         WHERE funcionario_id = ? AND MONTH(data) = ? AND YEAR(data) = ?'
    );
    $stmt->execute([$funcionarioId, $mes, $ano]);
    $pontos = [];
    foreach ($stmt->fetchAll() as $row) {
        $pontos[$row['data']] = $row;
    }

    $stmt = $pdo->prepare(
        'SELECT data, titulo, status FROM faltas
         WHERE funcionario_id = ? AND MONTH(data) = ? AND YEAR(data) = ?'
    );
    $stmt->execute([$funcionarioId, $mes, $ano]);
    $faltas = [];
    foreach ($stmt->fetchAll() as $row) {
        $faltas[$row['data']] = $row;
    }

    jsonResponse([
        'success' => true,
        'mes' => $mes,
        'ano' => $ano,
        'pontos' => $pontos,
        'faltas' => $faltas,
    ]);
}

if ($action === 'salvar' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true) ?? [];
    $dataPonto = $data['data'] ?? '';
    $horaEntrada = $data['hora_entrada'] ?? '';
    $horaSaida = $data['hora_saida'] ?? '';

    if ($dataPonto === '' || $horaEntrada === '') {
        jsonResponse(['success' => false, 'message' => 'Informe data e hora de entrada.'], 400);
    }

    $stmt = $pdo->prepare(
        'INSERT INTO registro_ponto (funcionario_id, data, hora_entrada, hora_saida)
         VALUES (?, ?, ?, ?)
         ON DUPLICATE KEY UPDATE hora_entrada = VALUES(hora_entrada), hora_saida = VALUES(hora_saida)'
    );
    $stmt->execute([$funcionarioId, $dataPonto, $horaEntrada, $horaSaida ?: null]);

    jsonResponse(['success' => true, 'message' => 'Ponto registrado com sucesso.']);
}

if ($action === 'relatorio' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $mes = (int) ($_GET['mes'] ?? 0);
    $ano = (int) ($_GET['ano'] ?? 0);

    $sql = 'SELECT data, hora_entrada, hora_saida FROM registro_ponto WHERE funcionario_id = ?';
    $params = [$funcionarioId];

    if ($mes > 0 && $ano > 0) {
        $sql .= ' AND MONTH(data) = ? AND YEAR(data) = ?';
        $params[] = $mes;
        $params[] = $ano;
    }

    $sql .= ' ORDER BY data DESC';
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    jsonResponse(['success' => true, 'registros' => $stmt->fetchAll()]);
}

jsonResponse(['success' => false, 'message' => 'Ação inválida.'], 400);
