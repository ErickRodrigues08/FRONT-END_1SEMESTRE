<?php
$tituloPagina = 'Dashboard';
$extraScripts = ['https://cdn.jsdelivr.net/npm/chart.js', '../assets/js/charts.js'];
require_once __DIR__ . '/../includes/header_admin.php';
?>

<section class="page-header">
    <h1>Dashboard</h1>
    <p>Visão geral de funcionários, serviços e presença.</p>
</section>

<div class="cards-grid" id="dashboardCards">
    <div class="stat-card"><h3>Funcionários</h3><p id="cardFuncionarios">0</p></div>
    <div class="stat-card"><h3>Presenças Hoje</h3><p id="cardPresencas">0</p></div>
    <div class="stat-card"><h3>Faltas no Mês</h3><p id="cardFaltas">0</p></div>
    <div class="stat-card"><h3>Mensagens Não Lidas</h3><p id="cardMensagens">0</p></div>
</div>

<div class="charts-grid">
    <div class="chart-card">
        <h3>Presença por Funcionário (mês atual)</h3>
        <canvas id="chartPresenca"></canvas>
    </div>
    <div class="chart-card">
        <h3>Status dos Serviços</h3>
        <canvas id="chartServicos"></canvas>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer_admin.php'; ?>
