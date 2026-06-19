<?php
if (session_status() === PHP_SESSION_NONE) session_start();

function getInitials($name) {
    $parts = explode(' ', trim($name));
    $initials = '';
    foreach (array_slice($parts, 0, 2) as $p) {
        $initials .= strtoupper(mb_substr($p, 0, 1));
    }
    return $initials;
}

$current = basename($_SERVER['PHP_SELF']);
$tipo = $_SESSION['tipo'] ?? '';
?>
<aside class="sidebar">
    <div class="sidebar-logo">
        <div class="logo-icon">🎓</div>
        <h1>EduGestão</h1>
        <p>Sistema Escolar</p>
    </div>

    <?php if (isset($_SESSION['usuario_id'])): ?>
    <div class="sidebar-user">
        <div class="user-avatar"><?= getInitials($_SESSION['nome']) ?></div>
        <div class="user-info">
            <div class="name"><?= htmlspecialchars($_SESSION['nome']) ?></div>
            <span class="badge-role <?= $tipo ?>"><?= ucfirst($tipo) ?></span>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section-label">Geral</div>
        <a href="dashboard.php" class="nav-item <?= $current === 'dashboard.php' ? 'active' : '' ?>">
            <span class="icon">⊞</span> Dashboard
        </a>

        <div class="nav-section-label">Conteúdo</div>
        <a href="avisos.php" class="nav-item <?= $current === 'avisos.php' ? 'active' : '' ?>">
            <span class="icon">📢</span> Avisos
        </a>
        <a href="atividades.php" class="nav-item <?= $current === 'atividades.php' ? 'active' : '' ?>">
            <span class="icon">📚</span> Atividades
        </a>
    </nav>

    <div class="sidebar-footer">
        <a href="logout.php" class="btn btn-ghost btn-full">
            <span>↩</span> Sair
        </a>
    </div>
    <?php endif; ?>
</aside>
