const MESES = [
    'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho',
    'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro',
];

let mesAtual = new Date().getMonth() + 1;
let anoAtual = new Date().getFullYear();
let pontosData = {};
let faltasData = {};

function initCalendario() {
    const selectMes = document.getElementById('selectMes');
    const selectAno = document.getElementById('selectAno');

    MESES.forEach((nome, i) => {
        const opt = document.createElement('option');
        opt.value = i + 1;
        opt.textContent = nome;
        if (i + 1 === mesAtual) opt.selected = true;
        selectMes.appendChild(opt);
    });

    for (let ano = anoAtual; ano >= anoAtual - 3; ano--) {
        const opt = document.createElement('option');
        opt.value = ano;
        opt.textContent = ano;
        selectAno.appendChild(opt);
    }

    selectMes.addEventListener('change', () => {
        mesAtual = parseInt(selectMes.value, 10);
        carregarCalendario();
    });

    selectAno.addEventListener('change', () => {
        anoAtual = parseInt(selectAno.value, 10);
        carregarCalendario();
    });

    document.getElementById('btnInformarFalta')?.addEventListener('click', () => openModal('modalFalta'));
    document.getElementById('formPonto')?.addEventListener('submit', salvarPonto);
    document.getElementById('formFalta')?.addEventListener('submit', salvarFalta);

    carregarCalendario();
}

async function carregarCalendario() {
    const res = await apiGet(`ponto.php?action=calendario&mes=${mesAtual}&ano=${anoAtual}`);
    if (!res.success) {
        showToast(res.message, 'error');
        return;
    }

    pontosData = res.pontos || {};
    faltasData = res.faltas || {};
    renderCalendario();
}

function renderCalendario() {
    const container = document.getElementById('calendario');
    const primeiroDia = new Date(anoAtual, mesAtual - 1, 1).getDay();
    const totalDias = new Date(anoAtual, mesAtual, 0).getDate();

    let html = '<div class="calendario-header">';
    ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'].forEach(d => {
        html += `<span>${d}</span>`;
    });
    html += '</div><div class="calendario-grid">';

    for (let i = 0; i < primeiroDia; i++) {
        html += '<div class="dia vazio"></div>';
    }

    for (let dia = 1; dia <= totalDias; dia++) {
        const data = `${anoAtual}-${String(mesAtual).padStart(2, '0')}-${String(dia).padStart(2, '0')}`;
        let classe = 'dia';
        if (pontosData[data]) classe += ' dia-registrado';
        else if (faltasData[data]) classe += ' dia-falta';

        html += `<button type="button" class="${classe}" data-data="${data}">${dia}</button>`;
    }

    html += '</div>';
    container.innerHTML = html;

    container.querySelectorAll('.dia:not(.vazio)').forEach(btn => {
        btn.addEventListener('click', () => abrirModalPonto(btn.dataset.data));
    });
}

function abrirModalPonto(data) {
    document.getElementById('dataPonto').value = data;
    document.getElementById('dataPontoLabel').textContent = formatDate(data);

    const ponto = pontosData[data];
    document.getElementById('horaEntrada').value = ponto?.hora_entrada?.slice(0, 5) || '';
    document.getElementById('horaSaida').value = ponto?.hora_saida?.slice(0, 5) || '';

    openModal('modalPonto');
}

async function salvarPonto(e) {
    e.preventDefault();
    const payload = {
        data: document.getElementById('dataPonto').value,
        hora_entrada: document.getElementById('horaEntrada').value,
        hora_saida: document.getElementById('horaSaida').value,
    };

    const res = await apiPost('ponto.php?action=salvar', payload);
    showToast(res.message, res.success ? 'success' : 'error');
    if (res.success) {
        closeModal('modalPonto');
        carregarCalendario();
    }
}

async function salvarFalta(e) {
    e.preventDefault();
    const formData = new FormData();
    formData.append('data', document.getElementById('dataFalta').value);
    formData.append('titulo', document.getElementById('tituloFalta').value);
    formData.append('descricao', document.getElementById('descricaoFalta').value);

    const anexo = document.getElementById('anexoFalta').files[0];
    if (anexo) formData.append('anexo', anexo);

    const response = await fetch(`${API_BASE}/faltas.php?action=salvar`, {
        method: 'POST',
        credentials: 'same-origin',
        body: formData,
    });
    const res = await response.json();

    showToast(res.message, res.success ? 'success' : 'error');
    if (res.success) {
        e.target.reset();
        closeModal('modalFalta');
        carregarCalendario();
    }
}

document.addEventListener('DOMContentLoaded', initCalendario);
