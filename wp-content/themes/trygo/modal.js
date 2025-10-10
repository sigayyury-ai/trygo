(function () {
  const MODAL_ID = 'signup-modal';
  let setModeHandler = null;

  document.addEventListener('DOMContentLoaded', () => {
    injectModal();
    attachListeners();
  });

  function injectModal() {
    if (document.getElementById(MODAL_ID)) return;

    const overlay = document.createElement('div');
    overlay.className = 'modal-overlay';
    overlay.id = MODAL_ID;
    overlay.innerHTML = `
      <div class="modal" role="dialog" aria-modal="true" aria-labelledby="modal-title">
        <button class="modal-close" type="button" aria-label="Close dialog" data-modal-close>
          <span aria-hidden="true">×</span>
        </button>
        <div class="modal-header">
          <div class="modal-tabs" role="tablist">
            <button class="modal-tab is-active" type="button" role="tab" aria-selected="true" data-modal-mode="signup">Sign up</button>
            <button class="modal-tab" type="button" role="tab" aria-selected="false" data-modal-mode="login">Log in</button>
          </div>
          <h2 id="modal-title" data-modal-heading>Create your account</h2>
          <p data-modal-subheading>Get started with your free TRYGO workspace.</p>
        </div>
        <form class="modal-form" novalidate aria-label="Sign up form">
          <input type="hidden" name="mode" value="signup" data-modal-mode-field />
          <label class="modal-field">
            <span>Email</span>
            <input type="email" name="email" placeholder="you@example.com" required />
          </label>
          <label class="modal-field">
            <span>Password</span>
            <input type="password" name="password" placeholder="Create a password" minlength="8" required />
          </label>
          <button class="modal-submit" type="submit">Sign up</button>
        </form>
        <div class="modal-divider">
          <span>or continue with</span>
        </div>
        <button class="modal-google" type="button">
          <svg viewBox="0 0 24 24" aria-hidden="true">
            <path fill="#4285F4" d="M21.35 11.1h-9.17v2.98h5.3c-.23 1.23-.94 2.27-2 2.96v2.46h3.22c1.88-1.73 2.97-4.29 2.97-7.32 0-.72-.07-1.42-.32-2.08z"/>
            <path fill="#34A853" d="M12.18 21c2.7 0 4.97-.9 6.62-2.52l-3.22-2.46c-.89.62-2.05.99-3.39.99-2.61 0-4.82-1.76-5.61-4.12H3.28v2.56C4.93 18.91 8.27 21 12.18 21z"/>
            <path fill="#FBBC05" d="M6.57 12.9c-.2-.6-.32-1.24-.32-1.9 0-.66.12-1.3.32-1.9V6.54H3.28C2.46 8.14 2 9.89 2 11.76s.46 3.62 1.28 5.22l3.29-2.08z"/>
            <path fill="#EA4335" d="M12.18 5.98c1.47 0 2.82.51 3.87 1.52l2.9-2.9C17.14 2.64 14.88 1.7 12.18 1.7 8.27 1.7 4.93 3.79 3.28 6.58l3.29 2.58c.79-2.36 3-4.12 5.61-4.12z"/>
            <path fill="none" d="M2 2h20v20H2z"/>
          </svg>
          Continue with Google
        </button>
        <p class="modal-footnote">
          <span data-modal-footnote-text>Already have an account?</span>
          <a href="#" data-modal-mode-toggle="login">Log in</a>
        </p>
      </div>
    `;

    document.body.appendChild(overlay);
  }

  function attachListeners() {
    const overlay = document.getElementById(MODAL_ID);
    if (!overlay) return;

    overlay.addEventListener('click', (event) => {
      const closeTrigger = event.target.closest('[data-modal-close]');
      if (event.target === overlay || closeTrigger) {
        closeModal();
      }
    });

    document.addEventListener('keydown', (event) => {
      if (event.key === 'Escape') {
        closeModal();
      }
    });

    document.querySelectorAll('[data-modal-trigger="signup"]').forEach((trigger) => {
      trigger.addEventListener('click', (event) => {
        event.preventDefault();
        openModal();
      });
    });

    const form = overlay.querySelector('form');
    const modeTabs = Array.from(overlay.querySelectorAll('[data-modal-mode]'));
    const heading = overlay.querySelector('[data-modal-heading]');
    const subheading = overlay.querySelector('[data-modal-subheading]');
    const submitButton = overlay.querySelector('.modal-submit');
    const modeField = overlay.querySelector('[data-modal-mode-field]');
    const footnoteText = overlay.querySelector('[data-modal-footnote-text]');
    const footnoteLink = overlay.querySelector('[data-modal-mode-toggle]');

    if (!form || !heading || !subheading || !submitButton || !modeField || !footnoteText || !footnoteLink) {
      return;
    }

    const setMode = (mode, { reset = false } = {}) => {
      if (mode !== 'signup' && mode !== 'login') return;
      overlay.dataset.mode = mode;

      modeTabs.forEach((tab) => {
        const isActive = tab.dataset.modalMode === mode;
        tab.classList.toggle('is-active', isActive);
        tab.setAttribute('aria-selected', isActive ? 'true' : 'false');
      });

      const isSignup = mode === 'signup';
      heading.textContent = isSignup ? 'Create your account' : 'Log in to your account';
      subheading.textContent = isSignup
        ? 'Get started with your free TRYGO workspace.'
        : 'Access your TRYGO workspace and continue your launch.';
      submitButton.textContent = isSignup ? 'Sign up' : 'Log in';
      modeField.value = mode;
      form.setAttribute('aria-label', isSignup ? 'Sign up form' : 'Log in form');
      footnoteText.textContent = isSignup ? 'Already have an account?' : 'Need an account?';
      footnoteLink.textContent = isSignup ? 'Log in' : 'Sign up';
      footnoteLink.dataset.modalModeToggle = isSignup ? 'login' : 'signup';

      if (reset) {
        form.reset();
      }
    };

    modeTabs.forEach((tab) => {
      tab.addEventListener('click', () => {
        setMode(tab.dataset.modalMode);
      });
    });

    footnoteLink.addEventListener('click', (event) => {
      event.preventDefault();
      const targetMode = footnoteLink.dataset.modalModeToggle;
      setMode(targetMode);
    });

    setMode('signup', { reset: true });
    setModeHandler = (mode, options) => setMode(mode, options);

    form.addEventListener('submit', (event) => {
      event.preventDefault();
      const mode = modeField.value || 'signup';
      closeModal();
      const message = mode === 'login'
        ? 'Welcome back! We will log you in shortly.'
        : 'Thanks! We will be in touch soon.';
      alert(message);
    });
  }

  function openModal() {
    const overlay = document.getElementById(MODAL_ID);
    if (!overlay) return;
    if (typeof setModeHandler === 'function') {
      setModeHandler('signup', { reset: true });
    }
    overlay.classList.add('is-open');
    document.body.classList.add('modal-open');
    const firstField = overlay.querySelector('input[name="email"]');
    if (firstField) {
      firstField.focus();
    }
  }

  function closeModal() {
    const overlay = document.getElementById(MODAL_ID);
    if (!overlay) return;
    overlay.classList.remove('is-open');
    document.body.classList.remove('modal-open');
  }
})();
