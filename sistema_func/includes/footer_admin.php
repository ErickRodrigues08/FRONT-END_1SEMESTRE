    </main>

    <div class="toast-container" id="toastContainer"></div>

    <script>window.API_BASE = '../api';</script>
    <script src="../assets/js/common.js"></script>
    <?php if (!empty($extraScripts)): ?>
        <?php foreach ($extraScripts as $script): ?>
            <script src="<?= htmlspecialchars($script) ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>
