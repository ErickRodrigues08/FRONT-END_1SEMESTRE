<?php $tituloPagina = 'Serviços'; require_once __DIR__ . '/../includes/header_admin.php'; ?>

<section class="page-header">
    <h1>Serviços</h1>
    <p>Envie tarefas para os funcionários.</p>
</section>

<div class="admin-grid">
    <div class="panel">
        <h3>Novo Serviço</h3>
        <form id="formServico">
            <div class="form-group">
                <label for="servicoFuncionario">Funcionário</label>
                <select id="servicoFuncionario" required></select>
            </div>
            <div class="form-group">
                <label for="servicoNome">Nome do Serviço</label>
                <input type="text" id="servicoNome" required>
            </div>
            <div class="form-group">
                <label for="servicoDescricao">Descrição</label>
                <textarea id="servicoDescricao" rows="3" required></textarea>
            </div>
            <div class="form-group">
                <label for="servicoDataHora">Data e Horário Solicitado</label>
                <input type="datetime-local" id="servicoDataHora" required>
            </div>
            <button type="submit" class="btn btn-primary">Enviar Serviço</button>
        </form>
    </div>

    <div class="panel">
        <h3>Serviços Enviados</h3>
        <div id="listaServicosAdmin" class="lista-servicos-admin"></div>
    </div>
</div>

<script>
    async function carregarFuncionariosSelect() {
        const res = await apiGet('funcionarios.php?action=listar');
        const select = document.getElementById('servicoFuncionario');
        if (!res.success) return;
        select.innerHTML = res.funcionarios.map(f => `<option value="${f.id}">${escapeHtml(f.nome)} (${f.id})</option>`).join('');
    }

    async function carregarServicosAdmin() {
        const res = await apiGet('servicos.php?action=listar_admin');
        const container = document.getElementById('listaServicosAdmin');
        if (!res.success || !res.servicos.length) {
            container.innerHTML = '<p class="empty-state">Nenhum serviço enviado.</p>';
            return;
        }
        container.innerHTML = res.servicos.map(s => `
            <div class="servico-item">
                <strong>${escapeHtml(s.nome)}</strong> - ${escapeHtml(s.funcionario_nome)}
                <p>${escapeHtml(s.descricao)}</p>
                <small>Solicitado: ${formatDateTime(s.data_hora_solicitado)}</small>
            </div>
        `).join('');
    }

    document.getElementById('formServico').addEventListener('submit', async (e) => {
        e.preventDefault();
        const payload = {
            funcionario_id: document.getElementById('servicoFuncionario').value,
            nome: document.getElementById('servicoNome').value.trim(),
            descricao: document.getElementById('servicoDescricao').value.trim(),
            data_hora_solicitado: document.getElementById('servicoDataHora').value.replace('T', ' ') + ':00',
        };
        const res = await apiPost('servicos.php?action=criar', payload);
        showToast(res.message, res.success ? 'success' : 'error');
        if (res.success) {
            e.target.reset();
            carregarServicosAdmin();
        }
    });

    carregarFuncionariosSelect();
    carregarServicosAdmin();
</script>

<?php require_once __DIR__ . '/../includes/footer_admin.php'; ?>
