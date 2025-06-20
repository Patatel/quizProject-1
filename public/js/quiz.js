const user = JSON.parse(localStorage.getItem('user'));
if (!user) {
  window.location.href = "index.html";
}

const API_URL = window.location.origin + '/api/route.php';
console.log("API URL:", API_URL);

async function loadQuizzes() {
  try {
    const response = await fetch(`${API_URL}?route=all_quizzes`);
    const quizzes = await response.json();

    const container = document.getElementById('quiz-list');
    const message = document.getElementById("message");
    if (!container) return;

    container.innerHTML = '';

    if (response.ok && Array.isArray(quizzes)) {
      quizzes.forEach(quiz => {
        const col = document.createElement('div');
        col.className = 'col-md-6 col-lg-4';

        const card = document.createElement('div');
        card.className = 'card quiz-card shadow-sm';

        const cardBody = document.createElement('div');
        cardBody.className = 'card-body';

        const title = document.createElement('h5');
        title.className = 'card-title';
        title.textContent = quiz.title;

        const description = document.createElement('p');
        description.className = 'card-text';
        description.textContent = quiz.description;

        const startBtn = document.createElement('a');
        startBtn.textContent = 'Commencer';
        startBtn.href = `questionnary.html?id=${quiz.id}`;
        startBtn.className = 'btn btn-primary start-btn';

        cardBody.appendChild(title);
        cardBody.appendChild(description);
        cardBody.appendChild(startBtn);
        card.appendChild(cardBody);
        col.appendChild(card);
        container.appendChild(col);
      });
    } else {
      if (message) message.textContent = quizzes.error || "Erreur lors du chargement des quiz.";
    }
  } catch (error) {
    const message = document.getElementById("message");
    if (message) message.textContent = "Erreur réseau.";
  }
}

async function createQuiz(event) {
  event.preventDefault();
  const title = document.getElementById('title').value;
  const description = document.getElementById('description').value;

  const res = await fetch(`${API_URL}?route=create_quiz`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ title, description, user_id: user.id })
  });

  const result = await res.json();
  if (res.ok && result.id) {
    document.getElementById("quiz-id").value = result.id;
    alert("Quiz créé ! Ajoutez maintenant vos questions.");
  } else {
    document.getElementById("message").textContent = result.error || "Erreur lors de la création du quiz";
  }
}

async function addQuestions(event) {
  event.preventDefault();
  const quizId = document.getElementById("quiz-id").value;
  if (!quizId) return alert("Quiz non identifié");

  const questionItems = document.querySelectorAll(".question-item");
  const questions = [];

  questionItems.forEach((item, questionIndex) => {
    const questionInput = item.querySelector("input[name='question']");
    const answerInputs = item.querySelectorAll("input[type='text'][name^='answer_']");
    const correctRadio = item.querySelector("input[type='radio']:checked");

    const answers = Array.from(answerInputs).map(input => input.value.trim());
    const correctAnswerIndex = parseInt(correctRadio.value);

    questions.push({
      text: questionInput.value.trim(),
      answers: answers,
      correctAnswerIndex: correctAnswerIndex
    });
  });

  if (questions.length < 1 || questions.length > 10) {
    return alert("Veuillez saisir entre 1 et 10 questions.");
  }

  try {
    const response = await fetch(`${API_URL}?route=add_questions`, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ quiz_id: quizId, questions })
    });

    const result = await response.json();

    if (response.ok) {
      alert("Questions ajoutées !");
      window.location.href = "quiz.html";
    } else {
      alert(result.error || "Erreur lors de l'enregistrement des questions.");
    }
  } catch (err) {
    alert("Erreur réseau.");
  }
}

function logout() {
  localStorage.removeItem('user');
  window.location.href = "/index.html";
}

if (document.getElementById("quiz-list")) {
  window.addEventListener("DOMContentLoaded", loadQuizzes);
}

if (document.getElementById("quiz-form")) {
  document.getElementById("quiz-form").addEventListener("submit", createQuiz);
}

if (document.getElementById("question-form")) {
  document.getElementById("question-form").addEventListener("submit", addQuestions);
}