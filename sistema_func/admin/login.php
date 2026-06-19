<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Administrador</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="auth-page admin-auth">
    <div class="auth-card">
        <h1>Login do Administrador</h1>
        <p class="auth-subtitle">Usuário padrão: admin / admin123</p>

        <form id="formLoginAdmin">
            <div class="form-group">
                <label for="usuario">Usuário</label>
                <input type="text" id="usuario" required>
            </div>
            <div class="form-group">
                <label for="senha">Senha</label>
                <input type="password" id="senha" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Entrar</button>
        </form>

        <div class="auth-footer">
            <a href="../index.php" class="link-back">Voltar ao início</a>
        </div>
    </div>

    <div class="toast-container" id="toastContainer"></div>
    <script>window.API_BASE = '../api';</script>
    <script src="../assets/js/common.js"></script>
    <script>
        document.getElementById('formLoginAdmin').addEventListener('submit', async (e) => {
            e.preventDefault();
            const res = await apiPost('auth.php?action=login_admin', {
                usuario: document.getElementById('usuario').value.trim(),
                senha: document.getElementById('senha').value,
            });
            if (res.success) {
                showToast(res.message, 'success');
                setTimeout(() => window.location.href = 'dashboard.php', 800);
            } else {
                showToast(res.message, 'error');
            }
        });
    </script>
</body>
</html>
