const API_BASE = window.location.origin + "/api/route.php";

function logout() {
  localStorage.removeItem("user");
  window.location.href = "/index.html";
}

document.addEventListener("DOMContentLoaded", () => {
  const user = JSON.parse(localStorage.getItem("user"));
  if (!user) return window.location.href = "index.html";

  const nameField = document.getElementById("name");
  const emailField = document.getElementById("email");

  if (nameField && emailField) {
    nameField.value = user.name;
    emailField.value = user.email;
  }

  const accountForm = document.getElementById("account-form");
  if (accountForm) {
    accountForm.addEventListener("submit", updateAccount);
  }
});

function updateAccount(e) {
  e.preventDefault();

  const user = JSON.parse(localStorage.getItem("user"));
  const name = document.getElementById("name").value;
  const email = document.getElementById("email").value;
  const password = document.getElementById("password").value;
  const message = document.getElementById("message");

  fetch(`${API_BASE}?route=update_user`, {
    method: "POST",
    headers: {
      "Content-Type": "application/json"
    },
    body: JSON.stringify({
      id: user.id,
      name: name || null,
      email: email || null,
      password: password || null
    })
  })
    .then((res) => res.json())
    .then((data) => {
      if (data.message === "Mise à jour réussie") {
        alert("Mise à jour réussie !");
        window.location.href = "home.html";
      } else {
        message.textContent = data.error || "Erreur inconnue";
      }
    })
    .catch((err) => {
      console.error("Erreur:", err);
      message.textContent = "Erreur réseau";
    });
}