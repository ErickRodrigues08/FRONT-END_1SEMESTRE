<?php
declare(strict_types=1);

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/layout.php';

$totalClientes = (int) db()->query('SELECT COUNT(*) FROM clientes')->fetchColumn();
$totalPacotes = (int) db()->query('SELECT COUNT(*) FROM pacotes WHERE ativo = 1')->fetchColumn();
$totalReservas = (int) db()->query('SELECT COUNT(*) FROM reservas')->fetchColumn();

renderHeader('Painel');
renderMessage();
?>

<h1>Gestão da Agência</h1>
<p>Controle clientes, pacotes e reservas em um único sistema.</p>

<section class="cards">
  <article class="card">
    <h3>Clientes cadastrados</h3>
    <p><strong><?= $totalClientes ?></strong></p>
  </article>
  <article class="card">
    <h3>Pacotes ativos</h3>
    <p><strong><?= $totalPacotes ?></strong></p>
  </article>
  <article class="card">
    <h3>Reservas realizadas</h3>
    <p><strong><?= $totalReservas ?></strong></p>
  </article>
</section>

<section class="cards">
  <article class="card">
    <h3>Cadastro de clientes</h3>
    <p>Registre clientes e mantenha dados de contato acessíveis.</p>
    <a href="clientes.php">Acessar módulo</a>
  </article>
  <article class="card">
    <h3>Pacotes de viagem</h3>
    <p>Visualize destinos, preço, duração e vagas disponíveis.</p>
    <a href="pacotes.php">Ver pacotes</a>
  </article>
  <article class="card">
    <h3>Reservas</h3>
    <p>Realize reservas com validação e histórico completo.</p>
    <a href="reservas.php">Gerenciar reservas</a>
  </article>
</section>

<?php renderFooter(); ?>
