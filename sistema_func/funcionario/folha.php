<?php $tituloPagina = 'Folha de Pagamento'; require_once __DIR__ . '/../includes/header_funcionario.php'; ?>

<section class="page-header">
    <h1>Folha de Pagamento</h1>
    <p>Consulte seu salário e benefícios do período selecionado.</p>
</section>

<div class="toolbar">
    <div class="form-group inline">
        <label for="folhaMes">Mês</label>
        <select id="folhaMes"></select>
    </div>
    <div class="form-group inline">
        <label for="folhaAno">Ano</label>
        <select id="folhaAno"></select>
    </div>
    <button class="btn btn-primary" id="btnBuscarFolha">Consultar</button>
</div>

<div id="folhaContainer" class="folha-container">
    <p class="empty-state">Selecione o período para visualizar a folha.</p>
</div>

<script>
    const meses = ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'];
    const folhaMes = document.getElementById('folhaMes');
    const folhaAno = document.getElementById('folhaAno');
    const agora = new Date();

    meses.forEach((nome, i) => {
        const opt = document.createElement('option');
        opt.value = i + 1;
        opt.textContent = nome;
        if (i + 1 === agora.getMonth() + 1) opt.selected = true;
        folhaMes.appendChild(opt);
    });

    for (let ano = agora.getFullYear(); ano >= agora.getFullYear() - 3; ano--) {
        const opt = document.createElement('option');
        opt.value = ano;
        opt.textContent = ano;
        folhaAno.appendChild(opt);
    }

    function renderFolha(folha) {
        const container = document.getElementById('folhaContainer');
        if (!folha) {
            container.innerHTML = '<p class="empty-state">Folha ainda não disponível para este período.</p>';
            return;
        }

        container.innerHTML = `
            <div class="folha-card">
                <h3>${folha.mes_nome} / ${folha.ano}</h3>
                <div class="folha-grid">
                    <div><span>Salário</span><strong>R$ ${formatMoney(folha.salario)}</strong></div>
                    <div><span>Recebido</span><strong>${folha.recebido_texto}</strong></div>
                    <div><span>Descontos</span><strong>R$ ${formatMoney(folha.descontos_manuais)}</strong></div>
                    <div><span>Desconto por Faltas</span><strong>R$ ${formatMoney(folha.desconto_faltas)}</strong></div>
                    <div><span>Vale-Transporte</span><strong>R$ ${formatMoney(folha.vale_transporte)}</strong></div>
                    <div><span>Vale-Alimentação</span><strong>R$ ${formatMoney(folha.vale_alimentacao)}</strong></div>
                    <div class="folha-liquido"><span>Salário Líquido</span><strong>R$ ${formatMoney(folha.salario_liquido)}</strong></div>
                </div>
            </div>
        `;
    }

    async function buscarFolha() {
        const res = await apiGet(`folha.php?action=minha_folha&mes=${folhaMes.value}&ano=${folhaAno.value}`);
        if (res.success) renderFolha(res.folha);
        else showToast(res.message, 'error');
    }

    document.getElementById('btnBuscarFolha').addEventListener('click', buscarFolha);
    buscarFolha();
</script>

<?php require_once __DIR__ . '/../includes/footer_funcionario.php'; ?>
