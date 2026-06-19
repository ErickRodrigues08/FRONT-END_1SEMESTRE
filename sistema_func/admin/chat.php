<?php $tituloPagina = 'Chat'; require_once __DIR__ . '/../includes/header_admin.php'; ?>

<section class="page-header">
    <h1>Chat com Funcionários</h1>
    <p>Visualize e responda mensagens dos funcionários.</p>
</section>

<div class="chat-admin-layout">
    <div class="chat-conversas panel" id="listaConversas">
        <p class="empty-state">Carregando conversas...</p>
    </div>
    <div class="chat-area panel">
        <div class="chat-header-admin">
            <h3 id="chatFuncionarioNome">Selecione uma conversa</h3>
        </div>
        <div class="chat-messages admin-messages" id="adminChatMessages"></div>
        <form class="chat-form" id="adminChatForm">
            <input type="hidden" id="chatFuncionarioId">
            <input type="text" id="adminChatInput" placeholder="Digite sua resposta..." disabled>
            <button type="submit" class="btn btn-primary" disabled id="btnEnviarChat">Enviar</button>
        </form>
    </div>
</div>

<script>
    let conversaAtual = null;
    let pollChat = null;

    async function carregarConversas() {
        const res = await apiGet('chat.php?action=listar');
        const container = document.getElementById('listaConversas');
        if (!res.success || !res.conversas || !res.conversas.length) {
            container.innerHTML = '<p class="empty-state">Nenhuma conversa ainda.</p>';
            return;
        }
        container.innerHTML = res.conversas.map(c => `
            <button class="conversa-item ${conversaAtual === c.id ? 'active' : ''}" data-id="${c.id}" data-nome="${escapeHtml(c.nome)}">
                <strong>${escapeHtml(c.nome)}</strong>
                <span>${escapeHtml(c.ultima_mensagem || '')}</span>
                ${c.nao_lidas > 0 ? `<em class="badge-notif">${c.nao_lidas}</em>` : ''}
            </button>
        `).join('');

        container.querySelectorAll('.conversa-item').forEach(btn => {
            btn.addEventListener('click', () => abrirConversa(btn.dataset.id, btn.dataset.nome));
        });
    }

    async function abrirConversa(id, nome) {
        conversaAtual = id;
        document.getElementById('chatFuncionarioId').value = id;
        document.getElementById('chatFuncionarioNome').textContent = nome;
        document.getElementById('adminChatInput').disabled = false;
        document.getElementById('btnEnviarChat').disabled = false;
        await carregarMensagens();
        carregarConversas();
    }

    async function carregarMensagens() {
        if (!conversaAtual) return;
        const res = await apiGet(`chat.php?action=listar&funcionario_id=${conversaAtual}`);
        const container = document.getElementById('adminChatMessages');
        if (!res.success) return;
        container.innerHTML = res.mensagens.map(m => `
            <div class="chat-bubble ${m.remetente === 'admin' ? 'mine' : 'theirs'}">
                <p>${escapeHtml(m.mensagem)}</p>
                <small>${formatDateTime(m.created_at)}</small>
            </div>
        `).join('');
        container.scrollTop = container.scrollHeight;
    }

    document.getElementById('adminChatForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const mensagem = document.getElementById('adminChatInput').value.trim();
        if (!mensagem || !conversaAtual) return;
        const res = await apiPost('chat.php?action=enviar', { funcionario_id: conversaAtual, mensagem });
        if (res.success) {
            document.getElementById('adminChatInput').value = '';
            carregarMensagens();
            carregarConversas();
        }
    });

    carregarConversas();
    pollChat = setInterval(() => {
        carregarConversas();
        if (conversaAtual) carregarMensagens();
    }, 5000);
</script>

<?php require_once __DIR__ . '/../includes/footer_admin.php'; ?>
