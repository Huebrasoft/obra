(function () {
  if (window.lucide && typeof window.lucide.createIcons === 'function') {
    window.lucide.createIcons();
  }

  const el = document.getElementById('currentDate');
  if (!el) return;

  const options = { weekday: 'long', day: 'numeric', month: 'long' };
  const text = new Date().toLocaleDateString('es-ES', options);
  el.textContent = text.charAt(0).toUpperCase() + text.slice(1);
})();
