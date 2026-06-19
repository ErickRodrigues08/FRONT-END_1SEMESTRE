<?php
session_start();
if (isset($_SESSION['usuario_id'])) {
    header('Location: dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>EduGestão – Sistema Escolar</title>
<link rel="stylesheet" href="css/style.css">
<style>
.landing {
    min-height: 100vh;
    background:
        radial-gradient(ellipse at 10% 30%, rgba(79,142,247,0.12) 0%, transparent 55%),
        radial-gradient(ellipse at 90% 70%, rgba(124,92,252,0.12) 0%, transparent 55%),
        var(--bg);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 32px;
    text-align: center;
}

.landing-logo {
    width: 72px; height: 72px;
    background: linear-gradient(135deg, var(--accent), var(--accent2));
    border-radius: 18px;
    display: flex; align-items: center; justify-content: center;
    font-size: 34px;
    margin: 0 auto 28px;
    box-shadow: 0 8px 32px rgba(79,142,247,0.3);
}

.landing h1 {
    font-family: 'DM Serif Display', serif;
    font-size: 52px;
    line-height: 1.1;
    margin-bottom: 16px;
    background: linear-gradient(135deg, var(--text) 0%, var(--muted) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.landing p {
    color: var(--muted);
    font-size: 18px;
    margin-bottom: 40px;
    max-width: 440px;
}

.landing-btns {
    display: flex;
    gap: 12px;
    justify-content: center;
    flex-wrap: wrap;
}

.features {
    display: flex;
    gap: 20px;
    margin-top: 64px;
    flex-wrap: wrap;
    justify-content: center;
}

.feature-item {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 20px 24px;
    width: 200px;
    text-align: center;
}

.feature-item .icon { font-size: 28px; margin-bottom: 10px; }
.feature-item h4 { font-size: 14px; font-weight: 600; margin-bottom: 6px; }
.feature-item p { font-size: 12px; color: var(--muted); }
</style>
</head>
<body>
<div class="landing">
    <div class="landing-logo">🎓</div>
    <h1>EduGestão</h1>
    <p>Plataforma escolar que conecta alunos, professores e administradores em um só lugar.</p>
    <div class="landing-btns">
        <a href="login.php" class="btn btn-primary btn-lg">Entrar no sistema</a>
        <a href="cadastro.php" class="btn btn-ghost btn-lg">Criar conta</a>
    </div>

    <div class="features">
        <div class="feature-item">
            <div class="icon">📢</div>
            <h4>Avisos</h4>
            <p>Comunicados da escola em tempo real</p>
        </div>
        <div class="feature-item">
            <div class="icon">📚</div>
            <h4>Atividades</h4>
            <p>Tarefas e prazos organizados por turma</p>
        </div>
        <div class="feature-item">
            <div class="icon">👥</div>
            <h4>Usuários</h4>
            <p>Alunos, professores e administradores</p>
        </div>
        <div class="feature-item">
            <div class="icon">⊞</div>
            <h4>Dashboard</h4>
            <p>Visão geral personalizada por perfil</p>
        </div>
    </div>
</div>
</body>
</html>
