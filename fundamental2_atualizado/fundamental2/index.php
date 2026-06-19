<?php
$pageTitle = 'Inscrição — Jogos Interclasse SESI';
$activeNav = 'home';
$rootPrefix = '';
$navVariant = 'public';
$extraScripts = '<script src="assets/js/cadastro.js" defer></script>';
require __DIR__ . '/includes/header.php';
?>

<section class="hero-section">
    <div class="container">
        <div class="row align-items-center g-4">
            <div class="col-lg-7 reveal">
                <p class="text-uppercase text-sesi-red fw-semibold small mb-2">Ensino fundamental · 6º ao 9º ano</p>
                <h1 class="hero-title mb-3">Inscrições para os <span class="text-sesi-red">Jogos Interclasse</span></h1>
                <p class="hero-lead mb-0">Preencha o formulário abaixo com atenção. Você pode escolher até <strong>duas modalidades</strong>. Boa sorte!</p>
            </div>
            <div class="col-lg-5">
                <div class="glass-card p-4 reveal reveal-delay-1">
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <span class="icon-bubble"><i class="bi bi-trophy"></i></span>
                        <div>
                            <div class="fw-bold">SESI</div>
                            <div class="text-muted small">Esporte, saúde e integração</div>
                        </div>
                    </div>
                    <p class="small text-muted mb-0">Use o menu <strong>Login Administrador</strong> para acessar estatísticas e a lista de inscritos.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="datas" class="py-5">
    <div class="container">
        <div class="text-center mb-5 reveal">
            <h2 class="fw-bold">Datas oficiais dos jogos</h2>
            <p class="text-muted mb-0">Confira o dia da sua série e prepare-se para competir com respeito e fair play.</p>
        </div>
        <div class="row g-4">
            <div class="col-md-6 reveal reveal-delay-1">
                <div class="date-card p-4 h-100">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <span class="icon-bubble"><i class="bi bi-calendar-event"></i></span>
                        <span class="icon-bubble"><i class="bi bi-volleyball"></i></span>
                    </div>
                    <div class="date-badge mb-2">19/05/2026</div>
                    <h5 class="fw-bold mb-2">6º ano e 7º ano</h5>
                    <p class="text-muted small mb-0">Apresentação no horário combinado pela coordenação. Uniforme e documentação conforme regulamento interno.</p>
                </div>
            </div>
            <div class="col-md-6 reveal reveal-delay-2">
                <div class="date-card p-4 h-100">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <span class="icon-bubble"><i class="bi bi-calendar2-check"></i></span>
                        <span class="icon-bubble"><i class="bi bi-dribbble"></i></span>
                    </div>
                    <div class="date-badge mb-2">20/05/2026</div>
                    <h5 class="fw-bold mb-2">8º ano e 9º ano</h5>
                    <p class="text-muted small mb-0">Mesma estrutura esportiva com adaptações por faixa etária. Acompanhe avisos no mural da escola.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="tabela-jogos" class="py-5 bg-white border-top">
    <div class="container">
        <div class="text-center mb-4 reveal">
            <h2 class="fw-bold">Tabela dos Jogos</h2>
            <p class="text-muted mb-0">Torneio Interclasses 2026 · 1ª Etapa · Semifinais e Final</p>
        </div>

        <div class="d-flex justify-content-center mb-4 reveal reveal-delay-1">
            <div class="modulo-pill-wrap d-flex gap-2 p-1" role="tablist">
                <button class="modulo-pill active" id="pill-mod1" data-modulo-target="#mod1" type="button" role="tab" aria-selected="true" aria-controls="mod1">
                    <i class="bi bi-calendar-event me-1"></i>
                    <span class="d-block fw-bold">Módulo I</span>
                    <span class="d-block small opacity-75">6º e 7º anos · Terça 19/05</span>
                </button>
                <button class="modulo-pill" id="pill-mod2" data-modulo-target="#mod2" type="button" role="tab" aria-selected="false" aria-controls="mod2">
                    <i class="bi bi-calendar2-check me-1"></i>
                    <span class="d-block fw-bold">Módulo II</span>
                    <span class="d-block small opacity-75">8º e 9º anos · Quarta 20/05</span>
                </button>
            </div>
        </div>

        <div class="tab-content reveal reveal-delay-2">

            <!-- MÓDULO I -->
            <div class="tab-pane fade show active" id="mod1" role="tabpanel">
                <div class="row g-4">

                    <!-- Vôlei Câmbio Misto -->
                    <div class="col-12">
                        <div class="sport-card glass-card p-4">
                            <div class="d-flex align-items-center gap-3 mb-4">
                                <span class="icon-bubble icon-bubble-lg"><i class="bi bi-volleyball"></i></span>
                                <div>
                                    <h5 class="fw-bold mb-0">Vôlei Câmbio Misto</h5>
                                    <span class="text-muted small">Módulo I · 6º e 7º anos · Terça, 19/05/2026</span>
                                </div>
                                <span class="badge-modality ms-auto">Misto</span>
                            </div>
                            <div class="bracket-grid">
                                <div class="bracket-semis">
                                    <div class="bracket-phase-label">Semifinal</div>
                                    <div class="bracket-game mb-3">
                                        <div class="bracket-game-header">
                                            <span class="bracket-game-num">Jogo 1</span>
                                            <span class="bracket-game-time" data-game-time="1|volei|semifinal|1"><i class="bi bi-clock me-1"></i>07h00</span>
                                        </div>
                                        <div class="bracket-slot">
                                            <span class="bracket-slot-dot" data-game-dot="1|volei|semifinal|1|t1"></span>
                                            <span class="bracket-slot-name" data-game-slot="1|volei|semifinal|1|t1">A definir</span>
                                        </div>
                                        <div class="bracket-vs-line"><span>×</span></div>
                                        <div class="bracket-slot">
                                            <span class="bracket-slot-dot" data-game-dot="1|volei|semifinal|1|t2"></span>
                                            <span class="bracket-slot-name" data-game-slot="1|volei|semifinal|1|t2">A definir</span>
                                        </div>
                                    </div>
                                    <div class="bracket-game">
                                        <div class="bracket-game-header">
                                            <span class="bracket-game-num">Jogo 2</span>
                                            <span class="bracket-game-time" data-game-time="1|volei|semifinal|2"><i class="bi bi-clock me-1"></i>07h30</span>
                                        </div>
                                        <div class="bracket-slot">
                                            <span class="bracket-slot-dot" data-game-dot="1|volei|semifinal|2|t1"></span>
                                            <span class="bracket-slot-name" data-game-slot="1|volei|semifinal|2|t1">A definir</span>
                                        </div>
                                        <div class="bracket-vs-line"><span>×</span></div>
                                        <div class="bracket-slot">
                                            <span class="bracket-slot-dot" data-game-dot="1|volei|semifinal|2|t2"></span>
                                            <span class="bracket-slot-name" data-game-slot="1|volei|semifinal|2|t2">A definir</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="bracket-arrow-col">
                                    <div class="bracket-connector">
                                        <div class="bracket-connector-line"></div>
                                        <i class="bi bi-arrow-right-circle-fill bracket-connector-icon"></i>
                                        <div class="bracket-connector-line"></div>
                                    </div>
                                </div>
                                <div class="bracket-final-col">
                                    <div class="bracket-phase-label">Final</div>
                                    <div class="bracket-game bracket-game-final">
                                        <div class="bracket-game-header">
                                            <span class="bracket-game-num">Final</span>
                                            <span class="bracket-game-time" data-game-time="1|volei|final|1"><i class="bi bi-clock me-1"></i>08h00</span>
                                        </div>
                                        <div class="bracket-slot">
                                            <span class="bracket-slot-dot bracket-slot-dot-gold" data-game-dot="1|volei|final|1|t1"></span>
                                            <span class="bracket-slot-name" data-game-slot="1|volei|final|1|t1">Vencedor Jogo 1</span>
                                        </div>
                                        <div class="bracket-vs-line"><span>×</span></div>
                                        <div class="bracket-slot">
                                            <span class="bracket-slot-dot bracket-slot-dot-gold" data-game-dot="1|volei|final|1|t2"></span>
                                            <span class="bracket-slot-name" data-game-slot="1|volei|final|1|t2">Vencedor Jogo 2</span>
                                        </div>
                                    </div>
                                    <div class="champion-badge mt-3" id="champ-1-volei">
                                        <i class="bi bi-trophy-fill"></i> Campeão
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Handebol Feminino -->
                    <div class="col-12">
                        <div class="sport-card sport-card-feminino glass-card p-4">
                            <div class="d-flex align-items-center gap-3 mb-4">
                                <span class="icon-bubble icon-bubble-lg"><i class="bi bi-dribbble"></i></span>
                                <div>
                                    <h5 class="fw-bold mb-0">Handebol Feminino</h5>
                                    <span class="text-muted small">Módulo I · 6º e 7º anos · Terça, 19/05/2026</span>
                                </div>
                                <span class="badge-modality badge-modality-fem ms-auto">Feminino</span>
                            </div>
                            <div class="bracket-grid">
                                <div class="bracket-semis">
                                    <div class="bracket-phase-label">Semifinal</div>
                                    <div class="bracket-game mb-3">
                                        <div class="bracket-game-header">
                                            <span class="bracket-game-num">Jogo 3</span>
                                            <span class="bracket-game-time" data-game-time="1|handebol_feminino|semifinal|1"><i class="bi bi-clock me-1"></i>08h40</span>
                                        </div>
                                        <div class="bracket-slot">
                                            <span class="bracket-slot-dot" data-game-dot="1|handebol_feminino|semifinal|1|t1"></span>
                                            <span class="bracket-slot-name" data-game-slot="1|handebol_feminino|semifinal|1|t1">A definir</span>
                                        </div>
                                        <div class="bracket-vs-line"><span>×</span></div>
                                        <div class="bracket-slot">
                                            <span class="bracket-slot-dot" data-game-dot="1|handebol_feminino|semifinal|1|t2"></span>
                                            <span class="bracket-slot-name" data-game-slot="1|handebol_feminino|semifinal|1|t2">A definir</span>
                                        </div>
                                    </div>
                                    <div class="bracket-game">
                                        <div class="bracket-game-header">
                                            <span class="bracket-game-num">Jogo 4</span>
                                            <span class="bracket-game-time" data-game-time="1|handebol_feminino|semifinal|2"><i class="bi bi-clock me-1"></i>09h10</span>
                                        </div>
                                        <div class="bracket-slot">
                                            <span class="bracket-slot-dot" data-game-dot="1|handebol_feminino|semifinal|2|t1"></span>
                                            <span class="bracket-slot-name" data-game-slot="1|handebol_feminino|semifinal|2|t1">A definir</span>
                                        </div>
                                        <div class="bracket-vs-line"><span>×</span></div>
                                        <div class="bracket-slot">
                                            <span class="bracket-slot-dot" data-game-dot="1|handebol_feminino|semifinal|2|t2"></span>
                                            <span class="bracket-slot-name" data-game-slot="1|handebol_feminino|semifinal|2|t2">A definir</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="bracket-arrow-col">
                                    <div class="bracket-connector">
                                        <div class="bracket-connector-line"></div>
                                        <i class="bi bi-arrow-right-circle-fill bracket-connector-icon"></i>
                                        <div class="bracket-connector-line"></div>
                                    </div>
                                </div>
                                <div class="bracket-final-col">
                                    <div class="bracket-phase-label">Final</div>
                                    <div class="bracket-game bracket-game-final">
                                        <div class="bracket-game-header">
                                            <span class="bracket-game-num">Final</span>
                                            <span class="bracket-game-time" data-game-time="1|handebol_feminino|final|1"><i class="bi bi-clock me-1"></i>09h50</span>
                                        </div>
                                        <div class="bracket-slot">
                                            <span class="bracket-slot-dot bracket-slot-dot-gold" data-game-dot="1|handebol_feminino|final|1|t1"></span>
                                            <span class="bracket-slot-name" data-game-slot="1|handebol_feminino|final|1|t1">Vencedor Jogo 3</span>
                                        </div>
                                        <div class="bracket-vs-line"><span>×</span></div>
                                        <div class="bracket-slot">
                                            <span class="bracket-slot-dot bracket-slot-dot-gold" data-game-dot="1|handebol_feminino|final|1|t2"></span>
                                            <span class="bracket-slot-name" data-game-slot="1|handebol_feminino|final|1|t2">Vencedor Jogo 4</span>
                                        </div>
                                    </div>
                                    <div class="champion-badge mt-3" id="champ-1-handebol_feminino">
                                        <i class="bi bi-trophy-fill"></i> Campeão
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Handebol Masculino -->
                    <div class="col-12">
                        <div class="sport-card sport-card-masculino glass-card p-4">
                            <div class="d-flex align-items-center gap-3 mb-4">
                                <span class="icon-bubble icon-bubble-lg"><i class="bi bi-dribbble"></i></span>
                                <div>
                                    <h5 class="fw-bold mb-0">Handebol Masculino</h5>
                                    <span class="text-muted small">Módulo I · 6º e 7º anos · Terça, 19/05/2026</span>
                                </div>
                                <span class="badge-modality badge-modality-masc ms-auto">Masculino</span>
                            </div>
                            <div class="bracket-grid">
                                <div class="bracket-semis">
                                    <div class="bracket-phase-label">Semifinal</div>
                                    <div class="bracket-game mb-3">
                                        <div class="bracket-game-header">
                                            <span class="bracket-game-num">Jogo 5</span>
                                            <span class="bracket-game-time" data-game-time="1|handebol_masculino|semifinal|1"><i class="bi bi-clock me-1"></i>10h20</span>
                                        </div>
                                        <div class="bracket-slot">
                                            <span class="bracket-slot-dot" data-game-dot="1|handebol_masculino|semifinal|1|t1"></span>
                                            <span class="bracket-slot-name" data-game-slot="1|handebol_masculino|semifinal|1|t1">A definir</span>
                                        </div>
                                        <div class="bracket-vs-line"><span>×</span></div>
                                        <div class="bracket-slot">
                                            <span class="bracket-slot-dot" data-game-dot="1|handebol_masculino|semifinal|1|t2"></span>
                                            <span class="bracket-slot-name" data-game-slot="1|handebol_masculino|semifinal|1|t2">A definir</span>
                                        </div>
                                    </div>
                                    <div class="bracket-game">
                                        <div class="bracket-game-header">
                                            <span class="bracket-game-num">Jogo 6</span>
                                            <span class="bracket-game-time" data-game-time="1|handebol_masculino|semifinal|2"><i class="bi bi-clock me-1"></i>10h50</span>
                                        </div>
                                        <div class="bracket-slot">
                                            <span class="bracket-slot-dot" data-game-dot="1|handebol_masculino|semifinal|2|t1"></span>
                                            <span class="bracket-slot-name" data-game-slot="1|handebol_masculino|semifinal|2|t1">A definir</span>
                                        </div>
                                        <div class="bracket-vs-line"><span>×</span></div>
                                        <div class="bracket-slot">
                                            <span class="bracket-slot-dot" data-game-dot="1|handebol_masculino|semifinal|2|t2"></span>
                                            <span class="bracket-slot-name" data-game-slot="1|handebol_masculino|semifinal|2|t2">A definir</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="bracket-arrow-col">
                                    <div class="bracket-connector">
                                        <div class="bracket-connector-line"></div>
                                        <i class="bi bi-arrow-right-circle-fill bracket-connector-icon"></i>
                                        <div class="bracket-connector-line"></div>
                                    </div>
                                </div>
                                <div class="bracket-final-col">
                                    <div class="bracket-phase-label">Final</div>
                                    <div class="bracket-game bracket-game-final">
                                        <div class="bracket-game-header">
                                            <span class="bracket-game-num">Final</span>
                                            <span class="bracket-game-time" data-game-time="1|handebol_masculino|final|1"><i class="bi bi-clock me-1"></i>11h30</span>
                                        </div>
                                        <div class="bracket-slot">
                                            <span class="bracket-slot-dot bracket-slot-dot-gold" data-game-dot="1|handebol_masculino|final|1|t1"></span>
                                            <span class="bracket-slot-name" data-game-slot="1|handebol_masculino|final|1|t1">Vencedor Jogo 5</span>
                                        </div>
                                        <div class="bracket-vs-line"><span>×</span></div>
                                        <div class="bracket-slot">
                                            <span class="bracket-slot-dot bracket-slot-dot-gold" data-game-dot="1|handebol_masculino|final|1|t2"></span>
                                            <span class="bracket-slot-name" data-game-slot="1|handebol_masculino|final|1|t2">Vencedor Jogo 6</span>
                                        </div>
                                    </div>
                                    <div class="champion-badge mt-3" id="champ-1-handebol_masculino">
                                        <i class="bi bi-trophy-fill"></i> Campeão
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div><!-- /mod1 -->

            <!-- MÓDULO II -->
            <div class="tab-pane fade" id="mod2" role="tabpanel">
                <div class="row g-4">

                    <!-- Vôlei Misto -->
                    <div class="col-12">
                        <div class="sport-card glass-card p-4">
                            <div class="d-flex align-items-center gap-3 mb-4">
                                <span class="icon-bubble icon-bubble-lg"><i class="bi bi-volleyball"></i></span>
                                <div>
                                    <h5 class="fw-bold mb-0">Vôlei Misto</h5>
                                    <span class="text-muted small">Módulo II · 8º e 9º anos · Quarta, 20/05/2026</span>
                                </div>
                                <span class="badge-modality ms-auto">Misto</span>
                            </div>
                            <div class="bracket-grid">
                                <div class="bracket-semis">
                                    <div class="bracket-phase-label">Semifinal</div>
                                    <div class="bracket-game mb-3">
                                        <div class="bracket-game-header">
                                            <span class="bracket-game-num">Jogo 1</span>
                                            <span class="bracket-game-time" data-game-time="2|volei|semifinal|1"><i class="bi bi-clock me-1"></i>07h00</span>
                                        </div>
                                        <div class="bracket-slot">
                                            <span class="bracket-slot-dot" data-game-dot="2|volei|semifinal|1|t1"></span>
                                            <span class="bracket-slot-name" data-game-slot="2|volei|semifinal|1|t1">A definir</span>
                                        </div>
                                        <div class="bracket-vs-line"><span>×</span></div>
                                        <div class="bracket-slot">
                                            <span class="bracket-slot-dot" data-game-dot="2|volei|semifinal|1|t2"></span>
                                            <span class="bracket-slot-name" data-game-slot="2|volei|semifinal|1|t2">A definir</span>
                                        </div>
                                    </div>
                                    <div class="bracket-game">
                                        <div class="bracket-game-header">
                                            <span class="bracket-game-num">Jogo 2</span>
                                            <span class="bracket-game-time" data-game-time="2|volei|semifinal|2"><i class="bi bi-clock me-1"></i>07h30</span>
                                        </div>
                                        <div class="bracket-slot">
                                            <span class="bracket-slot-dot" data-game-dot="2|volei|semifinal|2|t1"></span>
                                            <span class="bracket-slot-name" data-game-slot="2|volei|semifinal|2|t1">A definir</span>
                                        </div>
                                        <div class="bracket-vs-line"><span>×</span></div>
                                        <div class="bracket-slot">
                                            <span class="bracket-slot-dot" data-game-dot="2|volei|semifinal|2|t2"></span>
                                            <span class="bracket-slot-name" data-game-slot="2|volei|semifinal|2|t2">A definir</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="bracket-arrow-col">
                                    <div class="bracket-connector">
                                        <div class="bracket-connector-line"></div>
                                        <i class="bi bi-arrow-right-circle-fill bracket-connector-icon"></i>
                                        <div class="bracket-connector-line"></div>
                                    </div>
                                </div>
                                <div class="bracket-final-col">
                                    <div class="bracket-phase-label">Final</div>
                                    <div class="bracket-game bracket-game-final">
                                        <div class="bracket-game-header">
                                            <span class="bracket-game-num">Final</span>
                                            <span class="bracket-game-time" data-game-time="2|volei|final|1"><i class="bi bi-clock me-1"></i>08h00</span>
                                        </div>
                                        <div class="bracket-slot">
                                            <span class="bracket-slot-dot bracket-slot-dot-gold" data-game-dot="2|volei|final|1|t1"></span>
                                            <span class="bracket-slot-name" data-game-slot="2|volei|final|1|t1">Vencedor Jogo 1</span>
                                        </div>
                                        <div class="bracket-vs-line"><span>×</span></div>
                                        <div class="bracket-slot">
                                            <span class="bracket-slot-dot bracket-slot-dot-gold" data-game-dot="2|volei|final|1|t2"></span>
                                            <span class="bracket-slot-name" data-game-slot="2|volei|final|1|t2">Vencedor Jogo 2</span>
                                        </div>
                                    </div>
                                    <div class="champion-badge mt-3" id="champ-2-volei">
                                        <i class="bi bi-trophy-fill"></i> Campeão
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Handebol Feminino -->
                    <div class="col-12">
                        <div class="sport-card sport-card-feminino glass-card p-4">
                            <div class="d-flex align-items-center gap-3 mb-4">
                                <span class="icon-bubble icon-bubble-lg"><i class="bi bi-dribbble"></i></span>
                                <div>
                                    <h5 class="fw-bold mb-0">Handebol Feminino</h5>
                                    <span class="text-muted small">Módulo II · 8º e 9º anos · Quarta, 20/05/2026</span>
                                </div>
                                <span class="badge-modality badge-modality-fem ms-auto">Feminino</span>
                            </div>
                            <div class="bracket-grid">
                                <div class="bracket-semis">
                                    <div class="bracket-phase-label">Semifinal</div>
                                    <div class="bracket-game mb-3">
                                        <div class="bracket-game-header">
                                            <span class="bracket-game-num">Jogo 3</span>
                                            <span class="bracket-game-time" data-game-time="2|handebol_feminino|semifinal|1"><i class="bi bi-clock me-1"></i>08h40</span>
                                        </div>
                                        <div class="bracket-slot">
                                            <span class="bracket-slot-dot" data-game-dot="2|handebol_feminino|semifinal|1|t1"></span>
                                            <span class="bracket-slot-name" data-game-slot="2|handebol_feminino|semifinal|1|t1">A definir</span>
                                        </div>
                                        <div class="bracket-vs-line"><span>×</span></div>
                                        <div class="bracket-slot">
                                            <span class="bracket-slot-dot" data-game-dot="2|handebol_feminino|semifinal|1|t2"></span>
                                            <span class="bracket-slot-name" data-game-slot="2|handebol_feminino|semifinal|1|t2">A definir</span>
                                        </div>
                                    </div>
                                    <div class="bracket-game">
                                        <div class="bracket-game-header">
                                            <span class="bracket-game-num">Jogo 4</span>
                                            <span class="bracket-game-time" data-game-time="2|handebol_feminino|semifinal|2"><i class="bi bi-clock me-1"></i>09h10</span>
                                        </div>
                                        <div class="bracket-slot">
                                            <span class="bracket-slot-dot" data-game-dot="2|handebol_feminino|semifinal|2|t1"></span>
                                            <span class="bracket-slot-name" data-game-slot="2|handebol_feminino|semifinal|2|t1">A definir</span>
                                        </div>
                                        <div class="bracket-vs-line"><span>×</span></div>
                                        <div class="bracket-slot">
                                            <span class="bracket-slot-dot" data-game-dot="2|handebol_feminino|semifinal|2|t2"></span>
                                            <span class="bracket-slot-name" data-game-slot="2|handebol_feminino|semifinal|2|t2">A definir</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="bracket-arrow-col">
                                    <div class="bracket-connector">
                                        <div class="bracket-connector-line"></div>
                                        <i class="bi bi-arrow-right-circle-fill bracket-connector-icon"></i>
                                        <div class="bracket-connector-line"></div>
                                    </div>
                                </div>
                                <div class="bracket-final-col">
                                    <div class="bracket-phase-label">Final</div>
                                    <div class="bracket-game bracket-game-final">
                                        <div class="bracket-game-header">
                                            <span class="bracket-game-num">Final</span>
                                            <span class="bracket-game-time" data-game-time="2|handebol_feminino|final|1"><i class="bi bi-clock me-1"></i>09h50</span>
                                        </div>
                                        <div class="bracket-slot">
                                            <span class="bracket-slot-dot bracket-slot-dot-gold" data-game-dot="2|handebol_feminino|final|1|t1"></span>
                                            <span class="bracket-slot-name" data-game-slot="2|handebol_feminino|final|1|t1">Vencedor Jogo 3</span>
                                        </div>
                                        <div class="bracket-vs-line"><span>×</span></div>
                                        <div class="bracket-slot">
                                            <span class="bracket-slot-dot bracket-slot-dot-gold" data-game-dot="2|handebol_feminino|final|1|t2"></span>
                                            <span class="bracket-slot-name" data-game-slot="2|handebol_feminino|final|1|t2">Vencedor Jogo 4</span>
                                        </div>
                                    </div>
                                    <div class="champion-badge mt-3" id="champ-2-handebol_feminino">
                                        <i class="bi bi-trophy-fill"></i> Campeão
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Handebol Masculino -->
                    <div class="col-12">
                        <div class="sport-card sport-card-masculino glass-card p-4">
                            <div class="d-flex align-items-center gap-3 mb-4">
                                <span class="icon-bubble icon-bubble-lg"><i class="bi bi-dribbble"></i></span>
                                <div>
                                    <h5 class="fw-bold mb-0">Handebol Masculino</h5>
                                    <span class="text-muted small">Módulo II · 8º e 9º anos · Quarta, 20/05/2026</span>
                                </div>
                                <span class="badge-modality badge-modality-masc ms-auto">Masculino</span>
                            </div>
                            <div class="bracket-grid">
                                <div class="bracket-semis">
                                    <div class="bracket-phase-label">Semifinal</div>
                                    <div class="bracket-game mb-3">
                                        <div class="bracket-game-header">
                                            <span class="bracket-game-num">Jogo 5</span>
                                            <span class="bracket-game-time" data-game-time="2|handebol_masculino|semifinal|1"><i class="bi bi-clock me-1"></i>10h20</span>
                                        </div>
                                        <div class="bracket-slot">
                                            <span class="bracket-slot-dot" data-game-dot="2|handebol_masculino|semifinal|1|t1"></span>
                                            <span class="bracket-slot-name" data-game-slot="2|handebol_masculino|semifinal|1|t1">A definir</span>
                                        </div>
                                        <div class="bracket-vs-line"><span>×</span></div>
                                        <div class="bracket-slot">
                                            <span class="bracket-slot-dot" data-game-dot="2|handebol_masculino|semifinal|1|t2"></span>
                                            <span class="bracket-slot-name" data-game-slot="2|handebol_masculino|semifinal|1|t2">A definir</span>
                                        </div>
                                    </div>
                                    <div class="bracket-game">
                                        <div class="bracket-game-header">
                                            <span class="bracket-game-num">Jogo 6</span>
                                            <span class="bracket-game-time" data-game-time="2|handebol_masculino|semifinal|2"><i class="bi bi-clock me-1"></i>10h50</span>
                                        </div>
                                        <div class="bracket-slot">
                                            <span class="bracket-slot-dot" data-game-dot="2|handebol_masculino|semifinal|2|t1"></span>
                                            <span class="bracket-slot-name" data-game-slot="2|handebol_masculino|semifinal|2|t1">A definir</span>
                                        </div>
                                        <div class="bracket-vs-line"><span>×</span></div>
                                        <div class="bracket-slot">
                                            <span class="bracket-slot-dot" data-game-dot="2|handebol_masculino|semifinal|2|t2"></span>
                                            <span class="bracket-slot-name" data-game-slot="2|handebol_masculino|semifinal|2|t2">A definir</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="bracket-arrow-col">
                                    <div class="bracket-connector">
                                        <div class="bracket-connector-line"></div>
                                        <i class="bi bi-arrow-right-circle-fill bracket-connector-icon"></i>
                                        <div class="bracket-connector-line"></div>
                                    </div>
                                </div>
                                <div class="bracket-final-col">
                                    <div class="bracket-phase-label">Final</div>
                                    <div class="bracket-game bracket-game-final">
                                        <div class="bracket-game-header">
                                            <span class="bracket-game-num">Final</span>
                                            <span class="bracket-game-time" data-game-time="2|handebol_masculino|final|1"><i class="bi bi-clock me-1"></i>11h30</span>
                                        </div>
                                        <div class="bracket-slot">
                                            <span class="bracket-slot-dot bracket-slot-dot-gold" data-game-dot="2|handebol_masculino|final|1|t1"></span>
                                            <span class="bracket-slot-name" data-game-slot="2|handebol_masculino|final|1|t1">Vencedor Jogo 5</span>
                                        </div>
                                        <div class="bracket-vs-line"><span>×</span></div>
                                        <div class="bracket-slot">
                                            <span class="bracket-slot-dot bracket-slot-dot-gold" data-game-dot="2|handebol_masculino|final|1|t2"></span>
                                            <span class="bracket-slot-name" data-game-slot="2|handebol_masculino|final|1|t2">Vencedor Jogo 6</span>
                                        </div>
                                    </div>
                                    <div class="champion-badge mt-3" id="champ-2-handebol_masculino">
                                        <i class="bi bi-trophy-fill"></i> Campeão
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div><!-- /mod2 -->

        </div>
    </div>
</section>

<section id="cadastro" class="py-5 bg-white border-top border-bottom">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-8">
                <div class="glass-card p-4 p-md-5 reveal reveal-delay-3">
                    <h2 class="fw-bold mb-4">Cadastro do aluno</h2>
                    <form id="formCadastro" novalidate>
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label for="full_name" class="form-label">Nome completo</label>
                                <input type="text" class="form-control" id="full_name" name="full_name" required minlength="3" maxlength="180" placeholder="Nome e sobrenome">
                                <div class="invalid-feedback">Informe o nome completo.</div>
                            </div>
                            <div class="col-md-4">
                                <label for="age" class="form-label">Idade</label>
                                <input type="number" class="form-control" id="age" name="age" required min="10" max="18" placeholder="Ex.: 13">
                                <div class="invalid-feedback">Idade entre 10 e 18.</div>
                            </div>
                            <div class="col-md-4">
                                <label for="grade" class="form-label">Série</label>
                                <select class="form-select" id="grade" name="grade" required>
                                    <option value="">Selecione</option>
                                    <option value="6">6º ano</option>
                                    <option value="7">7º ano</option>
                                    <option value="8">8º ano</option>
                                    <option value="9">9º ano</option>
                                </select>
                                <div class="invalid-feedback">Selecione a série.</div>
                            </div>
                            <div class="col-md-4">
                                <label for="class" class="form-label">Turma</label>
                                <select class="form-select" id="class" name="class" required>
                                    <option value="">Selecione</option>
                                    <option value="A">A</option>
                                    <option value="B">B</option>
                                </select>
                                <div class="invalid-feedback">Selecione a turma.</div>
                            </div>
                            <div class="col-12">
                                <label class="form-label d-block">Gênero</label>
                                <div class="d-flex flex-wrap gap-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="gender" id="gender_m" value="masculino" required>
                                        <label class="form-check-label" for="gender_m">Masculino</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="gender" id="gender_f" value="feminino" required>
                                        <label class="form-check-label" for="gender_f">Feminino</label>
                                    </div>
                                </div>
                                <div class="invalid-feedback d-block" id="genderFeedback" style="display:none;">Selecione uma opção.</div>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Modalidades esportivas <span class="text-muted fw-normal">(máx. 2)</span></label>
                                <div class="alert alert-danger modality-limit-alert py-2 small mb-2" id="modalityLimitAlert" role="alert">
                                    <i class="bi bi-exclamation-octagon me-1"></i> Você pode escolher no máximo <strong>2 modalidades</strong>. Desmarque uma para selecionar outra.
                                </div>
                                <div class="row g-2 modality-check">
                                    <div class="col-md-4">
                                        <div class="form-check p-3 rounded-3 border bg-light h-100">
                                            <input class="form-check-input modality-cb" type="checkbox" value="handebol_feminino" id="mod_hf" data-gender="feminino">
                                            <label class="form-check-label fw-semibold" for="mod_hf">Handebol Feminino</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check p-3 rounded-3 border bg-light h-100">
                                            <input class="form-check-input modality-cb" type="checkbox" value="handebol_masculino" id="mod_hm" data-gender="masculino">
                                            <label class="form-check-label fw-semibold" for="mod_hm">Handebol Masculino</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check p-3 rounded-3 border bg-light h-100">
                                            <input class="form-check-input modality-cb" type="checkbox" value="volei_misto" id="mod_vm" data-gender="both">
                                            <label class="form-check-label fw-semibold" for="mod_vm">Vôlei Misto</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="invalid-feedback d-block" id="modalityFeedback" style="display:none;">Escolha de 1 a 2 modalidades.</div>
                            </div>
                        </div>
                        <div class="d-flex flex-column flex-sm-row align-items-stretch align-items-sm-center gap-3 mt-4">
                            <button type="submit" class="btn btn-sesi" id="btnSubmit">
                                <span class="submit-label"><i class="bi bi-send-check me-2"></i>Enviar inscrição</span>
                            </button>
                            <button type="reset" class="btn btn-outline-secondary rounded-3">Limpar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="loader-overlay" id="pageLoader" aria-hidden="true">
    <div class="loader-spinner" role="status" aria-label="Carregando"></div>
</div>

<script>
(function () {
    // Tabs de módulo
    var pills = document.querySelectorAll('.modulo-pill[data-modulo-target]');
    if (!pills.length) return;
    pills.forEach(function (pill) {
        pill.addEventListener('click', function () {
            var targetSel = pill.getAttribute('data-modulo-target');
            var target = document.querySelector(targetSel);
            if (!target) return;
            pills.forEach(function (p) {
                p.classList.remove('active');
                p.setAttribute('aria-selected', 'false');
            });
            pill.classList.add('active');
            pill.setAttribute('aria-selected', 'true');
            var panes = target.parentElement.querySelectorAll('.tab-pane');
            panes.forEach(function (pane) {
                pane.classList.remove('show', 'active');
            });
            target.classList.add('show', 'active');
        });
    });
})();

// Carregar dados dos jogos via AJAX e atualizar brackets
(function () {
    'use strict';

    function applyGames(games) {
        games.forEach(function (g) {
            var base = g.modulo + '|' + g.modalidade + '|' + g.fase + '|' + g.jogo_seq;

            // Atualizar horário
            var timeEl = document.querySelector('[data-game-time="' + base + '"]');
            if (timeEl && g.horario) {
                timeEl.innerHTML = '<i class="bi bi-clock me-1"></i>' + g.horario.replace(':', 'h');
            }

            ['t1', 't2'].forEach(function (team, idx) {
                var val = idx === 0 ? g.time1 : g.time2;
                var slotEl = document.querySelector('[data-game-slot="' + base + '|' + team + '"]');
                var dotEl  = document.querySelector('[data-game-dot="'  + base + '|' + team + '"]');
                if (!slotEl) return;

                if (val) {
                    slotEl.textContent    = val;
                    slotEl.style.color     = '#1f2937';
                    slotEl.style.fontStyle = 'normal';
                    slotEl.style.fontWeight = '600';
                }

                // Exibir placar ao lado do nome
                var placar = idx === 0 ? g.placar1 : g.placar2;
                if (placar !== null && placar !== undefined && String(placar) !== '') {
                    var existing = slotEl.nextElementSibling;
                    if (!existing || !existing.classList.contains('game-score-badge')) {
                        var badge = document.createElement('span');
                        badge.className = 'game-score-badge ms-2 badge bg-light text-dark border small';
                        slotEl.parentNode.insertBefore(badge, slotEl.nextSibling);
                    }
                    slotEl.nextElementSibling.textContent = placar;
                }

                // Destacar vencedor
                if (g.vencedor && val && g.vencedor === val) {
                    if (dotEl) dotEl.style.background = '#f59e0b';
                    slotEl.style.color = 'var(--sesi-red)';
                    slotEl.style.fontWeight = '700';
                }
            });

            // Exibir campeão na final
            if (g.fase === 'final' && g.vencedor) {
                var champEl = document.getElementById('champ-' + g.modulo + '-' + g.modalidade);
                if (champEl) {
                    champEl.innerHTML = '<i class="bi bi-trophy-fill"></i> ' + g.vencedor;
                }
            }
        });
    }

    fetch('api/games.php')
        .then(function (r) { return r.ok ? r.json() : null; })
        .then(function (data) {
            if (data && data.ok && data.games) applyGames(data.games);
        })
        .catch(function () {});
})();
</script>

<?php require __DIR__ . '/includes/footer.php'; ?>
