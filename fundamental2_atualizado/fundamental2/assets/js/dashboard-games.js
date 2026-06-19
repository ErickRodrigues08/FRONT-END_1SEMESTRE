(function () {
  'use strict';

  var API_BASE = '../api/';

  // Cache dos jogos carregados: id -> game object
  var gamesCache = {};

  /* ── Utilitários ─────────────────────────────────────────── */

  function showGamesToast(message, variant) {
    var container = document.getElementById('toastContainer');
    if (!container) return;
    var bg =
      variant === 'success'
        ? 'text-bg-success'
        : variant === 'danger'
          ? 'text-bg-danger'
          : 'text-bg-dark';
    var el = document.createElement('div');
    el.className = 'toast align-items-center border-0 ' + bg;
    el.setAttribute('role', 'alert');
    el.innerHTML =
      '<div class="d-flex">' +
      '<div class="toast-body">' + message + '</div>' +
      '<button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>' +
      '</div>';
    container.appendChild(el);
    var toast = new bootstrap.Toast(el, { delay: 3500 });
    toast.show();
    el.addEventListener('hidden.bs.toast', function () { el.remove(); });
  }

  function setGamesLoading(on) {
    var ov = document.getElementById('pageLoader');
    if (ov) ov.classList.toggle('show', !!on);
  }

  function escHtml(str) {
    return String(str)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;');
  }

  /* ── Carregar jogos e preencher formulários ──────────────── */

  function loadGames() {
    return fetch(API_BASE + 'games.php', { credentials: 'same-origin' })
      .then(function (r) { return r.json(); })
      .then(function (data) {
        if (!data.ok) return;
        gamesCache = {};
        data.games.forEach(function (g) { gamesCache[g.id] = g; });
        data.games.forEach(applyGameToForm);
      })
      .catch(function () {});
  }

  function applyGameToForm(g) {
    var t1 = document.getElementById('g-' + g.id + '-t1');
    var t2 = document.getElementById('g-' + g.id + '-t2');
    var p1 = document.getElementById('g-' + g.id + '-p1');
    var p2 = document.getElementById('g-' + g.id + '-p2');
    var venc = document.getElementById('g-' + g.id + '-venc');
    if (t1) t1.value = g.time1 || '';
    if (t2) t2.value = g.time2 || '';
    if (p1) p1.value = g.placar1 !== null ? g.placar1 : '';
    if (p2) p2.value = g.placar2 !== null ? g.placar2 : '';
    if (venc) updateVencedorOptions(g.id, g.time1, g.time2, g.vencedor);
  }

  function updateVencedorOptions(gameId, t1Val, t2Val, selectedVenc) {
    var sel = document.getElementById('g-' + gameId + '-venc');
    if (!sel) return;
    var prev = selectedVenc || sel.value;
    sel.innerHTML = '<option value="">— A definir —</option>';
    if (t1Val && t1Val.trim()) {
      var o1 = document.createElement('option');
      o1.value = t1Val.trim();
      o1.textContent = t1Val.trim();
      sel.appendChild(o1);
    }
    if (t2Val && t2Val.trim()) {
      var o2 = document.createElement('option');
      o2.value = t2Val.trim();
      o2.textContent = t2Val.trim();
      sel.appendChild(o2);
    }
    if (prev) sel.value = prev;
  }

  /* ── Salvar jogo ─────────────────────────────────────────── */

  function saveGame(id) {
    var t1 = document.getElementById('g-' + id + '-t1');
    var t2 = document.getElementById('g-' + id + '-t2');
    var p1 = document.getElementById('g-' + id + '-p1');
    var p2 = document.getElementById('g-' + id + '-p2');
    var venc = document.getElementById('g-' + id + '-venc');

    var payload = {
      time1:    t1   ? t1.value.trim()   : null,
      time2:    t2   ? t2.value.trim()   : null,
      placar1:  p1 && p1.value !== '' ? parseInt(p1.value, 10) : null,
      placar2:  p2 && p2.value !== '' ? parseInt(p2.value, 10) : null,
      vencedor: venc ? venc.value || null : null,
    };

    setGamesLoading(true);
    return fetch(API_BASE + 'games.php?id=' + encodeURIComponent(id), {
      method: 'PUT',
      credentials: 'same-origin',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload),
    })
      .then(function (r) { return r.json(); })
      .then(function (data) {
        if (!data.ok) {
          showGamesToast(data.error || 'Erro ao salvar.', 'danger');
          return null;
        }
        gamesCache[id] = data.game;
        showGamesToast('Jogo salvo com sucesso.', 'success');
        // Propagar vencedor para a final automaticamente
        if (data.game.fase === 'semifinal' && data.game.vencedor) {
          propagarParaFinal(data.game);
        }
        return data.game;
      })
      .catch(function () {
        showGamesToast('Erro de comunicação.', 'danger');
        return null;
      })
      .finally(function () { setGamesLoading(false); });
  }

  /* ── Propagar vencedor de semifinal para final ───────────── */

  function propagarParaFinal(sfGame) {
    // Encontrar o jogo final correspondente (mesmo modulo + modalidade + fase=final)
    var finalGame = null;
    Object.keys(gamesCache).forEach(function (key) {
      var g = gamesCache[key];
      if (
        g.modulo === sfGame.modulo &&
        g.modalidade === sfGame.modalidade &&
        g.fase === 'final'
      ) {
        finalGame = g;
      }
    });
    if (!finalGame) return;

    // sf jogo_seq=1 → time1 da final; seq=2 → time2 da final
    var field = sfGame.jogo_seq === 1 ? 'time1' : 'time2';
    var inputEl = document.getElementById('g-' + finalGame.id + '-' + (sfGame.jogo_seq === 1 ? 't1' : 't2'));
    if (inputEl) inputEl.value = sfGame.vencedor || '';

    // Atualizar cache local temporariamente
    var updatedFinal = Object.assign({}, finalGame);
    updatedFinal[field] = sfGame.vencedor || null;
    gamesCache[finalGame.id] = updatedFinal;

    // Atualizar opções do select de vencedor da final
    updateVencedorOptions(
      finalGame.id,
      updatedFinal.time1,
      updatedFinal.time2,
      updatedFinal.vencedor
    );

    // Salvar a final automaticamente
    saveGameSilent(finalGame.id, updatedFinal);
  }

  function saveGameSilent(id, gameData) {
    var t1 = document.getElementById('g-' + id + '-t1');
    var t2 = document.getElementById('g-' + id + '-t2');
    var p1 = document.getElementById('g-' + id + '-p1');
    var p2 = document.getElementById('g-' + id + '-p2');
    var venc = document.getElementById('g-' + id + '-venc');

    var payload = {
      time1:    t1   ? t1.value.trim()   : (gameData.time1 || null),
      time2:    t2   ? t2.value.trim()   : (gameData.time2 || null),
      placar1:  p1 && p1.value !== '' ? parseInt(p1.value, 10) : null,
      placar2:  p2 && p2.value !== '' ? parseInt(p2.value, 10) : null,
      vencedor: venc ? venc.value || null : null,
    };

    fetch(API_BASE + 'games.php?id=' + encodeURIComponent(id), {
      method: 'PUT',
      credentials: 'same-origin',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload),
    })
      .then(function (r) { return r.json(); })
      .then(function (data) {
        if (data.ok) gamesCache[id] = data.game;
      })
      .catch(function () {});
  }

  /* ── Pills de módulo ─────────────────────────────────────── */

  function wireModuloPills() {
    var pills = document.querySelectorAll('.admin-modulo-pill[data-admin-modulo-target]');
    if (!pills.length) return;
    pills.forEach(function (pill) {
      pill.addEventListener('click', function () {
        var targetSel = pill.getAttribute('data-admin-modulo-target');
        var target = document.querySelector(targetSel);
        if (!target) return;
        pills.forEach(function (p) {
          p.classList.remove('active');
          p.setAttribute('aria-selected', 'false');
        });
        pill.classList.add('active');
        pill.setAttribute('aria-selected', 'true');
        var panes = target.parentElement.querySelectorAll('.admin-tab-pane');
        panes.forEach(function (pane) {
          pane.classList.remove('show', 'active');
        });
        target.classList.add('show', 'active');
      });
    });
  }

  /* ── Inicialização ───────────────────────────────────────── */

  function init() {
    wireModuloPills();

    // Botão de atualizar jogos
    var btnRefreshGames = document.getElementById('btnRefreshGames');
    if (btnRefreshGames) {
      btnRefreshGames.addEventListener('click', function () { loadGames(); });
    }

    // Botões de salvar por jogo
    document.querySelectorAll('.game-save-btn').forEach(function (btn) {
      btn.addEventListener('click', function () {
        var id = parseInt(btn.getAttribute('data-id'), 10);
        if (id) saveGame(id);
      });
    });

    // Atualizar opções de vencedor quando times mudam
    document.querySelectorAll('.game-t1, .game-t2').forEach(function (input) {
      input.addEventListener('input', function () {
        var row = input.closest('.game-edit-row');
        if (!row) return;
        var gid = parseInt(row.getAttribute('data-game-id'), 10);
        if (!gid) return;
        var t1el = document.getElementById('g-' + gid + '-t1');
        var t2el = document.getElementById('g-' + gid + '-t2');
        var venc = gamesCache[gid] ? gamesCache[gid].vencedor : null;
        updateVencedorOptions(
          gid,
          t1el ? t1el.value : '',
          t2el ? t2el.value : '',
          venc
        );
      });
    });

    // Carregar jogos ao iniciar
    loadGames();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
