let chartPresenca = null;
let chartServicos = null;

async function carregarDashboard() {
    const res = await apiGet('dashboard.php');
    if (!res.success) {
        showToast(res.message || 'Erro ao carregar dashboard.', 'error');
        return;
    }

    document.getElementById('cardFuncionarios').textContent = res.cards.total_funcionarios;
    document.getElementById('cardPresencas').textContent = res.cards.presencas_hoje;
    document.getElementById('cardFaltas').textContent = res.cards.faltas_mes;
    document.getElementById('cardMensagens').textContent = res.cards.mensagens_nao_lidas;

    const labelsPresenca = res.presenca_por_funcionario.map(p => p.nome);
    const dataPresenca = res.presenca_por_funcionario.map(p => p.presencas);

    if (chartPresenca) chartPresenca.destroy();
    chartPresenca = new Chart(document.getElementById('chartPresenca'), {
        type: 'bar',
        data: {
            labels: labelsPresenca,
            datasets: [{
                label: 'Presenças no mês',
                data: dataPresenca,
                backgroundColor: '#3b82f6',
            }],
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
        },
    });

    if (chartServicos) chartServicos.destroy();
    chartServicos = new Chart(document.getElementById('chartServicos'), {
        type: 'pie',
        data: {
            labels: ['Em andamento', 'Concluídos', 'Pendentes'],
            datasets: [{
                data: [
                    res.servicos.em_andamento,
                    res.servicos.concluidos,
                    res.servicos.pendentes,
                ],
                backgroundColor: ['#f59e0b', '#22c55e', '#94a3b8'],
            }],
        },
        options: { responsive: true },
    });
}

document.addEventListener('DOMContentLoaded', carregarDashboard);
