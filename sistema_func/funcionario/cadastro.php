<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Funcionário</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="auth-page">
    <div class="auth-card auth-card-wide">
        <h1>Cadastro de Funcionário</h1>
        <p class="auth-subtitle">Preencha seus dados. Seu ID será gerado automaticamente.</p>

        <form id="formCadastro">
            <div class="form-grid">
                <div class="form-group">
                    <label for="nome">Nome</label>
                    <input type="text" id="nome" required>
                </div>
                <div class="form-group">
                    <label for="cpf">CPF</label>
                    <input type="text" id="cpf" placeholder="000.000.000-00" required>
                </div>
                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" required>
                </div>
                <div class="form-group">
                    <label for="cep">CEP</label>
                    <input type="text" id="cep" placeholder="00000-000" required>
                </div>
                <div class="form-group">
                    <label for="telefone">Telefone</label>
                    <input type="text" id="telefone" placeholder="(00) 00000-0000" required>
                </div>
                <div class="form-group">
                    <label for="idade">Idade</label>
                    <input type="number" id="idade" min="16" max="100" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Cadastrar</button>
        </form>

        <div id="idGerado" class="id-gerado hidden">
            <h3>Cadastro realizado!</h3>
            <p>Seu ID de acesso é:</p>
            <strong id="idValor"></strong>
            <p class="hint">Anote este ID para fazer login.</p>
            <a href="login.php" class="btn btn-secondary btn-block">Ir para login</a>
        </div>

        <div class="auth-footer">
            <a href="login.php" class="link-back">Já tenho cadastro</a>
        </div>
    </div>

    <div class="toast-container" id="toastContainer"></div>
    <script>window.API_BASE = '../api';</script>
    <script src="../assets/js/common.js"></script>
    <script>
        document.getElementById('formCadastro').addEventListener('submit', async (e) => {
            e.preventDefault();
            const payload = {
                nome: document.getElementById('nome').value.trim(),
                cpf: document.getElementById('cpf').value.trim(),
                email: document.getElementById('email').value.trim(),
                cep: document.getElementById('cep').value.trim(),
                telefone: document.getElementById('telefone').value.trim(),
                idade: parseInt(document.getElementById('idade').value, 10),
            };

            const res = await apiPost('auth.php?action=cadastro_funcionario', payload);
            if (res.success) {
                document.getElementById('formCadastro').classList.add('hidden');
                document.getElementById('idGerado').classList.remove('hidden');
                document.getElementById('idValor').textContent = res.id;
                showToast(res.message, 'success');
            } else {
                showToast(res.message, 'error');
            }
        });
    </script>
</body>
</html>
