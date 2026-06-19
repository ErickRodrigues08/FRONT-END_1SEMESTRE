<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Funcionário</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="auth-page">
    <div class="auth-card">
        <h1>Login do Funcionário</h1>
        <p class="auth-subtitle">Entre com seu ID e e-mail cadastrados.</p>

        <form id="formLoginFuncionario">
            <div class="form-group">
                <label for="id">ID</label>
                <input type="text" id="id" name="id" placeholder="Ex: FUNC-0001" required>
            </div>
            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" placeholder="seu@email.com" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Entrar</button>
        </form>

        <div class="auth-footer">
            <p>Ainda não se cadastrou?</p>
            <a href="cadastro.php" class="btn btn-secondary btn-block">Fazer cadastro</a>
            <a href="../index.php" class="link-back">Voltar ao início</a>
        </div>
    </div>

    <div class="toast-container" id="toastContainer"></div>
    <script>window.API_BASE = '../api';</script>
    <script src="../assets/js/common.js"></script>
    <script>
        document.getElementById('formLoginFuncionario').addEventListener('submit', async (e) => {
            e.preventDefault();
            const id = document.getElementById('id').value.trim();
            const email = document.getElementById('email').value.trim();

            const res = await apiPost('auth.php?action=login_funcionario', { id, email });
            if (res.success) {
                showToast(res.message, 'success');
                setTimeout(() => window.location.href = 'home.php', 800);
            } else {
                showToast(res.message, 'error');
            }
        });
    </script>
</body>
</html>
