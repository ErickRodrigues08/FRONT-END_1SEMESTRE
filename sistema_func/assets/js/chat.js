let chatPoll = null;

function initChatWidget() {
    const toggle = document.getElementById('chatToggle');
    const panel = document.getElementById('chatPanel');
    const close = document.getElementById('chatClose');
    const form = document.getElementById('chatForm');

    if (!toggle || !panel) return;

    toggle.addEventListener('click', () => {
        panel.classList.toggle('hidden');
        if (!panel.classList.contains('hidden')) {
            carregarMensagensChat();
            if (!chatPoll) chatPoll = setInterval(carregarMensagensChat, 5000);
        }
    });

    close?.addEventListener('click', () => panel.classList.add('hidden'));

    form?.addEventListener('submit', async (e) => {
        e.preventDefault();
        const input = document.getElementById('chatInput');
        const mensagem = input.value.trim();
        if (!mensagem) return;

        const res = await apiPost('chat.php?action=enviar', { mensagem });
        if (res.success) {
            input.value = '';
            carregarMensagensChat();
        } else {
            showToast(res.message, 'error');
        }
    });
}

async function carregarMensagensChat() {
    const container = document.getElementById('chatMessages');
    if (!container) return;

    const res = await apiGet('chat.php?action=listar');
    if (!res.success) return;

    container.innerHTML = res.mensagens.map(m => `
        <div class="chat-bubble ${m.remetente === 'funcionario' ? 'mine' : 'theirs'}">
            <p>${escapeHtml(m.mensagem)}</p>
            <small>${formatDateTime(m.created_at)}</small>
        </div>
    `).join('');

    container.scrollTop = container.scrollHeight;
}

document.addEventListener('DOMContentLoaded', initChatWidget);
