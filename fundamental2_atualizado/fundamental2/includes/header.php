<?php
/** @var string $pageTitle */
/** @var string $activeNav home|admin */
/** @var string $navVariant public|admin|login */
$pageTitle = $pageTitle ?? 'Jogos Interclasse SESI';
$activeNav = $activeNav ?? 'home';
$rootPrefix = $rootPrefix ?? '';
$navVariant = $navVariant ?? 'public';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="<?= $rootPrefix ?>assets/css/style.css" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100 bg-page">
<nav class="navbar navbar-expand-lg navbar-sesi sticky-top shadow-sm">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2 fw-bold text-sesi-red" href="<?= $rootPrefix ?>index.php">
            <img src="<?= $rootPrefix ?>assets/img/logo-sesi.svg" alt="SESI" width="120" height="36" class="logo-sesi">
            <span class="d-none d-sm-inline brand-sub">Interclasse</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain" aria-controls="navMain" aria-expanded="false" aria-label="Menu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMain">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-lg-center gap-lg-2">
                <?php if ($navVariant === 'admin'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= $rootPrefix ?>index.php"><i class="bi bi-house-door me-1"></i>Site</a>
                    </li>
                    <li class="nav-item">
                        <span class="nav-link disabled d-none d-md-block"><i class="bi bi-person-badge me-1"></i><?= htmlspecialchars($_SESSION['admin_username'] ?? 'Admin', ENT_QUOTES, 'UTF-8') ?></span>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="btn btn-outline-danger btn-sm ms-lg-2" id="btnLogoutNav"><i class="bi bi-box-arrow-right me-1"></i>Sair</button>
                    </li>
                <?php elseif ($navVariant === 'login'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= $rootPrefix ?>index.php"><i class="bi bi-arrow-left me-1"></i>Voltar ao cadastro</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link <?= $activeNav === 'home' ? 'active fw-semibold' : '' ?>" href="<?= $rootPrefix ?>index.php#cadastro">Inscrição</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= $rootPrefix ?>index.php#datas">Datas</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-sesi-outline btn-sm ms-lg-2 mt-2 mt-lg-0" href="<?= $rootPrefix ?>admin/login.php">
                            <i class="bi bi-shield-lock me-1"></i> Login Administrador
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
<main class="flex-grow-1">
