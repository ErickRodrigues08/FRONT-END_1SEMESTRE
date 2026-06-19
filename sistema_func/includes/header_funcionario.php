<?php
require_once __DIR__ . '/session.php';
redirectIfNotFuncionario();
$nomeFuncionario = $_SESSION['funcionario_nome'] ?? 'Funcionário';
$paginaAtual = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($tituloPagina ?? 'Funcionário') ?> - Sistema</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="app-layout">
    <aside class="sidebar">
        <div class="sidebar-brand">
            <h2>Funcionário</h2>
            <p><?= htmlspecialchars($nomeFuncionario) ?></p>
        </div>
        <nav class="sidebar-nav">
            <a href="home.php" class="<?= $paginaAtual === 'home.php' ? 'active' : '' ?>">Início</a>
            <a href="ponto.php" class="<?= $paginaAtual === 'ponto.php' ? 'active' : '' ?>">Registro de Ponto</a>
            <a href="folha.php" class="<?= $paginaAtual === 'folha.php' ? 'active' : '' ?>">Folha de Pagamento</a>
            <a href="servicos.php" class="<?= $paginaAtual === 'servicos.php' ? 'active' : '' ?>">Serviços</a>
            <a href="relatorios.php" class="<?= $paginaAtual === 'relatorios.php' ? 'active' : '' ?>">Relatórios</a>
        </nav>
        <button class="btn btn-outline btn-logout" id="btnLogoutFuncionario">Sair</button>
    </aside>
    <main class="main-content">
