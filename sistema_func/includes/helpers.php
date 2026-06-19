<?php

function jsonResponse(array $data, int $code = 200): void
{
    http_response_code($code);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

function gerarIdFuncionario(PDO $pdo): string
{
    $stmt = $pdo->query("SELECT id FROM funcionarios WHERE id LIKE 'FUNC-%' ORDER BY id DESC LIMIT 1");
    $ultimo = $stmt->fetchColumn();

    if (!$ultimo) {
        return 'FUNC-0001';
    }

    $numero = (int) substr($ultimo, 5);
    return 'FUNC-' . str_pad((string) ($numero + 1), 4, '0', STR_PAD_LEFT);
}

function validarEmail(string $email): bool
{
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function validarCpf(string $cpf): bool
{
    $cpf = preg_replace('/\D/', '', $cpf);

    if (strlen($cpf) !== 11 || preg_match('/^(\d)\1{10}$/', $cpf)) {
        return false;
    }

    for ($t = 9; $t < 11; $t++) {
        $soma = 0;
        for ($i = 0; $i < $t; $i++) {
            $soma += (int) $cpf[$i] * (($t + 1) - $i);
        }
        $digito = ((10 * $soma) % 11) % 10;
        if ((int) $cpf[$t] !== $digito) {
            return false;
        }
    }

    return true;
}

function diasUteisMes(int $mes, int $ano): int
{
    $dias = (int) date('t', mktime(0, 0, 0, $mes, 1, $ano));
    $uteis = 0;

    for ($dia = 1; $dia <= $dias; $dia++) {
        $diaSemana = (int) date('N', mktime(0, 0, 0, $mes, $dia, $ano));
        if ($diaSemana < 6) {
            $uteis++;
        }
    }

    return max($uteis, 1);
}

function calcularDescontoFaltas(PDO $pdo, string $funcionarioId, int $mes, int $ano, float $salario): float
{
    $stmt = $pdo->prepare(
        "SELECT COUNT(*) FROM faltas
         WHERE funcionario_id = ? AND MONTH(data) = ? AND YEAR(data) = ?
         AND status IN ('pendente', 'rejeitada')"
    );
    $stmt->execute([$funcionarioId, $mes, $ano]);
    $qtdFaltas = (int) $stmt->fetchColumn();

    if ($qtdFaltas === 0) {
        return 0.0;
    }

    $diasUteis = diasUteisMes($mes, $ano);
    return round(($salario / $diasUteis) * $qtdFaltas, 2);
}

function salvarUploadAtestado(array $file): ?string
{
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return null;
    }

    if ($file['size'] > 5 * 1024 * 1024) {
        throw new RuntimeException('Arquivo muito grande. Máximo 5MB.');
    }

    $extensoes = ['pdf', 'jpg', 'jpeg', 'png'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if (!in_array($ext, $extensoes, true)) {
        throw new RuntimeException('Formato não permitido. Use PDF, JPG ou PNG.');
    }

    $dir = dirname(__DIR__) . '/uploads/atestados';
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }

    $nome = uniqid('atestado_', true) . '.' . $ext;
    $destino = $dir . '/' . $nome;

    if (!move_uploaded_file($file['tmp_name'], $destino)) {
        throw new RuntimeException('Falha ao salvar o arquivo.');
    }

    return $nome;
}

function mesNome(int $mes): string
{
    $meses = [
        1 => 'Janeiro', 2 => 'Fevereiro', 3 => 'Março', 4 => 'Abril',
        5 => 'Maio', 6 => 'Junho', 7 => 'Julho', 8 => 'Agosto',
        9 => 'Setembro', 10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro',
    ];

    return $meses[$mes] ?? '';
}
