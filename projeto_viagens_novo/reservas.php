<?php
declare(strict_types=1);

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/layout.php';

$clientes = db()->query('SELECT id, nome, email FROM clientes ORDER BY nome')->fetchAll();
$pacotes = db()->query('SELECT id, destino, preco, vagas FROM pacotes WHERE ativo = 1 ORDER BY destino')->fetchAll();

$sqlReservas = '
    SELECT
      r.id,
      c.nome AS cliente,
      p.destino,
      r.data_viagem,
      r.quantidade_pessoas,
      p.preco,
      r.criado_em
    FROM reservas r
    INNER JOIN clientes c ON c.id = r.cliente_id
    INNER JOIN pacotes p ON p.id = r.pacote_id
    ORDER BY r.id DESC
';
$reservas = db()->query($sqlReservas)->fetchAll();

renderHeader('Reservas');
renderMessage();
?>

<h1>Reservas de Pacotes</h1>

<div class="two-cols">
  <section>
    <form action="salvar_reserva.php" method="post">
      <label for="cliente_id">Cliente *</label>
      <select id="cliente_id" name="cliente_id" required>
        <option value="">Selecione um cliente</option>
        <?php foreach ($clientes as $cliente): ?>
          <option value="<?= (int) $cliente['id'] ?>">
            <?= htmlspecialchars($cliente['nome']) ?> (<?= htmlspecialchars($cliente['email']) ?>)
          </option>
        <?php endforeach; ?>
      </select>

      <label for="pacote_id">Pacote *</label>
      <select id="pacote_id" name="pacote_id" required>
        <option value="">Selecione um pacote</option>
        <?php foreach ($pacotes as $pacote): ?>
          <option value="<?= (int) $pacote['id'] ?>">
            <?= htmlspecialchars($pacote['destino']) ?> - R$ <?= number_format((float) $pacote['preco'], 2, ',', '.') ?>
          </option>
        <?php endforeach; ?>
      </select>

      <label for="data_viagem">Data da viagem *</label>
      <input type="date" id="data_viagem" name="data_viagem" required min="<?= date('Y-m-d') ?>">

      <label for="quantidade_pessoas">Quantidade de pessoas *</label>
      <input type="number" id="quantidade_pessoas" name="quantidade_pessoas" required min="1" max="10" value="1">

      <label for="observacoes">Observações</label>
      <textarea id="observacoes" name="observacoes" rows="4" maxlength="500"></textarea>

      <button type="submit">Confirmar reserva</button>
    </form>
  </section>

  <section>
    <table>
      <thead>
        <tr>
          <th>Cliente</th>
          <th>Destino</th>
          <th>Viagem</th>
          <th>Pessoas</th>
          <th>Total</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($reservas as $reserva): ?>
          <?php $total = (float) $reserva['preco'] * (int) $reserva['quantidade_pessoas']; ?>
          <tr>
            <td><?= htmlspecialchars($reserva['cliente']) ?></td>
            <td><?= htmlspecialchars($reserva['destino']) ?></td>
            <td><?= date('d/m/Y', strtotime($reserva['data_viagem'])) ?></td>
            <td><?= (int) $reserva['quantidade_pessoas'] ?></td>
            <td>R$ <?= number_format($total, 2, ',', '.') ?></td>
          </tr>
        <?php endforeach; ?>
        <?php if (!$reservas): ?>
          <tr>
            <td colspan="5">Nenhuma reserva registrada.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </section>
</div>

<?php renderFooter(); ?>
