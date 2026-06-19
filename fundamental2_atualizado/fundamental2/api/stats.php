<?php

declare(strict_types=1);

require __DIR__ . '/bootstrap.php';
require __DIR__ . '/db.php';

require_admin();

$total = (int)$pdo->query('SELECT COUNT(*) FROM students')->fetchColumn();

$byGrade = $pdo->query('SELECT grade, COUNT(*) AS c FROM students GROUP BY grade ORDER BY grade')->fetchAll();
$gradeMap = ['6' => 0, '7' => 0, '8' => 0, '9' => 0];
foreach ($byGrade as $row) {
    $gradeMap[(string)(int)$row['grade']] = (int)$row['c'];
}

$byClass = $pdo->query('SELECT class, COUNT(*) AS c FROM students GROUP BY class')->fetchAll();
$classMap = ['A' => 0, 'B' => 0];
foreach ($byClass as $row) {
    $classMap[$row['class']] = (int)$row['c'];
}

$byGender = $pdo->query('SELECT gender, COUNT(*) AS c FROM students GROUP BY gender')->fetchAll();
$genderMap = ['masculino' => 0, 'feminino' => 0];
foreach ($byGender as $row) {
    $genderMap[$row['gender']] = (int)$row['c'];
}

$byModality = $pdo->query(
    'SELECT modality, COUNT(*) AS c FROM student_modalities GROUP BY modality'
)->fetchAll();
$modMap = [
    'handebol_feminino' => 0,
    'handebol_masculino' => 0,
    'volei_misto' => 0,
];
foreach ($byModality as $row) {
    $modMap[$row['modality']] = (int)$row['c'];
}

$modGender = $pdo->query(
    'SELECT sm.modality, s.gender, COUNT(*) AS c
     FROM student_modalities sm
     INNER JOIN students s ON s.id = sm.student_id
     GROUP BY sm.modality, s.gender'
)->fetchAll();

$modGenderSeries = [];
foreach ($modGender as $row) {
    $modGenderSeries[] = [
        'modality' => $row['modality'],
        'gender' => $row['gender'],
        'count' => (int)$row['c'],
    ];
}

json_out([
    'ok' => true,
    'total' => $total,
    'by_grade' => $gradeMap,
    'by_class' => $classMap,
    'by_gender' => $genderMap,
    'by_modality' => $modMap,
    'modality_by_gender' => $modGenderSeries,
]);
