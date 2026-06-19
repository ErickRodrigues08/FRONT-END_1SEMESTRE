<?php
declare(strict_types=1);

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/layout.php';

$clientes = db()->query('SELECT id, nome, email, telefone, documento, criado_em FROM clientes ORDER BY id DESC')->fetchAll();

renderHeader('Clientes');
renderMessage();
?>

<h1>Cadastro de Clientes</h1>

<div class="two-cols">
  <section>
    <form action="salvar_cliente.php" method="post">
      <label for="nome">Nome completo *</label>
      <input type="text" id="nome" name="nome" required maxlength="120">

      <label for="email">E-mail *</label>
      <input type="email" id="email" name="email" required maxlength="120">

      <label for="telefone">Telefone *</label>
      <input type="text" id="telefone" name="telefone" required maxlength="30">

      <label for="documento">Documento (CPF/RG) *</label>
      <input type="text" id="documento" name="documento" required maxlength="30">

      <button type="submit">Salvar cliente</button>
    </form>
  </section>

  <section>
    <table>
      <thead>
        <tr>
          <th>Nome</th>
          <th>E-mail</th>
          <th>Telefone</th>
          <th>Documento</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($clientes as $cliente): ?>
          <tr>
            <td><?= htmlspecialchars($cliente['nome']) ?></td>
            <td><?= htmlspecialchars($cliente['email']) ?></td>
            <td><?= htmlspecialchars($cliente['telefone']) ?></td>
            <td><?= htmlspecialchars($cliente['documento']) ?></td>
          </tr>
        <?php endforeach; ?>
        <?php if (!$clientes): ?>
          <tr>
            <td colspan="4">Nenhum cliente cadastrado até o momento.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </section>
</div>

<?php renderFooter(); ?>
