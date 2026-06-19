<?php $tituloPagina = 'Funcionários'; require_once __DIR__ . '/../includes/header_admin.php'; ?>

<section class="page-header">
    <h1>Funcionários</h1>
    <p>Gerencie funcionários e folha de pagamento.</p>
</section>

<div class="admin-grid">
    <div class="panel">
        <h3>Lista de Funcionários</h3>
        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr><th>ID</th><th>Nome</th><th>E-mail</th><th>Ação</th></tr>
                </thead>
                <tbody id="tbodyFuncionarios"></tbody>
            </table>
        </div>
    </div>

    <div class="panel">
        <h3>Folha de Pagamento</h3>
        <form id="formFolha">
            <input type="hidden" id="folhaFuncionarioId">
            <p id="folhaFuncionarioNome" class="hint">Selecione um funcionário</p>
            <div class="form-grid">
                <div class="form-group">
                    <label for="folhaMesAdmin">Mês</label>
                    <select id="folhaMesAdmin" required></select>
                </div>
                <div class="form-group">
                    <label for="folhaAnoAdmin">Ano</label>
                    <select id="folhaAnoAdmin" required></select>
                </div>
                <div class="form-group">
                    <label for="folhaSalario">Salário (R$)</label>
                    <input type="number" id="folhaSalario" step="0.01" min="0" required>
                </div>
                <div class="form-group">
                    <label for="folhaDescontos">Descontos (R$)</label>
                    <input type="number" id="folhaDescontos" step="0.01" min="0" value="0">
                </div>
                <div class="form-group">
                    <label for="folhaVT">Vale-Transporte (R$)</label>
                    <input type="number" id="folhaVT" step="0.01" min="0" value="0">
                </div>
                <div class="form-group">
                    <label for="folhaVA">Vale-Alimentação (R$)</label>
                    <input type="number" id="folhaVA" step="0.01" min="0" value="0">
                </div>
            </div>
            <label class="checkbox-label">
                <input type="checkbox" id="folhaRecebido"> Salário recebido
            </label>
            <p id="folhaDescontoFaltas" class="hint"></p>
            <button type="submit" class="btn btn-primary">Salvar Folha</button>
        </form>
    </div>
</div>

<script>
    const meses = ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'];
    const folhaMesAdmin = document.getElementById('folhaMesAdmin');
    const folhaAnoAdmin = document.getElementById('folhaAnoAdmin');
    const agora = new Date();
    let funcionarioSelecionado = null;
    let funcionariosLista = [];

    meses.forEach((nome, i) => {
        const opt = document.createElement('option');
        opt.value = i + 1;
        opt.textContent = nome;
        if (i + 1 === agora.getMonth() + 1) opt.selected = true;
        folhaMesAdmin.appendChild(opt);
    });

    for (let ano = agora.getFullYear(); ano >= agora.getFullYear() - 3; ano--) {
        const opt = document.createElement('option');
        opt.value = ano;
        opt.textContent = ano;
        folhaAnoAdmin.appendChild(opt);
    }

    async function carregarFuncionarios() {
        const res = await apiGet('funcionarios.php?action=listar');
        const tbody = document.getElementById('tbodyFuncionarios');
        if (!res.success) return;

        funcionariosLista = res.funcionarios;
        tbody.innerHTML = res.funcionarios.map(f => `
            <tr>
                <td>${escapeHtml(f.id)}</td>
                <td>${escapeHtml(f.nome)}</td>
                <td>${escapeHtml(f.email)}</td>
                <td><button class="btn btn-sm btn-secondary btn-selecionar" data-id="${f.id}">Folha</button></td>
            </tr>
        `).join('');

        tbody.querySelectorAll('.btn-selecionar').forEach(btn => {
            btn.addEventListener('click', () => {
                const func = funcionariosLista.find(item => item.id === btn.dataset.id);
                funcionarioSelecionado = { id: func.id, nome: func.nome };
                document.getElementById('folhaFuncionarioId').value = funcionarioSelecionado.id;
                document.getElementById('folhaFuncionarioNome').textContent = 'Funcionário: ' + funcionarioSelecionado.nome;
                carregarFolhaFuncionario();
            });
        });
    }

    async function carregarFolhaFuncionario() {
        if (!funcionarioSelecionado) return;
        const res = await apiGet(`folha.php?action=buscar&funcionario_id=${funcionarioSelecionado.id}&mes=${folhaMesAdmin.value}&ano=${folhaAnoAdmin.value}`);
        if (res.folha) {
            document.getElementById('folhaSalario').value = res.folha.salario;
            document.getElementById('folhaDescontos').value = res.folha.descontos_manuais;
            document.getElementById('folhaVT').value = res.folha.vale_transporte;
            document.getElementById('folhaVA').value = res.folha.vale_alimentacao;
            document.getElementById('folhaRecebido').checked = res.folha.recebido == 1;
            document.getElementById('folhaDescontoFaltas').textContent = 'Desconto por faltas (calculado): R$ ' + formatMoney(res.folha.desconto_faltas);
        } else {
            document.getElementById('formFolha').reset();
            document.getElementById('folhaFuncionarioId').value = funcionarioSelecionado.id;
            document.getElementById('folhaDescontoFaltas').textContent = '';
        }
    }

    folhaMesAdmin.addEventListener('change', carregarFolhaFuncionario);
    folhaAnoAdmin.addEventListener('change', carregarFolhaFuncionario);

    document.getElementById('formFolha').addEventListener('submit', async (e) => {
        e.preventDefault();
        if (!funcionarioSelecionado) {
            showToast('Selecione um funcionário.', 'error');
            return;
        }
        const payload = {
            funcionario_id: funcionarioSelecionado.id,
            mes: parseInt(folhaMesAdmin.value, 10),
            ano: parseInt(folhaAnoAdmin.value, 10),
            salario: parseFloat(document.getElementById('folhaSalario').value),
            descontos_manuais: parseFloat(document.getElementById('folhaDescontos').value || 0),
            vale_transporte: parseFloat(document.getElementById('folhaVT').value || 0),
            vale_alimentacao: parseFloat(document.getElementById('folhaVA').value || 0),
            recebido: document.getElementById('folhaRecebido').checked,
        };
        const res = await apiPost('folha.php?action=salvar', payload);
        showToast(res.message, res.success ? 'success' : 'error');
        if (res.success) {
            document.getElementById('folhaDescontoFaltas').textContent = 'Desconto por faltas (calculado): R$ ' + formatMoney(res.desconto_faltas);
        }
    });

    carregarFuncionarios();
</script>

<?php require_once __DIR__ . '/../includes/footer_admin.php'; ?>
