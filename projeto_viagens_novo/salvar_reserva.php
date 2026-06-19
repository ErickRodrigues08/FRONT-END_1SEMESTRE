<?php
declare(strict_types=1);

require_once __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirectWithMessage('reservas.php', 'Método inválido.', 'error');
}

$clienteId = (int) ($_POST['cliente_id'] ?? 0);
$pacoteId = (int) ($_POST['pacote_id'] ?? 0);
$dataViagem = trim((string) ($_POST['data_viagem'] ?? ''));
$quantidadePessoas = (int) ($_POST['quantidade_pessoas'] ?? 0);
$observacoes = trim((string) ($_POST['observacoes'] ?? ''));

if ($clienteId <= 0 || $pacoteId <= 0 || $dataViagem === '' || $quantidadePessoas <= 0) {
    redirectWithMessage('reservas.php', 'Preencha todos os campos obrigatórios da reserva.', 'error');
}

if ($dataViagem < date('Y-m-d')) {
    redirectWithMessage('reservas.php', 'A data da viagem deve ser futura.', 'error');
}

$pdo = db();

$stmtCliente = $pdo->prepare('SELECT COUNT(*) FROM clientes WHERE id = :id');
$stmtCliente->execute(['id' => $clienteId]);
if ((int) $stmtCliente->fetchColumn() === 0) {
    redirectWithMessage('reservas.php', 'Cliente não encontrado.', 'error');
}

$stmtPacote = $pdo->prepare('SELECT vagas FROM pacotes WHERE id = :id AND ativo = 1');
$stmtPacote->execute(['id' => $pacoteId]);
$pacote = $stmtPacote->fetch();

if (!$pacote) {
    redirectWithMessage('reservas.php', 'Pacote inválido ou inativo.', 'error');
}

$vagasDisponiveis = (int) $pacote['vagas'];
if ($quantidadePessoas > $vagasDisponiveis) {
    redirectWithMessage('reservas.php', 'Quantidade de pessoas excede as vagas disponíveis.', 'error');
}

try {
    $pdo->beginTransaction();

    $sqlReserva = '
      INSERT INTO reservas (cliente_id, pacote_id, data_viagem, quantidade_pessoas, observacoes)
      VALUES (:cliente_id, :pacote_id, :data_viagem, :quantidade_pessoas, :observacoes)
    ';
    $stmtReserva = $pdo->prepare($sqlReserva);
    $stmtReserva->execute([
        'cliente_id' => $clienteId,
        'pacote_id' => $pacoteId,
        'data_viagem' => $dataViagem,
        'quantidade_pessoas' => $quantidadePessoas,
        'observacoes' => $observacoes !== '' ? $observacoes : null,
    ]);

    $stmtUpdatePacote = $pdo->prepare('UPDATE pacotes SET vagas = vagas - :qtd WHERE id = :id');
    $stmtUpdatePacote->execute([
        'qtd' => $quantidadePessoas,
        'id' => $pacoteId,
    ]);

    $pdo->commit();
} catch (Throwable $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    redirectWithMessage('reservas.php', 'Não foi possível concluir a reserva.', 'error');
}

redirectWithMessage('reservas.php', 'Reserva realizada com sucesso.');
