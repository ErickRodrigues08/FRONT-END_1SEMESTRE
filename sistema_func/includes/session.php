<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function requireFuncionario(): string
{
    if (empty($_SESSION['funcionario_id'])) {
        http_response_code(401);
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Não autenticado.']);
        exit;
    }

    return $_SESSION['funcionario_id'];
}

function requireAdmin(): int
{
    if (empty($_SESSION['admin_id'])) {
        http_response_code(401);
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Não autenticado.']);
        exit;
    }

    return (int) $_SESSION['admin_id'];
}

function isFuncionarioLogged(): bool
{
    return !empty($_SESSION['funcionario_id']);
}

function isAdminLogged(): bool
{
    return !empty($_SESSION['admin_id']);
}

function redirectIfNotFuncionario(string $redirect = '../funcionario/login.php'): void
{
    if (!isFuncionarioLogged()) {
        header('Location: ' . $redirect);
        exit;
    }
}

function redirectIfNotAdmin(string $redirect = 'login.php'): void
{
    if (!isAdminLogged()) {
        header('Location: ' . $redirect);
        exit;
    }
}
