import './bootstrap';

document.addEventListener('DOMContentLoaded', () => {

    /* ==============================
       BURGER MENU
    ============================== */
    const burger = document.querySelector('.burger');
    const nav    = document.querySelector('.nav-links');

    if (burger && nav) {
        burger.addEventListener('click', () => {
            nav.classList.toggle('nav-active');
            burger.classList.toggle('active');
        });

        document.querySelectorAll('.nav-links a').forEach(link => {
            link.addEventListener('click', () => {
                nav.classList.remove('nav-active');
                burger.classList.remove('active');
            });
        });
    }

    /* ==============================
       HEADER SCROLL SHADOW
    ============================== */
    const header = document.querySelector('.header');
    if (header) {
        window.addEventListener('scroll', () => {
            header.classList.toggle('scrolled', window.scrollY > 10);
        }, { passive: true });
    }

});
