</main>
<footer class="footer-sesi mt-auto">
    <div class="container py-5">
        <div class="row g-4 align-items-center justify-content-between">
            <div class="col-lg-7">
                <p class="text-secondary mb-2 small text-uppercase tracking-wide">Desenvolvimento</p>
                <h5 class="fw-bold text-dark mb-3">Sistema desenvolvido para os Jogos Interclasse SESI</h5>
                <p class="text-muted mb-0">Cadastro de alunos do 6º ao 9º ano e painel administrativo com estatísticas.</p>
            </div>
            <div class="col-lg-5">
                <div class="row g-3 justify-content-lg-end">
                    <div class="col-auto text-center dev-card">
                        <img src="<?= ($rootPrefix ?? '') ?>assets/img/dev-placeholder.svg" alt="Desenvolvedor 1" width="72" height="72" class="rounded-circle border shadow-sm mb-2 dev-avatar">
                        <div class="small fw-semibold">Desenvolvedor 1</div>
                    </div>
                    <div class="col-auto text-center dev-card">
                        <img src="<?= ($rootPrefix ?? '') ?>assets/img/dev-placeholder.svg" alt="Desenvolvedor 2" width="72" height="72" class="rounded-circle border shadow-sm mb-2 dev-avatar">
                        <div class="small fw-semibold">Desenvolvedor 2</div>
                    </div>
                </div>
                <p class="text-muted small mt-3 mb-0 text-lg-end">Substitua fotos e nomes em <code>includes/footer.php</code> e arquivos em <code>assets/img/</code>.</p>
            </div>
        </div>
        <hr class="my-4 opacity-25">
        <p class="text-center text-muted small mb-0">&copy; <?= date('Y') ?> SESI — Jogos Interclasse</p>
    </div>
</footer>
<div class="toast-container position-fixed bottom-0 end-0 p-3" id="toastContainer"></div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?php if (!empty($extraScripts)) { echo $extraScripts; } ?>
</body>
</html>
