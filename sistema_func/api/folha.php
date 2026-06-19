<?php

require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../config/database.php';

$action = $_GET['action'] ?? '';
$pdo = getConnection();

function montarFolha(array $folha): array
{
    $liquido = (float) $folha['salario']
        - (float) $folha['descontos_manuais']
        - (float) $folha['desconto_faltas']
        + (float) $folha['vale_transporte']
        + (float) $folha['vale_alimentacao'];

    $folha['salario_liquido'] = round($liquido, 2);
    $folha['recebido_texto'] = (int) $folha['recebido'] === 1 ? 'Sim' : 'Não';
    $folha['mes_nome'] = mesNome((int) $folha['mes']);

    return $folha;
}

if ($action === 'minha_folha' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $funcionarioId = requireFuncionario();
    $mes = (int) ($_GET['mes'] ?? date('n'));
    $ano = (int) ($_GET['ano'] ?? date('Y'));

    $stmt = $pdo->prepare(
        'SELECT * FROM folha_pagamento WHERE funcionario_id = ? AND mes = ? AND ano = ?'
    );
    $stmt->execute([$funcionarioId, $mes, $ano]);
    $folha = $stmt->fetch();

    if (!$folha) {
        jsonResponse([
            'success' => true,
            'folha' => null,
            'message' => 'Folha ainda não disponível para este período.',
        ]);
    }

    jsonResponse(['success' => true, 'folha' => montarFolha($folha)]);
}

if ($action === 'relatorio' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $funcionarioId = requireFuncionario();

    $stmt = $pdo->prepare(
        'SELECT * FROM folha_pagamento WHERE funcionario_id = ? ORDER BY ano DESC, mes DESC'
    );
    $stmt->execute([$funcionarioId]);
    $folhas = array_map('montarFolha', $stmt->fetchAll());

    jsonResponse(['success' => true, 'folhas' => $folhas]);
}

if ($action === 'salvar' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    requireAdmin();
    $data = json_decode(file_get_contents('php://input'), true) ?? [];

    $funcionarioId = trim($data['funcionario_id'] ?? '');
    $mes = (int) ($data['mes'] ?? 0);
    $ano = (int) ($data['ano'] ?? 0);
    $salario = (float) ($data['salario'] ?? 0);
    $recebido = !empty($data['recebido']) ? 1 : 0;
    $descontos = (float) ($data['descontos_manuais'] ?? 0);
    $vt = (float) ($data['vale_transporte'] ?? 0);
    $va = (float) ($data['vale_alimentacao'] ?? 0);

    if ($funcionarioId === '' || $mes < 1 || $mes > 12 || $ano < 2000) {
        jsonResponse(['success' => false, 'message' => 'Dados inválidos.'], 400);
    }

    $descontoFaltas = calcularDescontoFaltas($pdo, $funcionarioId, $mes, $ano, $salario);

    $stmt = $pdo->prepare(
        'INSERT INTO folha_pagamento
         (funcionario_id, mes, ano, salario, recebido, descontos_manuais, vale_transporte, vale_alimentacao, desconto_faltas)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
         ON DUPLICATE KEY UPDATE
         salario = VALUES(salario), recebido = VALUES(recebido),
         descontos_manuais = VALUES(descontos_manuais),
         vale_transporte = VALUES(vale_transporte),
         vale_alimentacao = VALUES(vale_alimentacao),
         desconto_faltas = VALUES(desconto_faltas)'
    );
    $stmt->execute([$funcionarioId, $mes, $ano, $salario, $recebido, $descontos, $vt, $va, $descontoFaltas]);

    jsonResponse([
        'success' => true,
        'message' => 'Folha salva com sucesso.',
        'desconto_faltas' => $descontoFaltas,
    ]);
}

if ($action === 'buscar' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    requireAdmin();
    $funcionarioId = $_GET['funcionario_id'] ?? '';
    $mes = (int) ($_GET['mes'] ?? date('n'));
    $ano = (int) ($_GET['ano'] ?? date('Y'));

    $stmt = $pdo->prepare(
        'SELECT * FROM folha_pagamento WHERE funcionario_id = ? AND mes = ? AND ano = ?'
    );
    $stmt->execute([$funcionarioId, $mes, $ano]);
    $folha = $stmt->fetch();

    if ($folha) {
        $folha = montarFolha($folha);
    }

    jsonResponse(['success' => true, 'folha' => $folha]);
}

jsonResponse(['success' => false, 'message' => 'Ação inválida.'], 400);
