<?php
declare(strict_types=1);

function renderHeader(string $title): void
{
    $currentPage = basename($_SERVER['PHP_SELF']);
    ?>
    <!DOCTYPE html>
    <html lang="pt-BR">
    <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title><?= htmlspecialchars($title) ?> | Destino Certo Turismo</title>
      <link rel="preconnect" href="https://fonts.googleapis.com">
      <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
      <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,400&display=swap" rel="stylesheet">
      <link rel="stylesheet" href="styles.css">
    </head>
    <body>
      <div class="page-loader"><div class="loader-plane">✈</div></div>

      <header class="site-header">
        <div class="header-glow"></div>
        <div class="container header-inner">
          <a href="index.php" class="brand">
            <span class="brand-icon">✈</span>
            <div class="brand-text">
              <strong>Destino Certo</strong>
              <small>Turismo</small>
            </div>
          </a>
          <nav class="main-nav">
            <a href="index.php"    class="nav-link <?= $currentPage === 'index.php'    ? 'active' : '' ?>">🏠 Início</a>
            <a href="clientes.php" class="nav-link <?= $currentPage === 'clientes.php' ? 'active' : '' ?>">👤 Clientes</a>
            <a href="pacotes.php"  class="nav-link <?= $currentPage === 'pacotes.php'  ? 'active' : '' ?>">🗺️ Pacotes</a>
            <a href="reservas.php" class="nav-link <?= $currentPage === 'reservas.php' ? 'active' : '' ?>">📋 Reservas</a>
          </nav>
        </div>
      </header>

      <main class="container main-content">
    <?php
}

function renderMessage(): void
{
    if (!isset($_GET['message'])) {
        return;
    }

    $message = (string) $_GET['message'];
    $type = ($_GET['type'] ?? 'success') === 'error' ? 'error' : 'success';
    $icon = $type === 'success' ? '✅' : '❌';
    echo '<div class="alert ' . $type . '">' . $icon . ' ' . htmlspecialchars($message) . '</div>';
}

function renderFooter(): void
{
    ?>
      </main>

      <footer class="site-footer">
        <div class="container footer-inner">
          <span class="footer-brand">✈ Destino Certo Turismo</span>
          <span class="footer-copy">Sistema de gestão &copy; <?= date('Y') ?></span>
        </div>
      </footer>

      <script>
        window.addEventListener('load', () => {
          document.querySelector('.page-loader').classList.add('done');
          document.querySelectorAll('.card, .stat-card, form, table').forEach((el, i) => {
            el.style.setProperty('--delay', (i * 0.07) + 's');
            el.classList.add('animate-in');
          });
        });
      </script>
    </body>
    </html>
    <?php
}
