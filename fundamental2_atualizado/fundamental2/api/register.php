<?php

declare(strict_types=1);

require __DIR__ . '/bootstrap.php';
require __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_out(['ok' => false, 'error' => 'Método não permitido.'], 405);
}

$body = read_json_body();
$fullName = trim((string)($body['full_name'] ?? ''));
$age = (int)($body['age'] ?? 0);
$grade = (int)($body['grade'] ?? 0);
$class = strtoupper(trim((string)($body['class'] ?? '')));
$gender = (string)($body['gender'] ?? '');
$modalities = $body['modalities'] ?? [];

if ($fullName === '' || mb_strlen($fullName) < 3) {
    json_out(['ok' => false, 'error' => 'Informe o nome completo (mínimo 3 caracteres).'], 422);
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
    $stmt = $pdo->prepare(
        'INSERT INTO students (full_name, age, grade, class, gender) VALUES (?,?,?,?,?)'
    );
    $stmt->execute([$fullName, $age, $grade, $class, $gender]);
    $studentId = (int)$pdo->lastInsertId();

    $insM = $pdo->prepare('INSERT INTO student_modalities (student_id, modality) VALUES (?,?)');
    foreach ($modalities as $m) {
        $insM->execute([$studentId, $m]);
    }
    $pdo->commit();
} catch (Throwable $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    json_out(['ok' => false, 'error' => 'Não foi possível salvar o cadastro.'], 500);
}

json_out(['ok' => true, 'message' => 'Inscrição realizada com sucesso!', 'id' => $studentId]);
