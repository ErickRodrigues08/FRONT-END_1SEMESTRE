<?php

declare(strict_types=1);

session_start();
if (empty($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Carregar jogos para renderizar os formulários server-side
$allGames = [];
try {
    require __DIR__ . '/../api/db.php';
    $allGames = $pdo->query(
        "SELECT * FROM games ORDER BY modulo, modalidade, FIELD(fase,'semifinal','final'), jogo_seq"
    )->fetchAll();
} catch (Throwable $e) {
    // Tabela ainda não existe — seção ficará com aviso
}

// Organizar jogos por [modulo][modalidade]
$gamesByModMod = [];
foreach ($allGames as $g) {
    $gamesByModMod[$g['modulo']][$g['modalidade']][] = $g;
}

$pageTitle = 'Painel administrativo';
$rootPrefix = '../';
$navVariant = 'admin';
$extraScripts =
    '<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>' .
    '<script src="../assets/js/dashboard.js" defer></script>' .
    '<script src="../assets/js/dashboard-games.js" defer></script>';
require __DIR__ . '/../includes/header.php';

// Helper para renderizar os cards de jogo de uma modalidade
function renderGameRows(array $games, string $faseLabel = ''): void
{
    foreach ($games as $g) {
        $id       = (int)$g['id'];
        $label    = htmlspecialchars($g['jogo_label'], ENT_QUOTES, 'UTF-8');
        $horario  = htmlspecialchars($g['horario'], ENT_QUOTES, 'UTF-8');
        $isFinal  = $g['fase'] === 'final';
        $faseBadgeCls = $isFinal
            ? 'bg-warning-subtle text-warning-emphasis border-warning-subtle'
            : 'bg-light text-muted';
        $faseText = $isFinal ? 'Final' : 'Semifinal';
        ?>
        <div class="game-edit-row border rounded-3 p-3 mb-3" data-game-id="<?= $id ?>">
            <div class="d-flex align-items-center gap-2 mb-3">
                <span class="bracket-game-num"><?= $label ?></span>
                <span class="bracket-game-time"><i class="bi bi-clock me-1"></i><?= $horario ?>h</span>
                <span class="ms-auto badge border small <?= $faseBadgeCls ?>"><?= $faseText ?></span>
            </div>
            <div class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label small text-muted mb-1" for="g-<?= $id ?>-t1">Time 1</label>
                    <input type="text" class="form-control form-control-sm game-t1"
                           id="g-<?= $id ?>-t1" maxlength="100" placeholder="Nome da turma">
                </div>
                <div class="col-auto d-flex align-items-end pb-1">
                    <span class="fw-bold text-muted">×</span>
                </div>
                <div class="col-md-4">
                    <label class="form-label small text-muted mb-1" for="g-<?= $id ?>-t2">Time 2</label>
                    <input type="text" class="form-control form-control-sm game-t2"
                           id="g-<?= $id ?>-t2" maxlength="100" placeholder="Nome da turma">
                </div>
                <div class="col-md-1">
                    <label class="form-label small text-muted mb-1" for="g-<?= $id ?>-p1">P1</label>
                    <input type="number" class="form-control form-control-sm game-p1"
                           id="g-<?= $id ?>-p1" min="0" max="99" placeholder="—">
                </div>
                <div class="col-auto d-flex align-items-end pb-1">
                    <span class="fw-bold text-muted">:</span>
                </div>
                <div class="col-md-1">
                    <label class="form-label small text-muted mb-1" for="g-<?= $id ?>-p2">P2</label>
                    <input type="number" class="form-control form-control-sm game-p2"
                           id="g-<?= $id ?>-p2" min="0" max="99" placeholder="—">
                </div>
            </div>
            <div class="row g-2 align-items-end mt-1">
                <div class="col-md-6">
                    <label class="form-label small text-muted mb-1" for="g-<?= $id ?>-venc">Vencedor</label>
                    <select class="form-select form-select-sm game-venc" id="g-<?= $id ?>-venc">
                        <option value="">— A definir —</option>
                    </select>
                </div>
                <div class="col-md-auto ms-auto">
                    <button type="button" class="btn btn-sesi btn-sm game-save-btn" data-id="<?= $id ?>">
                        <i class="bi bi-check2 me-1"></i>Salvar
                    </button>
                </div>
            </div>
        </div>
        <?php
    }
}
?>

<section class="py-4">
    <div class="container">
        <div class="d-flex flex-column flex-md-row justify-content-md-between align-items-md-end gap-2 mb-4">
            <div>
                <h1 class="h3 fw-bold mb-1">Painel administrativo</h1>
                <p class="text-muted mb-0">Visão geral das inscrições e lista de alunos.</p>
            </div>
            <button type="button" class="btn btn-outline-secondary btn-sm" id="btnRefresh">
                <i class="bi bi-arrow-clockwise me-1"></i>Atualizar dados
            </button>
        </div>

        <div class="row g-3 mb-4" id="kpiRow">
            <div class="col-6 col-lg">
                <div class="kpi-card p-3 h-100">
                    <div class="text-muted small text-uppercase">Total</div>
                    <div class="kpi-value" id="kpiTotal">—</div>
                    <div class="small text-muted">Alunos cadastrados</div>
                </div>
            </div>
            <div class="col-6 col-lg">
                <div class="kpi-card p-3 h-100">
                    <div class="text-muted small text-uppercase">Meninos</div>
                    <div class="kpi-value" id="kpiBoys">—</div>
                </div>
            </div>
            <div class="col-6 col-lg">
                <div class="kpi-card p-3 h-100">
                    <div class="text-muted small text-uppercase">Meninas</div>
                    <div class="kpi-value" id="kpiGirls">—</div>
                </div>
            </div>
            <div class="col-6 col-lg">
                <div class="kpi-card p-3 h-100">
                    <div class="text-muted small text-uppercase">Turma A / B</div>
                    <div class="kpi-value small lh-sm" id="kpiClass">—</div>
                </div>
            </div>
            <div class="col-12 col-lg">
                <div class="kpi-card p-3 h-100">
                    <div class="text-muted small text-uppercase mb-1">Por série</div>
                    <div class="d-flex flex-wrap gap-2 small" id="kpiGrades">—</div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-lg-7">
                <div class="glass-card p-3 p-md-4 h-100">
                    <h2 class="h6 fw-bold text-uppercase text-muted mb-3">Inscritos por modalidade</h2>
                    <div style="max-height: 320px;">
                        <canvas id="chartModalities"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="glass-card p-3 p-md-4 h-100">
                    <h2 class="h6 fw-bold text-uppercase text-muted mb-3">Modalidade × gênero</h2>
                    <div style="max-height: 320px;">
                        <canvas id="chartModGender"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="glass-card p-3 p-md-4 mb-3">
            <div class="row g-2 align-items-end mb-3">
                <div class="col-md-4 col-lg-3">
                    <label class="form-label small text-muted mb-1" for="filterQ">Buscar</label>
                    <input type="search" class="form-control" id="filterQ" placeholder="Nome do aluno">
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label small text-muted mb-1" for="filterGrade">Série</label>
                    <select class="form-select" id="filterGrade">
                        <option value="all">Todas</option>
                        <option value="6">6º</option>
                        <option value="7">7º</option>
                        <option value="8">8º</option>
                        <option value="9">9º</option>
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label small text-muted mb-1" for="filterClass">Turma</label>
                    <select class="form-select" id="filterClass">
                        <option value="all">Todas</option>
                        <option value="A">A</option>
                        <option value="B">B</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small text-muted mb-1" for="filterGender">Gênero</label>
                    <select class="form-select" id="filterGender">
                        <option value="all">Todos</option>
                        <option value="masculino">Masculino</option>
                        <option value="feminino">Feminino</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-muted mb-1" for="filterModality">Modalidade</label>
                    <select class="form-select" id="filterModality">
                        <option value="all">Todas</option>
                        <option value="handebol_feminino">Handebol Fem.</option>
                        <option value="handebol_masculino">Handebol Masc.</option>
                        <option value="volei_misto">Vôlei Misto</option>
                    </select>
                </div>
            </div>

            <div class="table-wrap d-none d-md-block">
                <div class="table-responsive">
                    <table class="table table-hover table-sesi align-middle mb-0" id="tableStudents">
                        <thead>
                            <tr>
                                <th class="sortable-th" data-sort="full_name">Nome <i class="bi bi-chevron-expand text-muted"></i></th>
                                <th class="sortable-th" data-sort="age">Idade</th>
                                <th class="sortable-th" data-sort="grade">Série</th>
                                <th class="sortable-th" data-sort="class">Turma</th>
                                <th class="sortable-th" data-sort="gender">Gênero</th>
                                <th>Modalidades</th>
                                <th class="text-end">Ações</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody"></tbody>
                    </table>
                </div>
            </div>

            <div class="d-md-none" id="cardsMobile"></div>
        </div>

        <!-- ===== Seção: Tabela de Jogos ===== -->
        <div class="d-flex flex-column flex-md-row justify-content-md-between align-items-md-end gap-2 mb-4 mt-5">
            <div>
                <h2 class="h4 fw-bold mb-1"><i class="bi bi-trophy me-2 text-sesi-red"></i>Tabela de Jogos</h2>
                <p class="text-muted mb-0 small">Configure times, placares e vencedores. O vencedor da semifinal é propagado automaticamente para a final.</p>
            </div>
            <button type="button" class="btn btn-outline-secondary btn-sm" id="btnRefreshGames">
                <i class="bi bi-arrow-clockwise me-1"></i>Atualizar jogos
            </button>
        </div>

        <?php if (empty($allGames)): ?>
        <div class="alert alert-warning rounded-3">
            <i class="bi bi-exclamation-triangle me-2"></i>
            Nenhum jogo encontrado. Execute <code>api/migrate_games.php</code> no navegador para criar e popular a tabela de jogos.
        </div>
        <?php else: ?>

        <!-- Pills de módulo -->
        <div class="d-flex justify-content-center mb-4">
            <div class="modulo-pill-wrap d-flex gap-2 p-1" role="tablist">
                <button class="modulo-pill admin-modulo-pill active" id="admin-pill-mod1"
                        data-admin-modulo-target="#admin-mod1" type="button" role="tab"
                        aria-selected="true" aria-controls="admin-mod1">
                    <i class="bi bi-calendar-event me-1"></i>
                    <span class="d-block fw-bold">Módulo I</span>
                    <span class="d-block small opacity-75">6º e 7º anos · Terça 19/05</span>
                </button>
                <button class="modulo-pill admin-modulo-pill" id="admin-pill-mod2"
                        data-admin-modulo-target="#admin-mod2" type="button" role="tab"
                        aria-selected="false" aria-controls="admin-mod2">
                    <i class="bi bi-calendar2-check me-1"></i>
                    <span class="d-block fw-bold">Módulo II</span>
                    <span class="d-block small opacity-75">8º e 9º anos · Quarta 20/05</span>
                </button>
            </div>
        </div>

        <!-- Conteúdo por módulo -->
        <?php foreach ([1 => 'admin-mod1', 2 => 'admin-mod2'] as $mod => $modId): ?>
        <?php $isActive = $mod === 1; ?>
        <div class="admin-tab-pane<?= $isActive ? ' show active' : '' ?>"
             id="<?= $modId ?>" role="tabpanel">

            <?php if (empty($gamesByModMod[$mod])): ?>
            <p class="text-muted small">Nenhum jogo para este módulo.</p>
            <?php else: ?>
            <div class="row g-4">

                <?php
                $modalidades = [
                    'volei' => [
                        'titulo'    => $mod === 1 ? 'Vôlei Câmbio Misto' : 'Vôlei Misto',
                        'icone'     => 'bi-volleyball',
                        'badge'     => '<span class="badge-modality ms-auto">Misto</span>',
                        'cardClass' => 'sport-card glass-card p-4',
                        'subtitulo' => $mod === 1
                            ? 'Módulo I · 6º e 7º anos · Terça, 19/05/2026'
                            : 'Módulo II · 8º e 9º anos · Quarta, 20/05/2026',
                    ],
                    'handebol_feminino' => [
                        'titulo'    => 'Handebol Feminino',
                        'icone'     => 'bi-dribbble',
                        'badge'     => '<span class="badge-modality badge-modality-fem ms-auto">Feminino</span>',
                        'cardClass' => 'sport-card sport-card-feminino glass-card p-4',
                        'subtitulo' => $mod === 1
                            ? 'Módulo I · 6º e 7º anos · Terça, 19/05/2026'
                            : 'Módulo II · 8º e 9º anos · Quarta, 20/05/2026',
                    ],
                    'handebol_masculino' => [
                        'titulo'    => 'Handebol Masculino',
                        'icone'     => 'bi-dribbble',
                        'badge'     => '<span class="badge-modality badge-modality-masc ms-auto">Masculino</span>',
                        'cardClass' => 'sport-card sport-card-masculino glass-card p-4',
                        'subtitulo' => $mod === 1
                            ? 'Módulo I · 6º e 7º anos · Terça, 19/05/2026'
                            : 'Módulo II · 8º e 9º anos · Quarta, 20/05/2026',
                    ],
                ];
                foreach ($modalidades as $modalKey => $meta):
                    $jogos = $gamesByModMod[$mod][$modalKey] ?? [];
                    if (empty($jogos)) continue;
                ?>
                <div class="col-12">
                    <div class="<?= $meta['cardClass'] ?>">
                        <div class="d-flex align-items-center gap-3 mb-4">
                            <span class="icon-bubble icon-bubble-lg"><i class="bi <?= $meta['icone'] ?>"></i></span>
                            <div>
                                <h5 class="fw-bold mb-0"><?= $meta['titulo'] ?></h5>
                                <span class="text-muted small"><?= $meta['subtitulo'] ?></span>
                            </div>
                            <?= $meta['badge'] ?>
                        </div>
                        <?php renderGameRows($jogos); ?>
                    </div>
                </div>
                <?php endforeach; ?>

            </div>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>

        <?php endif; // end !empty($allGames) ?>

    </div>
</section>

<!-- Modal editar -->
<div class="modal fade" id="modalEdit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h2 class="modal-title h5 fw-bold">Editar cadastro</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formEdit" novalidate>
                    <input type="hidden" id="edit_id">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label" for="edit_full_name">Nome completo</label>
                            <input type="text" class="form-control" id="edit_full_name" required minlength="3">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="edit_age">Idade</label>
                            <input type="number" class="form-control" id="edit_age" required min="10" max="18">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="edit_grade">Série</label>
                            <select class="form-select" id="edit_grade" required>
                                <option value="6">6º ano</option>
                                <option value="7">7º ano</option>
                                <option value="8">8º ano</option>
                                <option value="9">9º ano</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="edit_class">Turma</label>
                            <select class="form-select" id="edit_class" required>
                                <option value="A">A</option>
                                <option value="B">B</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label d-block">Gênero</label>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="edit_gender" id="edit_gender_m" value="masculino" required>
                                    <label class="form-check-label" for="edit_gender_m">Masculino</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="edit_gender" id="edit_gender_f" value="feminino" required>
                                    <label class="form-check-label" for="edit_gender_f">Feminino</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Modalidades (máx. 2)</label>
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <div class="form-check p-2 border rounded-3">
                                        <input class="form-check-input edit-mod" type="checkbox" value="handebol_feminino" id="edit_mod_hf" data-gender="feminino">
                                        <label class="form-check-label small" for="edit_mod_hf">Handebol Fem.</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check p-2 border rounded-3">
                                        <input class="form-check-input edit-mod" type="checkbox" value="handebol_masculino" id="edit_mod_hm" data-gender="masculino">
                                        <label class="form-check-label small" for="edit_mod_hm">Handebol Masc.</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check p-2 border rounded-3">
                                        <input class="form-check-input edit-mod" type="checkbox" value="volei_misto" id="edit_mod_vm" data-gender="both">
                                        <label class="form-check-label small" for="edit_mod_vm">Vôlei Misto</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-outline-secondary rounded-3" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-sesi" id="btnSaveEdit">Salvar alterações</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal excluir -->
<div class="modal fade" id="modalDelete" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-body p-4">
                <h2 class="h5 fw-bold mb-2">Excluir aluno?</h2>
                <p class="text-muted mb-4 small" id="deleteStudentName"></p>
                <div class="d-flex gap-2 justify-content-end">
                    <button type="button" class="btn btn-outline-secondary rounded-3" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger rounded-3" id="btnConfirmDelete">Excluir</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="loader-overlay" id="pageLoader" aria-hidden="true">
    <div class="loader-spinner" role="status" aria-label="Carregando"></div>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>
