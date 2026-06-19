<?php

require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../config/database.php';

requireAdmin();

$pdo = getConnection();
$mes = (int) date('n');
$ano = (int) date('Y');
$hoje = date('Y-m-d');

$totalFuncionarios = (int) $pdo->query('SELECT COUNT(*) FROM funcionarios')->fetchColumn();

$stmt = $pdo->prepare('SELECT COUNT(*) FROM registro_ponto WHERE data = ?');
$stmt->execute([$hoje]);
$presencasHoje = (int) $stmt->fetchColumn();

$stmt = $pdo->prepare(
    'SELECT COUNT(*) FROM faltas WHERE MONTH(data) = ? AND YEAR(data) = ?'
);
$stmt->execute([$mes, $ano]);
$totalFaltasMes = (int) $stmt->fetchColumn();

$stmt = $pdo->query(
    "SELECT
        SUM(CASE WHEN em_andamento = 1 THEN 1 ELSE 0 END) AS em_andamento,
        SUM(CASE WHEN data_termino IS NOT NULL THEN 1 ELSE 0 END) AS concluidos,
        SUM(CASE WHEN em_andamento = 0 AND data_termino IS NULL THEN 1 ELSE 0 END) AS pendentes
     FROM servicos"
);
$servicos = $stmt->fetch() ?: ['em_andamento' => 0, 'concluidos' => 0, 'pendentes' => 0];

$stmt = $pdo->query(
    "SELECT COUNT(*) FROM mensagens_chat WHERE remetente = 'funcionario' AND lida = 0"
);
$mensagensNaoLidas = (int) $stmt->fetchColumn();

$stmt = $pdo->prepare(
    'SELECT f.nome, COUNT(r.id) AS presencas
     FROM funcionarios f
     LEFT JOIN registro_ponto r ON r.funcionario_id = f.id AND MONTH(r.data) = ? AND YEAR(r.data) = ?
     GROUP BY f.id, f.nome
     ORDER BY presencas DESC
     LIMIT 8'
);
$stmt->execute([$mes, $ano]);
$presencaPorFuncionario = $stmt->fetchAll();

jsonResponse([
    'success' => true,
    'cards' => [
        'total_funcionarios' => $totalFuncionarios,
        'presencas_hoje' => $presencasHoje,
        'faltas_mes' => $totalFaltasMes,
        'mensagens_nao_lidas' => $mensagensNaoLidas,
    ],
    'servicos' => [
        'em_andamento' => (int) $servicos['em_andamento'],
        'concluidos' => (int) $servicos['concluidos'],
        'pendentes' => (int) $servicos['pendentes'],
    ],
    'presenca_por_funcionario' => $presencaPorFuncionario,
]);
