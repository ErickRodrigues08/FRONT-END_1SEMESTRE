<?php
require_once __DIR__ . '/session.php';
redirectIfNotAdmin();
$adminUsuario = $_SESSION['admin_usuario'] ?? 'Admin';
$paginaAtual = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($tituloPagina ?? 'Admin') ?> - Sistema</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="app-layout admin-layout">
    <aside class="sidebar admin-sidebar">
        <div class="sidebar-brand">
            <h2>Administrador</h2>
            <p><?= htmlspecialchars($adminUsuario) ?></p>
        </div>
        <nav class="sidebar-nav">
            <a href="dashboard.php" class="<?= $paginaAtual === 'dashboard.php' ? 'active' : '' ?>">Dashboard</a>
            <a href="funcionarios.php" class="<?= $paginaAtual === 'funcionarios.php' ? 'active' : '' ?>">Funcionários</a>
            <a href="servicos.php" class="<?= $paginaAtual === 'servicos.php' ? 'active' : '' ?>">Serviços</a>
            <a href="chat.php" class="<?= $paginaAtual === 'chat.php' ? 'active' : '' ?>">Chat</a>
        </nav>
        <button class="btn btn-outline btn-logout" id="btnLogoutAdmin">Sair</button>
    </aside>
    <main class="main-content">
