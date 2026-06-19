    </main>

    <div class="chat-widget" id="chatWidget">
        <button class="chat-toggle" id="chatToggle" title="Chat com administrador">💬</button>
        <div class="chat-panel hidden" id="chatPanel">
            <div class="chat-header">
                <strong>Chat com Admin</strong>
                <button class="chat-close" id="chatClose">&times;</button>
            </div>
            <div class="chat-messages" id="chatMessages"></div>
            <form class="chat-form" id="chatForm">
                <input type="text" id="chatInput" placeholder="Digite sua mensagem..." required>
                <button type="submit" class="btn btn-primary">Enviar</button>
            </form>
        </div>
    </div>

    <div class="toast-container" id="toastContainer"></div>

    <script>window.API_BASE = '../api';</script>
    <script src="../assets/js/common.js"></script>
    <script src="../assets/js/chat.js"></script>
    <?php if (!empty($extraScripts)): ?>
        <?php foreach ($extraScripts as $script): ?>
            <script src="<?= htmlspecialchars($script) ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>
