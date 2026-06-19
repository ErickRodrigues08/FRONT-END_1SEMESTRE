<?php

require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../config/database.php';

$action = $_GET['action'] ?? '';
$pdo = getConnection();

if ($action === 'login_funcionario' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true) ?? [];
    $id = trim($data['id'] ?? '');
    $email = trim($data['email'] ?? '');

    if ($id === '' || $email === '') {
        jsonResponse(['success' => false, 'message' => 'Informe ID e e-mail.'], 400);
    }

    $stmt = $pdo->prepare('SELECT id, nome, email FROM funcionarios WHERE id = ? AND email = ?');
    $stmt->execute([$id, $email]);
    $funcionario = $stmt->fetch();

    if (!$funcionario) {
        jsonResponse(['success' => false, 'message' => 'ID ou e-mail inválidos.'], 401);
    }

    $_SESSION['funcionario_id'] = $funcionario['id'];
    $_SESSION['funcionario_nome'] = $funcionario['nome'];

    jsonResponse([
        'success' => true,
        'message' => 'Login realizado com sucesso.',
        'funcionario' => $funcionario,
    ]);
}

if ($action === 'cadastro_funcionario' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true) ?? [];

    $nome = trim($data['nome'] ?? '');
    $cpf = trim($data['cpf'] ?? '');
    $email = trim($data['email'] ?? '');
    $cep = trim($data['cep'] ?? '');
    $telefone = trim($data['telefone'] ?? '');
    $idade = (int) ($data['idade'] ?? 0);

    if ($nome === '' || $cpf === '' || $email === '' || $cep === '' || $telefone === '' || $idade <= 0) {
        jsonResponse(['success' => false, 'message' => 'Preencha todos os campos corretamente.'], 400);
    }

    if (!validarEmail($email)) {
        jsonResponse(['success' => false, 'message' => 'E-mail inválido.'], 400);
    }

    if (!validarCpf($cpf)) {
        jsonResponse(['success' => false, 'message' => 'CPF inválido.'], 400);
    }

    $id = gerarIdFuncionario($pdo);

    try {
        $stmt = $pdo->prepare(
            'INSERT INTO funcionarios (id, nome, cpf, email, cep, telefone, idade)
             VALUES (?, ?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([$id, $nome, $cpf, $email, $cep, $telefone, $idade]);
    } catch (PDOException $e) {
        if ($e->getCode() === '23000') {
            jsonResponse(['success' => false, 'message' => 'CPF ou e-mail já cadastrado.'], 409);
        }
        throw $e;
    }

    jsonResponse([
        'success' => true,
        'message' => 'Cadastro realizado com sucesso.',
        'id' => $id,
    ]);
}

if ($action === 'login_admin' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true) ?? [];
    $usuario = trim($data['usuario'] ?? '');
    $senha = $data['senha'] ?? '';

    if ($usuario === '' || $senha === '') {
        jsonResponse(['success' => false, 'message' => 'Informe usuário e senha.'], 400);
    }

    $stmt = $pdo->prepare('SELECT id, usuario, senha_hash FROM admins WHERE usuario = ?');
    $stmt->execute([$usuario]);
    $admin = $stmt->fetch();

    if (!$admin || !password_verify($senha, $admin['senha_hash'])) {
        jsonResponse(['success' => false, 'message' => 'Usuário ou senha inválidos.'], 401);
    }

    $_SESSION['admin_id'] = (int) $admin['id'];
    $_SESSION['admin_usuario'] = $admin['usuario'];

    jsonResponse(['success' => true, 'message' => 'Login realizado com sucesso.']);
}

if ($action === 'logout_funcionario') {
    unset($_SESSION['funcionario_id'], $_SESSION['funcionario_nome']);
    jsonResponse(['success' => true, 'message' => 'Logout realizado.']);
}

if ($action === 'logout_admin') {
    unset($_SESSION['admin_id'], $_SESSION['admin_usuario']);
    jsonResponse(['success' => true, 'message' => 'Logout realizado.']);
}

jsonResponse(['success' => false, 'message' => 'Ação inválida.'], 400);
