<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

require_once 'includes/conexao.php';

$tipo      = $_SESSION['tipo'];
$can_edit  = ($tipo === 'professor' || $tipo === 'administrador');
$action    = $_GET['action'] ?? 'listar';
$msg       = '';
$erro      = '';

// --- CREATE / EDIT ---
if ($can_edit && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo    = trim($_POST['titulo'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $prazo     = $_POST['prazo'] ?? '';
    $turma     = trim($_POST['turma'] ?? '');
    $edit_id   = (int)($_POST['edit_id'] ?? 0);

    if (!$titulo || !$descricao || !$prazo || !$turma) {
        $erro = 'Todos os campos são obrigatórios.';
        $action = $edit_id ? 'editar' : 'nova';
    } else {
        if ($edit_id) {
            $stmt = $pdo->prepare("UPDATE atividades SET titulo=?, descricao=?, prazo=?, turma=? WHERE id=?");
            $stmt->execute([$titulo, $descricao, $prazo, $turma, $edit_id]);
            $msg = 'Atividade atualizada com sucesso!';
        } else {
            $stmt = $pdo->prepare("INSERT INTO atividades (titulo, descricao, prazo, turma) VALUES (?,?,?,?)");
            $stmt->execute([$titulo, $descricao, $prazo, $turma]);
            $msg = 'Atividade cadastrada com sucesso!';
        }
        $action = 'listar';
    }
}

// --- DELETE ---
if ($can_edit && $action === 'excluir' && isset($_GET['id'])) {
    $pdo->prepare("DELETE FROM atividades WHERE id=?")->execute([(int)$_GET['id']]);
    $msg = 'Atividade excluída.';
    $action = 'listar';
}

// Load for edit
$at_edit = null;
if ($can_edit && $action === 'editar' && isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM atividades WHERE id=?");
    $stmt->execute([(int)$_GET['id']]);
    $at_edit = $stmt->fetch();
    if (!$at_edit) { $action = 'listar'; }
}

// Detail view
$at_detalhe = null;
if ($action === 'ver' && isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM atividades WHERE id=?");
    $stmt->execute([(int)$_GET['id']]);
    $at_detalhe = $stmt->fetch();
    if (!$at_detalhe) { $action = 'listar'; }
}

// Listing with optional filter
$filtro_turma = trim($_GET['turma'] ?? '');
$atividades = [];
$turmas = [];
if ($action === 'listar') {
    $turmas = $pdo->query("SELECT DISTINCT turma FROM atividades ORDER BY turma")->fetchAll(PDO::FETCH_COLUMN);
    if ($filtro_turma) {
        $stmt = $pdo->prepare("SELECT * FROM atividades WHERE turma=? ORDER BY prazo ASC");
        $stmt->execute([$filtro_turma]);
        $atividades = $stmt->fetchAll();
    } else {
        $atividades = $pdo->query("SELECT * FROM atividades ORDER BY prazo ASC")->fetchAll();
    }
}

function prazoStatus($prazo) {
    $dias = (strtotime($prazo) - time()) / 86400;
    if ($dias < 0)  return ['label'=>'Expirado', 'class'=>'chip-danger'];
    if ($dias <= 3) return ['label'=>'Urgente', 'class'=>'chip-warning'];
    return ['label'=>'Em dia', 'class'=>'chip-green'];
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Atividades – EduGestão</title>
<link rel="stylesheet" href="css/style.css">
<style>
.chip-danger  { background:rgba(248,113,113,0.12); color:var(--danger); }
.chip-warning { background:rgba(251,191,36,0.12);  color:var(--warning); }
.chip-green   { background:rgba(52,211,153,0.12);  color:var(--success); }
</style>
</head>
<body>
<div class="app-wrapper">
    <?php include 'includes/sidebar.php'; ?>
    <div class="main-content">
        <div class="topbar">
            <span class="topbar-title">
                <?php
                if ($action === 'nova')   echo 'Nova Atividade';
                elseif ($action === 'editar') echo 'Editar Atividade';
                elseif ($action === 'ver')    echo 'Detalhes da Atividade';
                else                          echo 'Atividades';
                ?>
            </span>
            <div class="topbar-actions">
                <?php if ($action !== 'listar'): ?>
                    <a href="atividades.php" class="btn btn-ghost btn-sm">← Voltar</a>
                <?php elseif ($can_edit): ?>
                    <a href="atividades.php?action=nova" class="btn btn-primary btn-sm">+ Nova Atividade</a>
                <?php endif; ?>
            </div>
        </div>
        <div class="page-body">

            <?php if ($msg): ?><div class="alert alert-success">✅ <?= htmlspecialchars($msg) ?></div><?php endif; ?>
            <?php if ($erro): ?><div class="alert alert-danger">⚠️ <?= htmlspecialchars($erro) ?></div><?php endif; ?>

            <?php if (($action === 'nova' || $action === 'editar') && $can_edit): ?>
            <!-- FORM -->
            <div class="card" style="max-width:640px">
                <div class="card-header">
                    <h3><?= $action === 'editar' ? 'Editar Atividade' : 'Cadastrar Nova Atividade' ?></h3>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <?php if ($action === 'editar' && $at_edit): ?>
                            <input type="hidden" name="edit_id" value="<?= $at_edit['id'] ?>">
                        <?php endif; ?>
                        <div class="form-group">
                            <label class="form-label">Título</label>
                            <input type="text" name="titulo" class="form-control"
                                   placeholder="Ex: Lista de exercícios – Cap. 5"
                                   value="<?= htmlspecialchars($at_edit['titulo'] ?? $_POST['titulo'] ?? '') ?>" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Descrição</label>
                            <textarea name="descricao" class="form-control" rows="5"
                                      placeholder="Instruções da atividade..." required><?= htmlspecialchars($at_edit['descricao'] ?? $_POST['descricao'] ?? '') ?></textarea>
                        </div>
                        <div class="grid-2">
                            <div class="form-group">
                                <label class="form-label">Turma</label>
                                <input type="text" name="turma" class="form-control"
                                       placeholder="Ex: 3A, Turma 2, 9º ano"
                                       value="<?= htmlspecialchars($at_edit['turma'] ?? $_POST['turma'] ?? '') ?>" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Prazo de entrega</label>
                                <input type="date" name="prazo" class="form-control"
                                       value="<?= $at_edit['prazo'] ?? $_POST['prazo'] ?? '' ?>" required>
                            </div>
                        </div>
                        <div style="display:flex;gap:12px">
                            <button type="submit" class="btn btn-primary">
                                <?= $action === 'editar' ? '💾 Salvar' : '📚 Cadastrar' ?>
                            </button>
                            <a href="atividades.php" class="btn btn-ghost">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>

            <?php elseif ($action === 'ver' && $at_detalhe): ?>
            <!-- DETAIL -->
            <div class="card" style="max-width:720px">
                <div class="card-header">
                    <h3><?= htmlspecialchars($at_detalhe['titulo']) ?></h3>
                    <?php $s = prazoStatus($at_detalhe['prazo']); ?>
                    <span class="chip <?= $s['class'] ?>"><?= $s['label'] ?></span>
                </div>
                <div class="card-body">
                    <div style="display:flex;gap:16px;margin-bottom:20px;flex-wrap:wrap">
                        <div>
                            <div class="form-label">Turma</div>
                            <span class="chip chip-blue"><?= htmlspecialchars($at_detalhe['turma']) ?></span>
                        </div>
                        <div>
                            <div class="form-label">Prazo</div>
                            <span style="font-weight:600">📅 <?= date('d/m/Y', strtotime($at_detalhe['prazo'])) ?></span>
                        </div>
                    </div>
                    <div class="form-label">Descrição</div>
                    <p style="line-height:1.8;white-space:pre-wrap"><?= htmlspecialchars($at_detalhe['descricao']) ?></p>
                    <?php if ($can_edit): ?>
                    <hr class="divider">
                    <div style="display:flex;gap:10px">
                        <a href="atividades.php?action=editar&id=<?= $at_detalhe['id'] ?>" class="btn btn-ghost btn-sm">✏️ Editar</a>
                        <a href="atividades.php?action=excluir&id=<?= $at_detalhe['id'] ?>"
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Excluir esta atividade?')">🗑️ Excluir</a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <?php else: ?>
            <!-- LIST -->
            <?php if ($turmas): ?>
            <div style="margin-bottom:20px;display:flex;gap:8px;flex-wrap:wrap;align-items:center">
                <span class="text-muted" style="font-size:13px">Filtrar por turma:</span>
                <a href="atividades.php" class="chip <?= !$filtro_turma ? 'chip-blue' : 'chip chip-ghost' ?>"
                   style="text-decoration:none;cursor:pointer">Todas</a>
                <?php foreach ($turmas as $t): ?>
                    <a href="atividades.php?turma=<?= urlencode($t) ?>"
                       class="chip <?= $filtro_turma === $t ? 'chip-blue' : '' ?>"
                       style="text-decoration:none;cursor:pointer;<?= $filtro_turma !== $t ? 'background:var(--surface2);color:var(--muted)' : '' ?>">
                        <?= htmlspecialchars($t) ?>
                    </a>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <?php if (empty($atividades)): ?>
                <div class="card">
                    <div class="empty-state">
                        <div class="icon">📚</div>
                        <p>Nenhuma atividade cadastrada<?= $filtro_turma ? ' para esta turma' : '' ?>.</p>
                        <?php if ($can_edit): ?><a href="atividades.php?action=nova" class="btn btn-primary" style="margin-top:16px">+ Cadastrar Atividade</a><?php endif; ?>
                    </div>
                </div>
            <?php else: ?>
                <div class="card">
                    <div class="table-wrapper">
                        <table>
                            <thead>
                                <tr>
                                    <th>Atividade</th>
                                    <th>Turma</th>
                                    <th>Prazo</th>
                                    <th>Status</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($atividades as $at): ?>
                                <?php $s = prazoStatus($at['prazo']); ?>
                                <tr>
                                    <td>
                                        <a href="atividades.php?action=ver&id=<?= $at['id'] ?>"
                                           style="color:var(--text);text-decoration:none;font-weight:500">
                                            <?= htmlspecialchars($at['titulo']) ?>
                                        </a>
                                    </td>
                                    <td><span class="chip chip-blue"><?= htmlspecialchars($at['turma']) ?></span></td>
                                    <td class="text-muted" style="white-space:nowrap">
                                        <?= date('d/m/Y', strtotime($at['prazo'])) ?>
                                    </td>
                                    <td><span class="chip <?= $s['class'] ?>"><?= $s['label'] ?></span></td>
                                    <td>
                                        <div class="actions-col">
                                            <a href="atividades.php?action=ver&id=<?= $at['id'] ?>" class="btn btn-ghost btn-sm">👁 Ver</a>
                                            <?php if ($can_edit): ?>
                                                <a href="atividades.php?action=editar&id=<?= $at['id'] ?>" class="btn btn-ghost btn-sm">✏️</a>
                                                <a href="atividades.php?action=excluir&id=<?= $at['id'] ?>"
                                                   class="btn btn-danger btn-sm"
                                                   onclick="return confirm('Excluir?')">🗑️</a>
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
