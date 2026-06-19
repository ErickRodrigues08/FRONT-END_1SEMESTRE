<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

require_once 'includes/conexao.php';

$tipo   = $_SESSION['tipo'];
$is_adm = ($tipo === 'administrador');
$action = $_GET['action'] ?? 'listar';
$msg    = '';
$erro   = '';

// --- CREATE / EDIT ---
if ($is_adm && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo    = trim($_POST['titulo'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $data_pub  = $_POST['data_publicacao'] ?? date('Y-m-d');
    $edit_id   = (int)($_POST['edit_id'] ?? 0);

    if (!$titulo || !$descricao) {
        $erro = 'Título e descrição são obrigatórios.';
        $action = $edit_id ? 'editar' : 'novo';
    } else {
        if ($edit_id) {
            $stmt = $pdo->prepare("UPDATE avisos SET titulo=?, descricao=?, data_publicacao=? WHERE id=?");
            $stmt->execute([$titulo, $descricao, $data_pub, $edit_id]);
            $msg = 'Aviso atualizado com sucesso!';
        } else {
            $stmt = $pdo->prepare("INSERT INTO avisos (titulo, descricao, data_publicacao) VALUES (?,?,?)");
            $stmt->execute([$titulo, $descricao, $data_pub]);
            $msg = 'Aviso publicado com sucesso!';
        }
        $action = 'listar';
    }
}

// --- DELETE ---
if ($is_adm && $action === 'excluir' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $pdo->prepare("DELETE FROM avisos WHERE id=?")->execute([$id]);
    $msg = 'Aviso excluído.';
    $action = 'listar';
}

// Load aviso for edit
$aviso_edit = null;
if ($is_adm && $action === 'editar' && isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM avisos WHERE id=?");
    $stmt->execute([(int)$_GET['id']]);
    $aviso_edit = $stmt->fetch();
    if (!$aviso_edit) { $action = 'listar'; }
}

// Load single aviso for detail view
$aviso_detalhe = null;
if ($action === 'ver' && isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM avisos WHERE id=?");
    $stmt->execute([(int)$_GET['id']]);
    $aviso_detalhe = $stmt->fetch();
    if (!$aviso_detalhe) { $action = 'listar'; }
}

// Listing
$avisos = [];
if ($action === 'listar') {
    $avisos = $pdo->query("SELECT * FROM avisos ORDER BY data_publicacao DESC")->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Avisos – EduGestão</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="app-wrapper">
    <?php include 'includes/sidebar.php'; ?>
    <div class="main-content">
        <div class="topbar">
            <span class="topbar-title">
                <?php
                if ($action === 'novo')   echo 'Novo Aviso';
                elseif ($action === 'editar') echo 'Editar Aviso';
                elseif ($action === 'ver')    echo 'Detalhes do Aviso';
                else                          echo 'Avisos';
                ?>
            </span>
            <div class="topbar-actions">
                <?php if ($action !== 'listar'): ?>
                    <a href="avisos.php" class="btn btn-ghost btn-sm">← Voltar</a>
                <?php elseif ($is_adm): ?>
                    <a href="avisos.php?action=novo" class="btn btn-primary btn-sm">+ Novo Aviso</a>
                <?php endif; ?>
            </div>
        </div>
        <div class="page-body">

            <?php if ($msg): ?><div class="alert alert-success">✅ <?= htmlspecialchars($msg) ?></div><?php endif; ?>
            <?php if ($erro): ?><div class="alert alert-danger">⚠️ <?= htmlspecialchars($erro) ?></div><?php endif; ?>

            <?php if (($action === 'novo' || $action === 'editar') && $is_adm): ?>
            <!-- FORM -->
            <div class="card" style="max-width:640px">
                <div class="card-header">
                    <h3><?= $action === 'editar' ? 'Editar Aviso' : 'Publicar Novo Aviso' ?></h3>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <?php if ($action === 'editar' && $aviso_edit): ?>
                            <input type="hidden" name="edit_id" value="<?= $aviso_edit['id'] ?>">
                        <?php endif; ?>
                        <div class="form-group">
                            <label class="form-label">Título</label>
                            <input type="text" name="titulo" class="form-control"
                                   placeholder="Título do aviso"
                                   value="<?= htmlspecialchars($aviso_edit['titulo'] ?? $_POST['titulo'] ?? '') ?>" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Descrição</label>
                            <textarea name="descricao" class="form-control" rows="5"
                                      placeholder="Conteúdo do aviso..." required><?= htmlspecialchars($aviso_edit['descricao'] ?? $_POST['descricao'] ?? '') ?></textarea>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Data de Publicação</label>
                            <input type="date" name="data_publicacao" class="form-control"
                                   value="<?= $aviso_edit['data_publicacao'] ?? date('Y-m-d') ?>">
                        </div>
                        <div style="display:flex;gap:12px">
                            <button type="submit" class="btn btn-primary">
                                <?= $action === 'editar' ? '💾 Salvar alterações' : '📢 Publicar Aviso' ?>
                            </button>
                            <a href="avisos.php" class="btn btn-ghost">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>

            <?php elseif ($action === 'ver' && $aviso_detalhe): ?>
            <!-- DETALHE -->
            <div class="card" style="max-width:720px">
                <div class="card-header">
                    <h3><?= htmlspecialchars($aviso_detalhe['titulo']) ?></h3>
                    <span class="text-muted" style="font-size:13px">
                        📅 <?= date('d/m/Y', strtotime($aviso_detalhe['data_publicacao'])) ?>
                    </span>
                </div>
                <div class="card-body">
                    <p style="line-height:1.8;white-space:pre-wrap"><?= htmlspecialchars($aviso_detalhe['descricao']) ?></p>
                    <?php if ($is_adm): ?>
                    <hr class="divider">
                    <div style="display:flex;gap:10px">
                        <a href="avisos.php?action=editar&id=<?= $aviso_detalhe['id'] ?>" class="btn btn-ghost btn-sm">✏️ Editar</a>
                        <a href="avisos.php?action=excluir&id=<?= $aviso_detalhe['id'] ?>"
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Excluir este aviso?')">🗑️ Excluir</a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <?php else: ?>
            <!-- LIST -->
            <?php if (empty($avisos)): ?>
                <div class="card">
                    <div class="empty-state">
                        <div class="icon">📢</div>
                        <p>Nenhum aviso publicado ainda.</p>
                        <?php if ($is_adm): ?><a href="avisos.php?action=novo" class="btn btn-primary" style="margin-top:16px">+ Publicar Aviso</a><?php endif; ?>
                    </div>
                </div>
            <?php else: ?>
                <div class="card">
                    <div class="table-wrapper">
                        <table>
                            <thead>
                                <tr>
                                    <th>Título</th>
                                    <th>Data</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($avisos as $av): ?>
                                <tr>
                                    <td>
                                        <a href="avisos.php?action=ver&id=<?= $av['id'] ?>"
                                           style="color:var(--text);text-decoration:none;font-weight:500">
                                            <?= htmlspecialchars($av['titulo']) ?>
                                        </a>
                                        <div class="text-muted" style="font-size:12px;margin-top:2px">
                                            <?= mb_substr(htmlspecialchars($av['descricao']), 0, 80) ?>…
                                        </div>
                                    </td>
                                    <td class="text-muted" style="white-space:nowrap">
                                        <?= date('d/m/Y', strtotime($av['data_publicacao'])) ?>
                                    </td>
                                    <td>
                                        <div class="actions-col">
                                            <a href="avisos.php?action=ver&id=<?= $av['id'] ?>" class="btn btn-ghost btn-sm">👁 Ver</a>
                                            <?php if ($is_adm): ?>
                                                <a href="avisos.php?action=editar&id=<?= $av['id'] ?>" class="btn btn-ghost btn-sm">✏️</a>
                                                <a href="avisos.php?action=excluir&id=<?= $av['id'] ?>"
                                                   class="btn btn-danger btn-sm"
                                                   onclick="return confirm('Excluir este aviso?')">🗑️</a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>
            <?php endif; ?>

        </div>
    </div>
</div>
</body>
</html>
