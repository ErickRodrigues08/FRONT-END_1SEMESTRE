(function () {
  const API_BASE = "../api/";
  const MOD_LABEL = {
    handebol_feminino: "Handebol Fem.",
    handebol_masculino: "Handebol Masc.",
    volei_misto: "Vôlei Misto",
  };

  let chartMod = null;
  let chartStack = null;
  let sortState = { key: "created_at", order: "DESC" };
  let deleteTargetId = null;
  let modalEdit = null;
  let modalDelete = null;

  function showToast(message, variant) {
    const container = document.getElementById("toastContainer");
    if (!container) return;
    const bg =
      variant === "success"
        ? "text-bg-success"
        : variant === "danger"
          ? "text-bg-danger"
          : "text-bg-dark";
    const el = document.createElement("div");
    el.className = "toast align-items-center border-0 " + bg;
    el.setAttribute("role", "alert");
    el.innerHTML =
      '<div class="d-flex">' +
      '<div class="toast-body">' +
      message +
      "</div>" +
      '<button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>' +
      "</div>";
    container.appendChild(el);
    const toast = new bootstrap.Toast(el, { delay: 4000 });
    toast.show();
    el.addEventListener("hidden.bs.toast", function () {
      el.remove();
    });
  }

  function setLoading(on) {
    const ov = document.getElementById("pageLoader");
    if (ov) ov.classList.toggle("show", !!on);
  }

  function modalityLabels(arr) {
    return (arr || []).map(function (m) {
      return MOD_LABEL[m] || m;
    }).join(", ");
  }

  function genderLabel(g) {
    return g === "masculino" ? "Masculino" : "Feminino";
  }

  async function fetchJson(url, options) {
    const res = await fetch(url, Object.assign({ credentials: "same-origin" }, options || {}));
    const data = await res.json().catch(function () {
      return {};
    });
    if (res.status === 401) {
      window.location.href = "login.php";
      throw new Error("auth");
    }
    return { res, data };
  }

  async function loadStats() {
    const { res, data } = await fetchJson(API_BASE + "stats.php");
    if (!res.ok || !data.ok) {
      showToast(data.error || "Erro ao carregar estatísticas.", "danger");
      return;
    }
    document.getElementById("kpiTotal").textContent = data.total;
    document.getElementById("kpiBoys").textContent = data.by_gender.masculino;
    document.getElementById("kpiGirls").textContent = data.by_gender.feminino;
    document.getElementById("kpiClass").textContent =
      "A: " + data.by_class.A + " · B: " + data.by_class.B;
    const g = data.by_grade;
    document.getElementById("kpiGrades").innerHTML =
      ["6", "7", "8", "9"].map(function (k) {
        return '<span class="badge rounded-pill bg-light text-dark border">' + k + "º: " + g[k] + "</span>";
      }).join("");

    const labels = Object.keys(data.by_modality).map(function (k) {
      return MOD_LABEL[k] || k;
    });
    const values = Object.keys(data.by_modality).map(function (k) {
      return data.by_modality[k];
    });

    const ctx1 = document.getElementById("chartModalities");
    if (ctx1) {
      if (chartMod) chartMod.destroy();
      chartMod = new Chart(ctx1, {
        type: "bar",
        data: {
          labels: labels,
          datasets: [
            {
              label: "Inscritos",
              data: values,
              backgroundColor: ["rgba(213,0,0,0.85)", "rgba(183,28,28,0.75)", "rgba(248,113,113,0.9)"],
              borderRadius: 8,
            },
          ],
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: { legend: { display: false } },
          scales: {
            y: { beginAtZero: true, ticks: { precision: 0 } },
          },
        },
      });
    }

    const mods = ["handebol_feminino", "handebol_masculino", "volei_misto"];
    const masc = mods.map(function () {
      return 0;
    });
    const fem = mods.map(function () {
      return 0;
    });
    (data.modality_by_gender || []).forEach(function (row) {
      const i = mods.indexOf(row.modality);
      if (i < 0) return;
      if (row.gender === "masculino") masc[i] = row.count;
      if (row.gender === "feminino") fem[i] = row.count;
    });

    const ctx2 = document.getElementById("chartModGender");
    if (ctx2) {
      if (chartStack) chartStack.destroy();
      chartStack = new Chart(ctx2, {
        type: "bar",
        data: {
          labels: mods.map(function (k) {
            return MOD_LABEL[k];
          }),
          datasets: [
            {
              label: "Masculino",
              data: masc,
              backgroundColor: "rgba(59,130,246,0.75)",
              borderRadius: 6,
            },
            {
              label: "Feminino",
              data: fem,
              backgroundColor: "rgba(213,0,0,0.75)",
              borderRadius: 6,
            },
          ],
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          scales: {
            x: { stacked: true },
            y: { stacked: true, beginAtZero: true, ticks: { precision: 0 } },
          },
        },
      });
    }
  }

  function buildQuery() {
    const p = new URLSearchParams();
    const q = document.getElementById("filterQ").value.trim();
    if (q) p.set("q", q);
    const grade = document.getElementById("filterGrade").value;
    if (grade && grade !== "all") p.set("grade", grade);
    const cls = document.getElementById("filterClass").value;
    if (cls && cls !== "all") p.set("class", cls);
    const gen = document.getElementById("filterGender").value;
    if (gen && gen !== "all") p.set("gender", gen);
    const mod = document.getElementById("filterModality").value;
    if (mod && mod !== "all") p.set("modality", mod);
    p.set("sort", sortState.key);
    p.set("order", sortState.order);
    return p.toString();
  }

  async function loadStudents() {
    const qs = buildQuery();
    const { res, data } = await fetchJson(API_BASE + "students.php?" + qs);
    if (!res.ok || !data.ok) {
      showToast(data.error || "Erro ao carregar alunos.", "danger");
      return;
    }
    renderTable(data.students || []);
  }

  function renderTable(rows) {
    const tb = document.getElementById("tableBody");
    const mob = document.getElementById("cardsMobile");
    if (!tb || !mob) return;
    tb.innerHTML = "";
    mob.innerHTML = "";
    rows.forEach(function (s) {
      const mods = modalityLabels(s.modalities);
      const tr = document.createElement("tr");
      tr.innerHTML =
        "<td>" +
        escapeHtml(s.full_name) +
        "</td>" +
        "<td>" +
        s.age +
        "</td>" +
        "<td>" +
        s.grade +
        "º</td>" +
        "<td>" +
        escapeHtml(s.class) +
        "</td>" +
        "<td>" +
        genderLabel(s.gender) +
        "</td>" +
        "<td><small>" +
        escapeHtml(mods) +
        "</small></td>" +
        '<td class="text-end">' +
        '<button type="button" class="btn btn-sm btn-outline-primary me-1 btn-edit" data-id="' +
        s.id +
        '"><i class="bi bi-pencil"></i></button>' +
        '<button type="button" class="btn btn-sm btn-outline-danger btn-del" data-id="' +
        s.id +
        '" data-name="' +
        escapeAttr(s.full_name) +
        '"><i class="bi bi-trash"></i></button>' +
        "</td>";
      tb.appendChild(tr);

      const card = document.createElement("div");
      card.className = "mobile-student-card p-3 mb-3";
      card.innerHTML =
        '<div class="fw-bold">' +
        escapeHtml(s.full_name) +
        "</div>" +
        '<div class="small text-muted">' +
        s.age +
        " anos · " +
        s.grade +
        "º " +
        escapeHtml(s.class) +
        " · " +
        genderLabel(s.gender) +
        "</div>" +
        '<div class="small mt-2">' +
        escapeHtml(mods) +
        "</div>" +
        '<div class="mt-2 d-flex gap-2">' +
        '<button type="button" class="btn btn-sm btn-outline-primary btn-edit" data-id="' +
        s.id +
        '">Editar</button>' +
        '<button type="button" class="btn btn-sm btn-outline-danger btn-del" data-id="' +
        s.id +
        '" data-name="' +
        escapeAttr(s.full_name) +
        '">Excluir</button>' +
        "</div>";
      mob.appendChild(card);
    });
  }

  function escapeHtml(str) {
    return String(str)
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;");
  }

  function escapeAttr(str) {
    return escapeHtml(str).replace(/'/g, "&#39;");
  }

  function allowedEditModalityValues(genderValue) {
    // Gênero masculino -> apenas handebol_masculino; feminino -> apenas handebol_feminino.
    // Vôlei Misto aparece para ambos.
    if (genderValue === "masculino") return new Set(["handebol_masculino", "volei_misto"]);
    if (genderValue === "feminino") return new Set(["handebol_feminino", "volei_misto"]);
    return new Set(["handebol_feminino", "handebol_masculino", "volei_misto"]);
  }

  function applyEditGenderFilter() {
    const gender = document.querySelector('input[name="edit_gender"]:checked');
    const genderValue = gender ? gender.value : "";
    const allowed = allowedEditModalityValues(genderValue);

    document.querySelectorAll(".edit-mod").forEach(function (cb) {
      const allowedByGender = allowed.has(cb.value);
      cb.disabled = !allowedByGender;

      if (!allowedByGender) cb.checked = false;

      const row = cb.closest(".col-md-4");
      if (row) row.style.display = allowedByGender ? "" : "none";
    });
  }

  function openEdit(student) {
    document.getElementById("edit_id").value = student.id;
    document.getElementById("edit_full_name").value = student.full_name;
    document.getElementById("edit_age").value = student.age;
    document.getElementById("edit_grade").value = String(student.grade);
    document.getElementById("edit_class").value = student.class;
    if (student.gender === "masculino") document.getElementById("edit_gender_m").checked = true;
    else document.getElementById("edit_gender_f").checked = true;
    document.querySelectorAll(".edit-mod").forEach(function (c) {
      c.checked = (student.modalities || []).indexOf(c.value) >= 0;
    });
    modalEdit.show();
  }

  function wireEditDelete() {
    document.querySelectorAll(".btn-edit").forEach(function (btn) {
      btn.addEventListener("click", function () {
        const id = btn.getAttribute("data-id");
        const row = findStudentRow(id);
        if (row) openEdit(row);
      });
    });
    document.querySelectorAll(".btn-del").forEach(function (btn) {
      btn.addEventListener("click", function () {
        deleteTargetId = btn.getAttribute("data-id");
        document.getElementById("deleteStudentName").textContent = btn.getAttribute("data-name") || "";
        modalDelete.show();
      });
    });
  }

  let lastRows = [];

  function findStudentRow(id) {
    return lastRows.find(function (r) {
      return String(r.id) === String(id);
    });
  }

  async function refreshAll() {
    setLoading(true);
    try {
      await loadStats();
      const qs = buildQuery();
      const { res, data } = await fetchJson(API_BASE + "students.php?" + qs);
      if (!res.ok || !data.ok) throw new Error("students");
      lastRows = data.students || [];
      renderTable(lastRows);
      wireEditDelete();
    } catch (e) {
      if (e.message !== "auth") showToast("Falha ao atualizar.", "danger");
    } finally {
      setLoading(false);
    }
  }

  document.querySelectorAll(".sortable-th").forEach(function (th) {
    th.addEventListener("click", function () {
      const key = th.getAttribute("data-sort");
      if (!key) return;
      if (sortState.key === key) {
        sortState.order = sortState.order === "ASC" ? "DESC" : "ASC";
      } else {
        sortState.key = key;
        sortState.order = "ASC";
      }
      refreshAll();
    });
  });

  let deb;
  function scheduleReload() {
    clearTimeout(deb);
    deb = setTimeout(refreshAll, 280);
  }

  ["filterQ", "filterGrade", "filterClass", "filterGender", "filterModality"].forEach(function (id) {
    const el = document.getElementById(id);
    if (el) el.addEventListener("input", scheduleReload);
    if (el) el.addEventListener("change", scheduleReload);
  });

  const btnRefresh = document.getElementById("btnRefresh");
  if (btnRefresh) {
    btnRefresh.addEventListener("click", function () {
      refreshAll();
    });
  }

  const btnLogoutNav = document.getElementById("btnLogoutNav");
  if (btnLogoutNav) {
    btnLogoutNav.addEventListener("click", async function () {
    try {
      await fetch(API_BASE + "logout.php", { method: "POST", credentials: "same-origin" });
    } catch (e) {}
    window.location.href = "login.php";
    });
  }

  document.querySelectorAll(".edit-mod").forEach(function (cb) {
    cb.addEventListener("change", function () {
      const sel = Array.from(document.querySelectorAll(".edit-mod")).filter(function (c) {
        return c.checked;
      });
      if (sel.length > 2) {
        cb.checked = false;
        showToast("Máximo de 2 modalidades.", "danger");
      }
    });
  });

  document.getElementById("btnSaveEdit").addEventListener("click", async function () {
    const id = document.getElementById("edit_id").value;
    const mods = Array.from(document.querySelectorAll(".edit-mod"))
      .filter(function (c) {
        return c.checked;
      })
      .map(function (c) {
        return c.value;
      });
    const gender = document.querySelector('input[name="edit_gender"]:checked');
    if (!gender || mods.length < 1 || mods.length > 2) {
      showToast("Preencha gênero e de 1 a 2 modalidades.", "danger");
      return;
    }
    const payload = {
      full_name: document.getElementById("edit_full_name").value.trim(),
      age: parseInt(document.getElementById("edit_age").value, 10),
      grade: parseInt(document.getElementById("edit_grade").value, 10),
      class: document.getElementById("edit_class").value,
      gender: gender.value,
      modalities: mods,
    };
    setLoading(true);
    try {
      const { res, data } = await fetchJson(API_BASE + "students.php?id=" + encodeURIComponent(id), {
        method: "PUT",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(payload),
      });
      if (!res.ok || !data.ok) {
        showToast(data.error || "Erro ao salvar.", "danger");
        return;
      }
      showToast("Cadastro atualizado.", "success");
      modalEdit.hide();
      await refreshAll();
    } finally {
      setLoading(false);
    }
  });

  document.getElementById("btnConfirmDelete").addEventListener("click", async function () {
    if (!deleteTargetId) return;
    setLoading(true);
    try {
      const { res, data } = await fetchJson(
        API_BASE + "students.php?id=" + encodeURIComponent(deleteTargetId),
        { method: "DELETE" }
      );
      if (!res.ok || !data.ok) {
        showToast(data.error || "Erro ao excluir.", "danger");
        return;
      }
      showToast("Aluno removido.", "success");
      modalDelete.hide();
      await refreshAll();
    } finally {
      setLoading(false);
      deleteTargetId = null;
    }
  });

  modalEdit = new bootstrap.Modal(document.getElementById("modalEdit"));
  modalDelete = new bootstrap.Modal(document.getElementById("modalDelete"));

  refreshAll();
})();
