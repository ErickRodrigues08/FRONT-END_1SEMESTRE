<?php

declare(strict_types=1);

require __DIR__ . '/bootstrap.php';
require __DIR__ . '/db.php';

require_admin();

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $q = trim((string)($_GET['q'] ?? ''));
    $grade = $_GET['grade'] ?? '';
    $class = $_GET['class'] ?? '';
    $gender = $_GET['gender'] ?? '';
    $modality = $_GET['modality'] ?? '';
    $sort = (string)($_GET['sort'] ?? 'created_at');
    $order = strtoupper((string)($_GET['order'] ?? 'DESC'));

    $sortMap = [
        'full_name' => 's.full_name',
        'age' => 's.age',
        'grade' => 's.grade',
        'class' => 's.class',
        'gender' => 's.gender',
        'created_at' => 's.created_at',
    ];
    if (!isset($sortMap[$sort])) {
        $sort = 'created_at';
    }
    if (!in_array($order, ['ASC', 'DESC'], true)) {
        $order = 'DESC';
    }
    $sortCol = $sortMap[$sort];

    $where = ['1=1'];
    $params = [];

    if ($q !== '') {
        $where[] = 's.full_name LIKE ?';
        $params[] = '%' . $q . '%';
    }
    if ($grade !== '' && $grade !== 'all') {
        $where[] = 's.grade = ?';
        $params[] = (int)$grade;
    }
    if ($class !== '' && $class !== 'all') {
        $where[] = 's.class = ?';
        $params[] = strtoupper($class);
    }
    if ($gender !== '' && $gender !== 'all') {
        $where[] = 's.gender = ?';
        $params[] = $gender;
    }
    if ($modality !== '' && $modality !== 'all') {
        $where[] = 'EXISTS (SELECT 1 FROM student_modalities smf WHERE smf.student_id = s.id AND smf.modality = ?)';
        $params[] = $modality;
    }

    $sql = 'SELECT s.id, s.full_name, s.age, s.grade, s.class, s.gender, s.created_at,
            GROUP_CONCAT(sm.modality ORDER BY sm.modality SEPARATOR ",") AS modalities
            FROM students s
            LEFT JOIN student_modalities sm ON sm.student_id = s.id
            WHERE ' . implode(' AND ', $where) . '
            GROUP BY s.id, s.full_name, s.age, s.grade, s.class, s.gender, s.created_at
            ORDER BY ' . $sortCol . ' ' . $order;

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $rows = $stmt->fetchAll();
    foreach ($rows as &$r) {
        $mods = $r['modalities'] !== null && $r['modalities'] !== ''
            ? explode(',', (string)$r['modalities'])
            : [];
        $r['modalities'] = $mods;
    }
    unset($r);
    json_out(['ok' => true, 'students' => $rows]);
}

if ($method === 'PUT') {
    $id = (int)($_GET['id'] ?? 0);
    if ($id < 1) {
        json_out(['ok' => false, 'error' => 'ID inválido.'], 422);
    }
    $body = read_json_body();
    $fullName = trim((string)($body['full_name'] ?? ''));
    $age = (int)($body['age'] ?? 0);
    $grade = (int)($body['grade'] ?? 0);
    $class = strtoupper(trim((string)($body['class'] ?? '')));
    $gender = (string)($body['gender'] ?? '');
    $modalities = $body['modalities'] ?? [];

    if ($fullName === '' || mb_strlen($fullName) < 3) {
        json_out(['ok' => false, 'error' => 'Nome inválido.'], 422);
    }
    if ($age < 10 || $age > 18) {
        json_out(['ok' => false, 'error' => 'Idade inválida.'], 422);
    }
    if (!in_array($grade, [6, 7, 8, 9], true)) {
        json_out(['ok' => false, 'error' => 'Série inválida.'], 422);
    }
    if (!in_array($class, ['A', 'B'], true)) {
        json_out(['ok' => false, 'error' => 'Turma inválida.'], 422);
    }
    if (!in_array($gender, ['masculino', 'feminino'], true)) {
        json_out(['ok' => false, 'error' => 'Gênero inválido.'], 422);
    }
    $allowed = ['handebol_feminino', 'handebol_masculino', 'volei_misto'];
    if (!is_array($modalities)) {
        json_out(['ok' => false, 'error' => 'Modalidades inválidas.'], 422);
    }
    $modalities = array_values(array_unique(array_map('strval', $modalities)));
    $modalities = array_values(array_filter($modalities, static fn ($m) => in_array($m, $allowed, true)));
    if (count($modalities) < 1 || count($modalities) > 2) {
        json_out(['ok' => false, 'error' => 'Selecione de 1 a 2 modalidades.'], 422);
    }

    try {
        $pdo->beginTransaction();
        $u = $pdo->prepare(
            'UPDATE students SET full_name=?, age=?, grade=?, class=?, gender=? WHERE id=?'
        );
        $u->execute([$fullName, $age, $grade, $class, $gender, $id]);
        if ($u->rowCount() === 0) {
            $chk = $pdo->prepare('SELECT id FROM students WHERE id=?');
            $chk->execute([$id]);
            if (!$chk->fetch()) {
                $pdo->rollBack();
                json_out(['ok' => false, 'error' => 'Aluno não encontrado.'], 404);
            }
        }
        $pdo->prepare('DELETE FROM student_modalities WHERE student_id=?')->execute([$id]);
        $insM = $pdo->prepare('INSERT INTO student_modalities (student_id, modality) VALUES (?,?)');
        foreach ($modalities as $m) {
            $insM->execute([$id, $m]);
        }
        $pdo->commit();
    } catch (Throwable $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        json_out(['ok' => false, 'error' => 'Erro ao atualizar.'], 500);
    }
    json_out(['ok' => true, 'message' => 'Cadastro atualizado.']);
}

if ($method === 'DELETE') {
    $id = (int)($_GET['id'] ?? 0);
    if ($id < 1) {
        json_out(['ok' => false, 'error' => 'ID inválido.'], 422);
    }
    $del = $pdo->prepare('DELETE FROM students WHERE id=?');
    $del->execute([$id]);
    if ($del->rowCount() === 0) {
        json_out(['ok' => false, 'error' => 'Aluno não encontrado.'], 404);
    }
    json_out(['ok' => true, 'message' => 'Aluno removido.']);
}

json_out(['ok' => false, 'error' => 'Método não permitido.'], 405);
