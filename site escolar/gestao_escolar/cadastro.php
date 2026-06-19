<?php
session_start();
if (isset($_SESSION['usuario_id'])) {
    header('Location: dashboard.php');
    exit;
}

require_once 'includes/conexao.php';
$erro = '';
$sucesso = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome  = trim($_POST['nome']  ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha']      ?? '';
    $conf  = $_POST['confirmacao']?? '';
    $tipo  = $_POST['tipo']       ?? '';

    $tipos_validos = ['aluno', 'professor', 'administrador'];

    if (!$nome || !$email || !$senha || !$conf || !$tipo) {
        $erro = 'Preencha todos os campos.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = 'E-mail inválido.';
    } elseif (strlen($senha) < 6) {
        $erro = 'A senha deve ter pelo menos 6 caracteres.';
    } elseif ($senha !== $conf) {
        $erro = 'As senhas não coincidem.';
    } elseif (!in_array($tipo, $tipos_validos)) {
        $erro = 'Tipo de usuário inválido.';
    } else {
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $erro = 'Este e-mail já está cadastrado.';
        } else {
            $hash = password_hash($senha, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha, tipo) VALUES (?, ?, ?, ?)");
            $stmt->execute([$nome, $email, $hash, $tipo]);
            $sucesso = 'Conta criada com sucesso! Faça login.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Cadastro – EduGestão</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="auth-page">
    <div class="auth-left">
        <div class="auth-tagline">
            <h2>Crie sua conta na plataforma escolar</h2>
            <p>Alunos acompanham atividades. Professores publicam tarefas. Administradores gerenciam a escola.</p>
        </div>
    </div>
    <div class="auth-right">
        <div class="auth-card">
            <div class="auth-logo">
                <div class="logo-icon">✏️</div>
                <h2>Criar Conta</h2>
                <p>Preencha os dados para se cadastrar</p>
            </div>

            <?php if ($erro): ?>
                <div class="alert alert-danger">⚠️ <?= htmlspecialchars($erro) ?></div>
            <?php endif; ?>
            <?php if ($sucesso): ?>
                <div class="alert alert-success">✅ <?= htmlspecialchars($sucesso) ?></div>
            <?php endif; ?>

            <?php if (!$sucesso): ?>
            <form method="POST">
                <div class="form-group">
                    <label class="form-label">Nome completo</label>
                    <input type="text" name="nome" class="form-control"
                           placeholder="Seu nome"
                           value="<?= htmlspecialchars($_POST['nome'] ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label class="form-label">E-mail</label>
                    <input type="email" name="email" class="form-control"
                           placeholder="seu@email.com"
                           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                </div>
                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Senha</label>
                        <input type="password" name="senha" class="form-control"
                               placeholder="Min. 6 caracteres" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Confirmar senha</label>
                        <input type="password" name="confirmacao" class="form-control"
                               placeholder="Repita a senha" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Tipo de usuário</label>
                    <select name="tipo" class="form-control" required>
                        <option value="">Selecione...</option>
                        <option value="aluno" <?= (($_POST['tipo']??'')==='aluno')?'selected':'' ?>>🎒 Aluno</option>
                        <option value="professor" <?= (($_POST['tipo']??'')==='professor')?'selected':'' ?>>👨‍🏫 Professor</option>
                        <option value="administrador" <?= (($_POST['tipo']??'')==='administrador')?'selected':'' ?>>⚙️ Administrador</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary btn-full btn-lg">
                    Criar conta →
                </button>
            </form>
            <?php else: ?>
                <a href="login.php" class="btn btn-primary btn-full btn-lg">Ir para login →</a>
            <?php endif; ?>

            <hr class="divider">
            <p class="text-center text-muted" style="font-size:14px">
                Já tem conta?
                <a href="login.php" style="color:var(--accent);text-decoration:none;font-weight:600">Entrar</a>
            </p>
        </div>
    </div>
</div>
</body>
</html>
