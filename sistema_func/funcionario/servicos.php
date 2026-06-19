<?php $tituloPagina = 'Serviços'; require_once __DIR__ . '/../includes/header_funcionario.php'; ?>

<section class="page-header">
    <h1>Meus Serviços</h1>
    <p>Serviços atribuídos pelo administrador.</p>
</section>

<div id="listaServicos" class="lista-servicos">
    <p class="empty-state">Carregando serviços...</p>
</div>

<?php require_once __DIR__ . '/../includes/footer_funcionario.php'; ?>

<script>
    function statusServico(s) {
        if (s.data_termino) return 'Concluído';
        if (s.em_andamento == 1) return 'Em andamento';
        return 'Pendente';
    }

    async function carregarServicos() {
        const container = document.getElementById('listaServicos');
        const res = await apiGet('servicos.php?action=listar_funcionario');

        if (!res.success || !res.servicos.length) {
            container.innerHTML = '<p class="empty-state">Nenhum serviço atribuído.</p>';
            return;
        }

        container.innerHTML = res.servicos.map(s => `
            <div class="servico-card" data-id="${s.id}">
                <div class="servico-header">
                    <h3>${escapeHtml(s.nome)}</h3>
                    <span class="badge">${statusServico(s)}</span>
                </div>
                <p>${escapeHtml(s.descricao)}</p>
                <p><strong>Solicitado:</strong> ${formatDateTime(s.data_hora_solicitado)}</p>
                <div class="form-grid">
                    <div class="form-group">
                        <label>Hora que foi executar</label>
                        <input type="time" class="hora-exec" value="${s.hora_execucao || ''}">
                    </div>
                    <div class="form-group">
                        <label>Data de término</label>
                        <input type="date" class="data-termino" value="${s.data_termino || ''}">
                    </div>
                </div>
                <label class="checkbox-label">
                    <input type="checkbox" class="em-andamento" ${s.em_andamento == 1 ? 'checked' : ''}>
                    Em andamento
                </label>
                <button class="btn btn-primary btn-salvar-servico">Salvar</button>
            </div>
        `).join('');

        container.querySelectorAll('.btn-salvar-servico').forEach(btn => {
            btn.addEventListener('click', async () => {
                const card = btn.closest('.servico-card');
                const id = card.dataset.id;
                const payload = {
                    id: parseInt(id, 10),
                    hora_execucao: card.querySelector('.hora-exec').value,
                    data_termino: card.querySelector('.data-termino').value,
                    em_andamento: card.querySelector('.em-andamento').checked,
                };
                const result = await apiPost('servicos.php?action=atualizar_funcionario', payload);
                showToast(result.message, result.success ? 'success' : 'error');
                if (result.success) carregarServicos();
            });
        });
    }

    carregarServicos();
</script>
