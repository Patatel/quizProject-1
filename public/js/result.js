document.addEventListener("DOMContentLoaded", async () => {
  const API_URL = window.location.origin + "/api/route.php";

  const user = JSON.parse(localStorage.getItem("user"));
  if (!user) {
    window.location.href = "index.html";
    return;
  }

  try {
    const res = await fetch(`${API_URL}?route=get_user_results&user_id=${user.id}`);
    const results = await res.json();

    const tbody = document.querySelector("#results-table tbody");
    results.forEach(result => {
      const tr = document.createElement("tr");
      tr.innerHTML = `
        <td>${result.title}</td>
        <td>${result.score}/${result.total_questions}</td>
        <td>${result.percentage}%</td>
        <td>${new Date(result.date_passed).toLocaleDateString()}</td>
      `;
      tbody.appendChild(tr);
    });
  } catch (error) {
    alert("Erreur lors du chargement des résultats.");
    console.error(error);
  }
});