document.addEventListener('DOMContentLoaded', () => {
  const modal = document.getElementById("contactModal");
  const btn = document.querySelector(".contact-info-btn");
  const span = modal ? modal.querySelector(".close") : null;

  if (!modal || !btn || !span) return;

  btn.addEventListener('click', () => {
    modal.style.display = "block";
  });

  span.addEventListener('click', () => {
    modal.style.display = "none";
  });

  window.addEventListener('click', (event) => {
    if (event.target === modal) {
      modal.style.display = "none";
    }
  });
});
