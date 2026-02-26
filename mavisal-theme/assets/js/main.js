(function () {
  const button = document.getElementById('mobile-menu-button');
  const menu = document.getElementById('mobile-menu');
  if (button && menu) {
    button.addEventListener('click', function () {
      const expanded = button.getAttribute('aria-expanded') === 'true';
      button.setAttribute('aria-expanded', expanded ? 'false' : 'true');
      menu.classList.toggle('hidden');
    });
  }

  const navbar = document.getElementById('navbar');
  if (navbar) {
    const onScroll = function () {
      if (window.scrollY > 50) {
        navbar.classList.add('bg-mavisal-blue/95', 'shadow-lg', 'py-3');
        navbar.classList.remove('bg-transparent', 'py-5');
      } else {
        navbar.classList.remove('bg-mavisal-blue/95', 'shadow-lg', 'py-3');
        navbar.classList.add('bg-mavisal-blue/20', 'py-5');
      }
    };
    window.addEventListener('scroll', onScroll);
    onScroll();
  }
})();
