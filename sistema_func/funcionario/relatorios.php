<?php $tituloPagina = 'Relatórios'; require_once __DIR__ . '/../includes/header_funcionario.php'; ?>

<section class="page-header">
    <h1>Relatórios</h1>
    <p>Visualize seus relatórios de ponto e folha de pagamento.</p>
</section>

<div class="tabs">
    <button class="tab active" data-tab="ponto">Registro de Ponto</button>
    <button class="tab" data-tab="folha">Folha de Pagamento</button>
</div>

<div id="tabPonto" class="tab-content active">
    <div class="toolbar">
        <div class="form-group inline">
            <label for="relMes">Mês</label>
            <select id="relMes"><option value="0">Todos</option></select>
        </div>
        <div class="form-group inline">
            <label for="relAno">Ano</label>
            <select id="relAno"></select>
        </div>
        <button class="btn btn-primary" id="btnRelPonto">Filtrar</button>
    </div>
    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr><th>Data</th><th>Entrada</th><th>Saída</th></tr>
            </thead>
            <tbody id="tbodyPonto"></tbody>
        </table>
    </div>
</div>

<div id="tabFolha" class="tab-content">
    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Período</th><th>Salário</th><th>Recebido</th>
                    <th>Descontos</th><th>Desc. Faltas</th><th>VT</th><th>VA</th><th>Líquido</th>
                </tr>
            </thead>
            <tbody id="tbodyFolha"></tbody>
        </table>
    </div>
</div>

<script>
    const meses = ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'];
    const relMes = document.getElementById('relMes');
    const relAno = document.getElementById('relAno');
    const agora = new Date();

    meses.forEach((nome, i) => {
        const opt = document.createElement('option');
        opt.value = i + 1;
        opt.textContent = nome;
        relMes.appendChild(opt);
    });

    for (let ano = agora.getFullYear(); ano >= agora.getFullYear() - 3; ano--) {
        const opt = document.createElement('option');
        opt.value = ano;
        opt.textContent = ano;
        relAno.appendChild(opt);
    }

    document.querySelectorAll('.tab').forEach(tab => {
        tab.addEventListener('click', () => {
            document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
            tab.classList.add('active');
            document.getElementById('tab' + tab.dataset.tab.charAt(0).toUpperCase() + tab.dataset.tab.slice(1)).classList.add('active');
        });
    });

    async function carregarRelatorioPonto() {
        const mes = relMes.value;
        const ano = relAno.value;
        const res = await apiGet(`ponto.php?action=relatorio&mes=${mes}&ano=${ano}`);
        const tbody = document.getElementById('tbodyPonto');
        if (!res.success || !res.registros.length) {
            tbody.innerHTML = '<tr><td colspan="3">Nenhum registro encontrado.</td></tr>';
            return;
        }
        tbody.innerHTML = res.registros.map(r => `
            <tr>
                <td>${formatDate(r.data)}</td>
                <td>${r.hora_entrada}</td>
                <td>${r.hora_saida || '-'}</td>
            </tr>
        `).join('');
    }

    async function carregarRelatorioFolha() {
        const res = await apiGet('folha.php?action=relatorio');
        const tbody = document.getElementById('tbodyFolha');
        if (!res.success || !res.folhas.length) {
            tbody.innerHTML = '<tr><td colspan="8">Nenhuma folha encontrada.</td></tr>';
            return;
        }
        tbody.innerHTML = res.folhas.map(f => `
            <tr>
                <td>${f.mes_nome}/${f.ano}</td>
                <td>R$ ${formatMoney(f.salario)}</td>
                <td>${f.recebido_texto}</td>
                <td>R$ ${formatMoney(f.descontos_manuais)}</td>
                <td>R$ ${formatMoney(f.desconto_faltas)}</td>
                <td>R$ ${formatMoney(f.vale_transporte)}</td>
                <td>R$ ${formatMoney(f.vale_alimentacao)}</td>
                <td>R$ ${formatMoney(f.salario_liquido)}</td>
            </tr>
        `).join('');
    }

    document.getElementById('btnRelPonto').addEventListener('click', carregarRelatorioPonto);
    carregarRelatorioPonto();
    carregarRelatorioFolha();
</script>

<?php require_once __DIR__ . '/../includes/footer_funcionario.php'; ?>
