(function () {
  const navs = document.querySelectorAll('[data-nav]');

  navs.forEach((nav) => {
    const toggle = nav.querySelector('[data-nav-toggle]');
    const menu = nav.querySelector('[data-nav-menu]');
    if (!toggle || !menu) return;

    const closeNav = () => {
      nav.classList.remove('is-open');
      toggle.setAttribute('aria-expanded', 'false');
    };

    toggle.addEventListener('click', (event) => {
      event.stopPropagation();
      const isOpen = nav.classList.toggle('is-open');
      toggle.setAttribute('aria-expanded', String(isOpen));
    });

    document.addEventListener('click', (event) => {
      if (!nav.contains(event.target)) {
        closeNav();
      }
    });

    window.addEventListener('resize', () => {
      if (window.matchMedia('(min-width: 761px)').matches) {
        closeNav();
      }
    });
  });
})();
