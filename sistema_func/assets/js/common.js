const API_BASE = window.API_BASE || '../api';

async function apiRequest(url, options = {}) {
    const response = await fetch(`${API_BASE}/${url}`, {
        credentials: 'same-origin',
        ...options,
    });

    try {
        return await response.json();
    } catch {
        return { success: false, message: 'Erro ao processar resposta do servidor.' };
    }
}

function apiGet(url) {
    return apiRequest(url);
}

function apiPost(url, data) {
    return apiRequest(url, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data),
    });
}

function showToast(message, type = 'info') {
    const container = document.getElementById('toastContainer');
    if (!container) return;

    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.textContent = message;
    container.appendChild(toast);

    setTimeout(() => {
        toast.classList.add('fade-out');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

function formatMoney(value) {
    return Number(value || 0).toFixed(2).replace('.', ',');
}

function formatDate(dateStr) {
    if (!dateStr) return '-';
    const [y, m, d] = dateStr.split('-');
    return `${d}/${m}/${y}`;
}

function formatDateTime(dateStr) {
    if (!dateStr) return '-';
    const date = new Date(dateStr.replace(' ', 'T'));
    if (Number.isNaN(date.getTime())) return dateStr;
    return date.toLocaleString('pt-BR');
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text ?? '';
    return div.innerHTML;
}

function openModal(id) {
    document.getElementById(id)?.classList.remove('hidden');
}

function closeModal(id) {
    document.getElementById(id)?.classList.add('hidden');
}

document.addEventListener('click', (e) => {
    const closeBtn = e.target.closest('[data-close]');
    if (closeBtn) closeModal(closeBtn.dataset.close);

    if (e.target.classList.contains('modal')) {
        e.target.classList.add('hidden');
    }
});

const btnLogoutFuncionario = document.getElementById('btnLogoutFuncionario');
if (btnLogoutFuncionario) {
    btnLogoutFuncionario.addEventListener('click', async () => {
        await apiGet('auth.php?action=logout_funcionario');
        window.location.href = 'login.php';
    });
}

const btnLogoutAdmin = document.getElementById('btnLogoutAdmin');
if (btnLogoutAdmin) {
    btnLogoutAdmin.addEventListener('click', async () => {
        await apiGet('auth.php?action=logout_admin');
        window.location.href = 'login.php';
    });
}
