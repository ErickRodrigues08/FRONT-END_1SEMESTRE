<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

require_once 'includes/conexao.php';

$tipo = $_SESSION['tipo'];
$nome = $_SESSION['nome'];

// Stats
$total_avisos     = $pdo->query("SELECT COUNT(*) FROM avisos")->fetchColumn();
$total_atividades = $pdo->query("SELECT COUNT(*) FROM atividades")->fetchColumn();
$total_usuarios   = $pdo->query("SELECT COUNT(*) FROM usuarios")->fetchColumn();

// Últimos avisos
$avisos_recentes = $pdo->query("SELECT * FROM avisos ORDER BY data_publicacao DESC LIMIT 3")->fetchAll();

// Próximas atividades
$atividades_recentes = $pdo->query("SELECT * FROM atividades WHERE prazo >= CURDATE() ORDER BY prazo ASC LIMIT 3")->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard – EduGestão</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="app-wrapper">
    <?php include 'includes/sidebar.php'; ?>
    <div class="main-content">
        <div class="topbar">
            <span class="topbar-title">Dashboard</span>
            <div class="topbar-actions">
                <span class="badge-role <?= $tipo ?>"><?= ucfirst($tipo) ?></span>
            </div>
        </div>
        <div class="page-body">

            <!-- Welcome -->
            <div style="margin-bottom:28px">
                <h2 style="font-family:'DM Serif Display',serif;font-size:28px;margin-bottom:6px">
                    Olá, <?= htmlspecialchars(explode(' ', $nome)[0]) ?>! 👋
                </h2>
                <p class="text-muted">Aqui está um resumo do sistema hoje.</p>
            </div>

            <!-- Stats -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon blue">📢</div>
                    <div class="stat-info">
                        <div class="value"><?= $total_avisos ?></div>
                        <div class="label">Avisos publicados</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon purple">📚</div>
                    <div class="stat-info">
                        <div class="value"><?= $total_atividades ?></div>
                        <div class="label">Atividades cadastradas</div>
                    </div>
                </div>
                <?php if ($tipo === 'administrador'): ?>
                <div class="stat-card">
                    <div class="stat-icon gold">👥</div>
                    <div class="stat-info">
                        <div class="value"><?= $total_usuarios ?></div>
                        <div class="label">Usuários cadastrados</div>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Two-col content -->
            <div class="grid-2">
                <!-- Avisos recentes -->
                <div class="card">
                    <div class="card-header">
                        <h3>Avisos Recentes</h3>
                        <a href="avisos.php" class="btn btn-ghost btn-sm">Ver todos</a>
                    </div>
                    <div class="card-body" style="padding:0">
                        <?php if ($avisos_recentes): ?>
                            <?php foreach ($avisos_recentes as $aviso): ?>
                            <div style="padding:16px 20px;border-bottom:1px solid var(--border);">
                                <div style="font-weight:600;margin-bottom:4px"><?= htmlspecialchars($aviso['titulo']) ?></div>
                                <div class="text-muted" style="font-size:12px">
                                    <?= date('d/m/Y', strtotime($aviso['data_publicacao'])) ?>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="empty-state">
                                <div class="icon">📢</div>
                                <p>Nenhum aviso ainda</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Próximas atividades -->
                <div class="card">
                    <div class="card-header">
                        <h3>Próximas Atividades</h3>
                        <a href="atividades.php" class="btn btn-ghost btn-sm">Ver todas</a>
                    </div>
                    <div class="card-body" style="padding:0">
                        <?php if ($atividades_recentes): ?>
                            <?php foreach ($atividades_recentes as $at): ?>
                            <div style="padding:16px 20px;border-bottom:1px solid var(--border);">
                                <div style="font-weight:600;margin-bottom:4px"><?= htmlspecialchars($at['titulo']) ?></div>
                                <div style="display:flex;gap:8px;align-items:center;margin-top:4px">
                                    <span class="chip chip-blue"><?= htmlspecialchars($at['turma']) ?></span>
                                    <span class="text-muted" style="font-size:12px">
                                        Prazo: <?= date('d/m/Y', strtotime($at['prazo'])) ?>
                                    </span>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="empty-state">
                                <div class="icon">📚</div>
                                <p>Nenhuma atividade pendente</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <?php if ($tipo === 'administrador'): ?>
            <!-- Quick actions for admin -->
            <div class="card" style="margin-top:24px">
                <div class="card-header"><h3>Ações Rápidas</h3></div>
                <div class="card-body" style="display:flex;gap:12px;flex-wrap:wrap">
                    <a href="avisos.php?action=novo" class="btn btn-primary">+ Novo Aviso</a>
                    <a href="avisos.php" class="btn btn-ghost">Gerenciar Avisos</a>
                </div>
            </div>
            <?php elseif ($tipo === 'professor'): ?>
            <div class="card" style="margin-top:24px">
                <div class="card-header"><h3>Ações Rápidas</h3></div>
                <div class="card-body" style="display:flex;gap:12px;flex-wrap:wrap">
                    <a href="atividades.php?action=nova" class="btn btn-primary">+ Nova Atividade</a>
                    <a href="atividades.php" class="btn btn-ghost">Gerenciar Atividades</a>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>
