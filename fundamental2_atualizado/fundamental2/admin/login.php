<?php

declare(strict_types=1);

session_start();
if (!empty($_SESSION['admin_id'])) {
    header('Location: dashboard.php');
    exit;
}

$pageTitle = 'Login administrativo';
$rootPrefix = '../';
$navVariant = 'login';
$extraScripts = '<script src="../assets/js/admin-login.js" defer></script>';
require __DIR__ . '/../includes/header.php';
?>

<section class="py-5">
    <div class="container">
        <div class="login-card glass-card p-4 p-md-5 reveal">
            <div class="text-center mb-4">
                <span class="icon-bubble d-inline-flex mb-2"><i class="bi bi-shield-lock"></i></span>
                <h1 class="h4 fw-bold">Acesso administrativo</h1>
                <p class="text-muted small mb-0">Entre com usuário e senha fornecidos pela coordenação.</p>
            </div>
            <div class="alert alert-danger d-none rounded-3" id="loginError" role="alert"></div>
            <form id="formLogin" novalidate>
                <div class="mb-3">
                    <label for="username" class="form-label">Usuário</label>
                    <input type="text" class="form-control" id="username" name="username" autocomplete="username" required>
                    <div class="invalid-feedback">Informe o usuário.</div>
                </div>
                <div class="mb-4">
                    <label for="password" class="form-label">Senha</label>
                    <input type="password" class="form-control" id="password" name="password" autocomplete="current-password" required>
                    <div class="invalid-feedback">Informe a senha.</div>
                </div>
                <button type="submit" class="btn btn-sesi w-100" id="btnLogin">
                    Entrar
                </button>
            </form>
        </div>
    </div>
</section>

<div class="loader-overlay" id="pageLoader" aria-hidden="true">
    <div class="loader-spinner" role="status" aria-label="Carregando"></div>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>
