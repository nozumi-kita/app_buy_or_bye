import './bootstrap';

const initHamburgerMenu = () => {
    const hamburgerButton = document.querySelector('.hamburger-menu');
    const menu = document.querySelector('.nav');

    if (!hamburgerButton || !menu) return;

    hamburgerButton.addEventListener('click', () => {
        const isExpanded =
            hamburgerButton.getAttribute('aria-expanded') === 'true';

        hamburgerButton.setAttribute('aria-expanded', !isExpanded);

        hamburgerButton.classList.toggle('is-active');
        menu.classList.toggle('is-active');
    });
};

initHamburgerMenu();
