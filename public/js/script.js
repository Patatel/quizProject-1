function showForm(type) {
  document.getElementById('login-form').classList.add('hidden');
  document.getElementById('register-form').classList.add('hidden');
  document.getElementById(type + '-form').classList.remove('hidden');
}

const API_BASE = window.location.origin + '/api/route.php';
console.log(API_BASE);
// Envoie la requête d'inscription
function handleRegister(e) {
  e.preventDefault();

  const name = document.getElementById('register-name').value;
  const email = document.getElementById('register-email').value;
  const password = document.getElementById('register-password').value;

  fetch(`${API_BASE}?route=register`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ name, email, password })
  })
    .then(async res => {
      const text = await res.text();
      console.log("Raw response:", text);  // 🧪 Voir la vraie réponse (HTML ? JSON ?)
      try {
        return JSON.parse(text);
      } catch (e) {
        throw new Error("Réponse invalide JSON");
      }
    })
    .then(data => {
      const messageElement = document.getElementById('register-message');
      if (data.message) {
        messageElement.textContent = data.message;
        messageElement.style.color = 'green';
        showForm('login');
      } else if (data.error) {
        messageElement.textContent = data.error;
        messageElement.style.color = 'red';
      }
    })
    .catch(err => console.error("Fetch failed:", err));
}

fetch(`${API_BASE}?route=login`, {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json'
  },
  body: JSON.stringify({ email, password })
})
  .then(async response => {
    const text = await response.text();
    console.log("Raw response login:", text);
    try {
      return JSON.parse(text);
    } catch {
      throw new Error("Réponse invalide JSON");
    }
  })
  .then(data => {
    if (data.user) {
      localStorage.setItem('user', JSON.stringify(data.user));
      window.location.href = '/quizProject/public/html/home.html';
    } else {
      document.getElementById('login-message').textContent = data.error || "Erreur inconnue";
    }
  })
  .catch(error => {
    console.error("Erreur lors de la connexion :", error);
    document.getElementById('login-message').textContent = "Erreur réseau ou serveur.";
  });