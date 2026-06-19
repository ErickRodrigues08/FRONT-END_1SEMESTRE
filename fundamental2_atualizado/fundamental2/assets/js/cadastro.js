(function () {
  const API = "api/register.php";

  function showToast(message, variant) {
    const container = document.getElementById("toastContainer");
    if (!container) return;
    const id = "t-" + Date.now();
    const bg =
      variant === "success"
        ? "text-bg-success"
        : variant === "danger"
          ? "text-bg-danger"
          : "text-bg-dark";
    const el = document.createElement("div");
    el.className = "toast align-items-center border-0 " + bg;
    el.id = id;
    el.setAttribute("role", "alert");
    el.setAttribute("aria-live", "assertive");
    el.innerHTML =
      '<div class="d-flex">' +
      '<div class="toast-body">' +
      message +
      "</div>" +
      '<button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>' +
      "</div>";
    container.appendChild(el);
    const toast = new bootstrap.Toast(el, { delay: 4500 });
    toast.show();
    el.addEventListener("hidden.bs.toast", function () {
      el.remove();
    });
  }

  function setLoading(on) {
    const ov = document.getElementById("pageLoader");
    if (!ov) return;
    ov.classList.toggle("show", !!on);
  }

  const modalityBoxes = Array.from(document.querySelectorAll(".modality-cb"));
  const alertLimit = document.getElementById("modalityLimitAlert");
  const modalityFeedback = document.getElementById("modalityFeedback");

  function visibleAllowedModalityValues(genderValue) {
    // Requisito: gênero masculino -> apenas handebol_masculino; feminino -> apenas handebol_feminino.
    // Vôlei Misto aparece para ambos.
    if (genderValue === "masculino") return new Set(["handebol_masculino", "volei_misto"]);
    if (genderValue === "feminino") return new Set(["handebol_feminino", "volei_misto"]);
    return new Set(["handebol_feminino", "handebol_masculino", "volei_misto"]);
  }

  function applyGenderFilter() {
    const genderInput = form.querySelector('input[name="gender"]:checked');
    const genderValue = genderInput ? genderInput.value : "";
    const allowed = visibleAllowedModalityValues(genderValue);

    modalityBoxes.forEach(function (cb) {
      const allowedByGender = allowed.has(cb.value);
      cb.disabled = !allowedByGender;

      // Mantém a UI consistente: esconde os incompatíveis.
      const row = cb.closest(".col-md-4");
      if (row) {
        row.style.display = allowedByGender ? "" : "none";
      }

      // Se o usuário já tinha marcado algo que ficou incompatível, desmarca.
      if (!allowedByGender) cb.checked = false;
    });
  }


  function selectedModalities() {
    return modalityBoxes.filter(function (c) {
      return c.checked;
    });
  }

  modalityBoxes.forEach(function (cb) {
    cb.addEventListener("change", function () {
      const sel = selectedModalities();
      if (sel.length > 2) {
        cb.checked = false;
        if (alertLimit) {
          alertLimit.classList.add("show");
          setTimeout(function () {
            alertLimit.classList.remove("show");
          }, 3500);
        }
        showToast("Máximo de 2 modalidades permitidas.", "danger");
      }
    });
  });

  const form = document.getElementById("formCadastro");
  if (!form) return;

  // Ao selecionar gênero, filtra as modalidades compatíveis.
  form.querySelectorAll('input[name="gender"]').forEach(function (r) {
    r.addEventListener("change", function () {
      applyGenderFilter();
    });
  });
  applyGenderFilter();


  form.addEventListener("submit", async function (e) {
    e.preventDefault();
    modalityFeedback.style.display = "none";
    document.getElementById("genderFeedback").style.display = "none";

    const gender = form.querySelector('input[name="gender"]:checked');
    let valid = form.checkValidity();
    if (!gender) {
      valid = false;
      document.getElementById("genderFeedback").style.display = "block";
    }
    const mods = selectedModalities().map(function (c) {
      return c.value;
    });
    if (mods.length < 1 || mods.length > 2) {
      valid = false;
      modalityFeedback.style.display = "block";
    }

    form.classList.add("was-validated");
    if (!valid) return;

    const payload = {
      full_name: form.full_name.value.trim(),
      age: parseInt(form.age.value, 10),
      grade: parseInt(form.grade.value, 10),
      class: form.class.value,
      gender: gender.value,
      modalities: mods,
    };

    setLoading(true);
    try {
      const res = await fetch(API, {
        method: "POST",
        credentials: "same-origin",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(payload),
      });
      const data = await res.json().catch(function () {
        return {};
      });
      if (!res.ok || !data.ok) {
        showToast(data.error || "Não foi possível enviar. Tente novamente.", "danger");
        return;
      }
      showToast(data.message || "Inscrição realizada!", "success");
      form.reset();
      form.classList.remove("was-validated");
    } catch (err) {
      showToast("Erro de conexão. Verifique sua internet.", "danger");
    } finally {
      setLoading(false);
    }
  });
})();
