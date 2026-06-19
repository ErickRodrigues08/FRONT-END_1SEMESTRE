<?php
declare(strict_types=1);

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/layout.php';

$pacotes = db()->query('SELECT id, destino, descricao, duracao_dias, preco, vagas FROM pacotes WHERE ativo = 1 ORDER BY destino')->fetchAll();

renderHeader('Pacotes');
renderMessage();
?>

<h1>Pacotes de Viagem</h1>
<p>Consulte abaixo os pacotes disponíveis para reserva.</p>

<div class="cards">
  <?php foreach ($pacotes as $pacote): ?>
    <article class="card">
      <h3><?= htmlspecialchars($pacote['destino']) ?></h3>
      <p><?= htmlspecialchars($pacote['descricao']) ?></p>
      <p><strong>Duração:</strong> <?= (int) $pacote['duracao_dias'] ?> dias</p>
      <p><strong>Preço:</strong> R$ <?= number_format((float) $pacote['preco'], 2, ',', '.') ?></p>
      <p><strong>Vagas:</strong> <?= (int) $pacote['vagas'] ?></p>
    </article>
  <?php endforeach; ?>

  <?php if (!$pacotes): ?>
    <article class="card">
      <p>Nenhum pacote ativo no momento.</p>
    </article>
  <?php endif; ?>
</div>

<?php renderFooter(); ?>
