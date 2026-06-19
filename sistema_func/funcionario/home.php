<?php $tituloPagina = 'Início'; require_once __DIR__ . '/../includes/header_funcionario.php'; ?>

<section class="page-header">
    <h1>Bem-vindo(a)</h1>
    <p>Gerencie seu ponto, folha de pagamento, serviços e relatórios.</p>
</section>

<div class="cards-grid" id="cardsHome">
    <div class="info-card">
        <h3>Seu ID</h3>
        <p id="homeId">-</p>
    </div>
    <div class="info-card">
        <h3>E-mail</h3>
        <p id="homeEmail">-</p>
    </div>
    <div class="info-card">
        <h3>Telefone</h3>
        <p id="homeTelefone">-</p>
    </div>
</div>

<div class="quick-links">
    <a href="ponto.php" class="quick-link-card">Registrar Ponto</a>
    <a href="folha.php" class="quick-link-card">Ver Folha</a>
    <a href="servicos.php" class="quick-link-card">Meus Serviços</a>
    <a href="relatorios.php" class="quick-link-card">Relatórios</a>
</div>

<script>
    async function carregarPerfil() {
        const res = await apiGet('funcionarios.php?action=meu_perfil');
        if (res.success && res.funcionario) {
            document.getElementById('homeId').textContent = res.funcionario.id;
            document.getElementById('homeEmail').textContent = res.funcionario.email;
            document.getElementById('homeTelefone').textContent = res.funcionario.telefone;
        }
    }
    carregarPerfil();
</script>

<?php require_once __DIR__ . '/../includes/footer_funcionario.php'; ?>
