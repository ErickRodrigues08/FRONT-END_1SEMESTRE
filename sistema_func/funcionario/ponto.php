<?php
$tituloPagina = 'Registro de Ponto';
$extraScripts = ['../assets/js/calendar.js'];
require_once __DIR__ . '/../includes/header_funcionario.php';
?>

<section class="page-header">
    <h1>Registro de Ponto</h1>
    <p>Selecione o mês e clique em um dia para registrar entrada e saída.</p>
</section>

<div class="toolbar">
    <div class="form-group inline">
        <label for="selectMes">Mês</label>
        <select id="selectMes"></select>
    </div>
    <div class="form-group inline">
        <label for="selectAno">Ano</label>
        <select id="selectAno"></select>
    </div>
    <button class="btn btn-warning" id="btnInformarFalta">Informar Falta</button>
</div>

<div class="calendar-legend">
    <span><span class="legend-box dia-registrado"></span> Ponto registrado</span>
    <span><span class="legend-box dia-falta"></span> Falta informada</span>
</div>

<div id="calendario" class="calendario"></div>

<div class="modal hidden" id="modalPonto">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Registrar Ponto - <span id="dataPontoLabel"></span></h3>
            <button class="modal-close" data-close="modalPonto">&times;</button>
        </div>
        <form id="formPonto">
            <input type="hidden" id="dataPonto">
            <div class="form-group">
                <label for="horaEntrada">Hora de Entrada</label>
                <input type="time" id="horaEntrada" required>
            </div>
            <div class="form-group">
                <label for="horaSaida">Hora de Saída</label>
                <input type="time" id="horaSaida">
            </div>
            <button type="submit" class="btn btn-primary">Salvar Ponto</button>
        </form>
    </div>
</div>

<div class="modal hidden" id="modalFalta">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Informar Falta</h3>
            <button class="modal-close" data-close="modalFalta">&times;</button>
        </div>
        <form id="formFalta" enctype="multipart/form-data">
            <div class="form-group">
                <label for="dataFalta">Data da Falta</label>
                <input type="date" id="dataFalta" required>
            </div>
            <div class="form-group">
                <label for="tituloFalta">Título</label>
                <input type="text" id="tituloFalta" placeholder="Ex: Atestado médico" required>
            </div>
            <div class="form-group">
                <label for="descricaoFalta">Descrição</label>
                <textarea id="descricaoFalta" rows="4" required></textarea>
            </div>
            <div class="form-group">
                <label for="anexoFalta">Anexar atestado (PDF, JPG, PNG)</label>
                <input type="file" id="anexoFalta" accept=".pdf,.jpg,.jpeg,.png">
            </div>
            <button type="submit" class="btn btn-warning">Enviar Falta</button>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer_funcionario.php'; ?>
