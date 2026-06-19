(function () {
  const form = document.getElementById("formLogin");
  const errBox = document.getElementById("loginError");
  const loader = document.getElementById("pageLoader");

  function setLoading(on) {
    if (loader) loader.classList.toggle("show", !!on);
  }

  function showError(msg) {
    if (!errBox) return;
    errBox.textContent = msg;
    errBox.classList.remove("d-none");
  }

  function clearError() {
    if (!errBox) return;
    errBox.textContent = "";
    errBox.classList.add("d-none");
  }

  if (!form) return;

  form.addEventListener("submit", async function (e) {
    e.preventDefault();
    clearError();
    if (!form.checkValidity()) {
      form.classList.add("was-validated");
      return;
    }
    form.classList.add("was-validated");
    setLoading(true);
    try {
      const res = await fetch("../api/login.php", {
        method: "POST",
        credentials: "same-origin",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          username: form.username.value.trim(),
          password: form.password.value,
        }),
      });
      const data = await res.json().catch(function () {
        return {};
      });
      if (!res.ok || !data.ok) {
        showError(data.error || "Falha no login.");
        return;
      }
      window.location.href = "dashboard.php";
    } catch (ex) {
      showError("Erro de conexão. Tente novamente.");
    } finally {
      setLoading(false);
    }
  });
})();
