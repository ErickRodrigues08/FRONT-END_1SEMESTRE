<?php

declare(strict_types=1);

require __DIR__ . '/bootstrap.php';
require __DIR__ . '/db.php';

$method = $_SERVER['REQUEST_METHOD'];

// GET — público (sem autenticação)
if ($method === 'GET') {
    $modulo = isset($_GET['modulo']) ? (int)$_GET['modulo'] : 0;

    $where  = [];
    $params = [];

    if ($modulo === 1 || $modulo === 2) {
        $where[]  = 'modulo = ?';
        $params[] = $modulo;
    }

    $sql = 'SELECT id, modulo, modalidade, fase, jogo_seq, jogo_label, horario,
                   time1, time2, placar1, placar2, vencedor, updated_at
            FROM games';
    if ($where) {
        $sql .= ' WHERE ' . implode(' AND ', $where);
    }
    $sql .= " ORDER BY modulo, modalidade, FIELD(fase,'semifinal','final'), jogo_seq";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $rows = $stmt->fetchAll();

    // Normalizar tipos
    foreach ($rows as &$r) {
        $r['id']       = (int)$r['id'];
        $r['modulo']   = (int)$r['modulo'];
        $r['jogo_seq'] = (int)$r['jogo_seq'];
        $r['placar1']  = $r['placar1'] !== null ? (int)$r['placar1'] : null;
        $r['placar2']  = $r['placar2'] !== null ? (int)$r['placar2'] : null;
    }
    unset($r);

    json_out(['ok' => true, 'games' => $rows]);
}

// PUT — requer admin
if ($method === 'PUT') {
    require_admin();

    $id = (int)($_GET['id'] ?? 0);
    if ($id < 1) {
        json_out(['ok' => false, 'error' => 'ID inválido.'], 422);
    }

    $body = read_json_body();

    // Buscar jogo atual
    $stmt = $pdo->prepare('SELECT * FROM games WHERE id = ? LIMIT 1');
    $stmt->execute([$id]);
    $game = $stmt->fetch();
    if (!$game) {
        json_out(['ok' => false, 'error' => 'Jogo não encontrado.'], 404);
    }

    // Validar e sanitizar campos
    $time1   = array_key_exists('time1', $body)   ? (trim((string)$body['time1'])   ?: null)   : $game['time1'];
    $time2   = array_key_exists('time2', $body)   ? (trim((string)$body['time2'])   ?: null)   : $game['time2'];
    $vencedor = array_key_exists('vencedor', $body) ? (trim((string)$body['vencedor']) ?: null) : $game['vencedor'];

    $placar1 = $game['placar1'];
    if (array_key_exists('placar1', $body)) {
        $placar1 = ($body['placar1'] !== null && $body['placar1'] !== '') ? (int)$body['placar1'] : null;
    }
    $placar2 = $game['placar2'];
    if (array_key_exists('placar2', $body)) {
        $placar2 = ($body['placar2'] !== null && $body['placar2'] !== '') ? (int)$body['placar2'] : null;
    }

    if ($time1 !== null && mb_strlen($time1) > 100) {
        json_out(['ok' => false, 'error' => 'Time 1 muito longo (máx. 100 caracteres).'], 422);
    }
    if ($time2 !== null && mb_strlen($time2) > 100) {
        json_out(['ok' => false, 'error' => 'Time 2 muito longo (máx. 100 caracteres).'], 422);
    }
    if ($vencedor !== null && mb_strlen($vencedor) > 100) {
        json_out(['ok' => false, 'error' => 'Vencedor muito longo (máx. 100 caracteres).'], 422);
    }
    if ($placar1 !== null && ($placar1 < 0 || $placar1 > 255)) {
        json_out(['ok' => false, 'error' => 'Placar 1 inválido.'], 422);
    }
    if ($placar2 !== null && ($placar2 < 0 || $placar2 > 255)) {
        json_out(['ok' => false, 'error' => 'Placar 2 inválido.'], 422);
    }

    $upd = $pdo->prepare(
        'UPDATE games SET time1=?, time2=?, placar1=?, placar2=?, vencedor=? WHERE id=?'
    );
    $upd->execute([$time1, $time2, $placar1, $placar2, $vencedor, $id]);

    // Retornar jogo atualizado
    $stmt = $pdo->prepare('SELECT * FROM games WHERE id = ? LIMIT 1');
    $stmt->execute([$id]);
    $updated = $stmt->fetch();
    $updated['id']       = (int)$updated['id'];
    $updated['modulo']   = (int)$updated['modulo'];
    $updated['jogo_seq'] = (int)$updated['jogo_seq'];
    $updated['placar1']  = $updated['placar1'] !== null ? (int)$updated['placar1'] : null;
    $updated['placar2']  = $updated['placar2'] !== null ? (int)$updated['placar2'] : null;

    json_out(['ok' => true, 'game' => $updated]);
}

json_out(['ok' => false, 'error' => 'Método não permitido.'], 405);
