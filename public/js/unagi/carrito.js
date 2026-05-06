(function () {
    'use strict';

    /* ==============================
       CONSTANTES
    ============================== */
    const ENDPOINTS = {
        index:     '/carrito',
        agregar:   '/carrito/agregar',
        quitar:    '/carrito/quitar',
        actualizar:'/carrito/actualizar',
        vaciar:    '/carrito/vaciar',
    };

    const LS_KEY = 'carrito_items';

    /* ==============================
       UTILIDADES
    ============================== */
    function getCsrf() {
        return document.querySelector('meta[name="csrf-token"]')?.content ?? '';
    }

    function fmt(n) {
        return Number(n).toLocaleString('es-AR', { minimumFractionDigits: 0 });
    }

    async function fetchAPI(url, method = 'GET', body = null) {
        const opts = {
            method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCsrf(),
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            },
            credentials: 'same-origin',
        };
        if (body) opts.body = JSON.stringify(body);
        const res = await fetch(url, opts);
        return res.json();
    }

    /* ==============================
       CACHÉ LOCAL (optimistic UI)
    ============================== */
    function lsGet() {
        try {
            return JSON.parse(localStorage.getItem(LS_KEY)) ?? { items: [], total: 0, cantidad: 0 };
        } catch {
            return { items: [], total: 0, cantidad: 0 };
        }
    }

    function lsSet(data) {
        localStorage.setItem(LS_KEY, JSON.stringify(data));
    }

    /* ==============================
       ACTUALIZACIÓN DE UI
    ============================== */
    function updateBadge(cantidad) {
        const badge = document.querySelector('.cart-count');
        if (!badge) return;
        badge.textContent = cantidad;
        badge.style.display = cantidad > 0 ? 'flex' : 'none';
    }

    function renderItems(items) {
        const container = document.getElementById('carrito-items');
        const footer    = document.getElementById('carrito-footer');
        if (!container) return;

        if (!items || !items.length) {
            container.innerHTML = `
                <div class="carrito-empty">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="9" cy="21" r="1"></circle>
                        <circle cx="20" cy="21" r="1"></circle>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                    </svg>
                    <p>Tu carrito está vacío.</p>
                    <button type="button" class="btn btn--outline" onclick="window.Carrito.cerrar()">Ver productos</button>
                </div>`;
            if (footer) footer.style.display = 'none';
            return;
        }

        container.innerHTML = items.map(item => {
            const imgSrc = item.proImagen
                ? `/storage/${item.proImagen}`
                : '/images/placeholder.jpg';

            const descuentoBadge = item.tieneDescuento
                ? `<span class="carrito-item__badge">-${item.porcentajeDescuento}%</span>`
                : '';

            const precioViejo = item.tieneDescuento
                ? `<s class="carrito-item__precio-old">$${fmt(item.proPrecio)}</s>`
                : '';

            return `
                <div class="carrito-item" data-pro-id="${item.proID}">
                    <div class="carrito-item__img-wrap">
                        <img src="${imgSrc}" alt="${item.proNombre}" class="carrito-item__img" loading="lazy">
                        ${descuentoBadge}
                    </div>
                    <div class="carrito-item__info">
                        <span class="carrito-item__nombre">${item.proNombre}</span>
                        <div class="carrito-item__precios">
                            ${precioViejo}
                            <span class="carrito-item__precio">$${fmt(item.precioPagado)}</span>
                        </div>
                        <div class="carrito-item__controls">
                            <div class="carrito-item__qty" role="group" aria-label="Cantidad">
                                <button class="carrito-qty-btn" data-action="dec" data-pro-id="${item.proID}" aria-label="Reducir cantidad" ${item.cantidad <= 1 ? 'disabled' : ''}>−</button>
                                <span class="carrito-qty-value">${item.cantidad}</span>
                                <button class="carrito-qty-btn" data-action="inc" data-pro-id="${item.proID}" aria-label="Aumentar cantidad">+</button>
                            </div>
                            <span class="carrito-item__subtotal">$${fmt(item.subtotal)}</span>
                            <button class="carrito-item__remove" data-pro-id="${item.proID}" type="button" aria-label="Eliminar ${item.proNombre}">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>`;
        }).join('');

        if (footer) footer.style.display = '';
    }

    function renderTotal(total) {
        const el = document.getElementById('carrito-total');
        if (el) el.textContent = `$${fmt(total)}`;
    }

    function syncUI(data) {
        lsSet(data);
        updateBadge(data.cantidad);
        renderItems(data.items);
        renderTotal(data.total);
        window.dispatchEvent(new CustomEvent('carrito:updated', { detail: data }));
    }

    /* ==============================
       DRAWER
    ============================== */
    function abrir() {
        const drawer  = document.getElementById('carrito-drawer');
        const overlay = document.getElementById('carrito-overlay');
        if (!drawer) return;

        drawer.removeAttribute('hidden');
        requestAnimationFrame(() => drawer.classList.add('active'));
        overlay?.classList.add('active');
        document.body.style.overflow = 'hidden';

        // Sincronizar con el servidor al abrir
        fetchAPI(ENDPOINTS.index).then(data => {
            if (data.ok) syncUI(data.carrito);
        }).catch(() => {});
    }

    function cerrar() {
        const drawer  = document.getElementById('carrito-drawer');
        const overlay = document.getElementById('carrito-overlay');
        if (!drawer) return;

        drawer.classList.remove('active');
        overlay?.classList.remove('active');
        document.body.style.overflow = '';

        // Ocultar tras la animación
        drawer.addEventListener('transitionend', () => {
            if (!drawer.classList.contains('active')) drawer.setAttribute('hidden', '');
        }, { once: true });
    }

    /* ==============================
       API PÚBLICA
    ============================== */
    async function agregar(proID, cantidad = 1) {
        // Optimista: incrementar badge de inmediato
        const prev   = lsGet();
        const newQty = (prev.cantidad || 0) + cantidad;
        updateBadge(newQty);

        try {
            const data = await fetchAPI(ENDPOINTS.agregar, 'POST', { proID, cantidad });
            if (data.ok) {
                syncUI(data.carrito);
            } else {
                updateBadge(prev.cantidad); // revertir badge
                showError(data.mensaje ?? 'No se pudo agregar el producto.');
            }
            return data;
        } catch {
            updateBadge(prev.cantidad);
            return { ok: false };
        }
    }

    async function quitar(proID) {
        const prev = lsGet();
        try {
            const data = await fetchAPI(ENDPOINTS.quitar, 'POST', { proID });
            if (data.ok) syncUI(data.carrito);
            else syncUI(prev);
            return data;
        } catch {
            syncUI(prev);
            return { ok: false };
        }
    }

    async function actualizar(proID, cantidad) {
        const prev = lsGet();

        // Optimista: actualizar UI localmente
        const itemsOpt = prev.items.map(i =>
            i.proID === proID
                ? { ...i, cantidad, subtotal: i.precioPagado * cantidad }
                : i
        );
        const totalOpt  = itemsOpt.reduce((s, i) => s + i.subtotal, 0);
        const cantOpt   = itemsOpt.reduce((s, i) => s + i.cantidad, 0);
        syncUI({ items: itemsOpt, total: totalOpt, cantidad: cantOpt });

        try {
            const data = await fetchAPI(ENDPOINTS.actualizar, 'POST', { proID, cantidad });
            if (data.ok) syncUI(data.carrito);
            else syncUI(prev);
            return data;
        } catch {
            syncUI(prev);
            return { ok: false };
        }
    }

    async function vaciar() {
        const prev = lsGet();
        syncUI({ items: [], total: 0, cantidad: 0 });

        try {
            const data = await fetchAPI(ENDPOINTS.vaciar, 'POST');
            if (data.ok) syncUI(data.carrito);
            else syncUI(prev);
            return data;
        } catch {
            syncUI(prev);
            return { ok: false };
        }
    }

    /* ==============================
       FEEDBACK DE ERROR
    ============================== */
    function showError(msg) {
        const el = document.createElement('div');
        el.className  = 'carrito-toast carrito-toast--error';
        el.textContent = msg;
        document.body.appendChild(el);
        requestAnimationFrame(() => el.classList.add('visible'));
        setTimeout(() => {
            el.classList.remove('visible');
            el.addEventListener('transitionend', () => el.remove(), { once: true });
        }, 3500);
    }

    /* ==============================
       EVENT LISTENERS DEL DRAWER
    ============================== */
    function bindDrawerEvents() {
        // Delegación en el body de items
        const container = document.getElementById('carrito-items');
        if (container) {
            container.addEventListener('click', e => {
                const qtyBtn    = e.target.closest('[data-action]');
                const removeBtn = e.target.closest('.carrito-item__remove');

                if (qtyBtn) {
                    const proID   = parseInt(qtyBtn.dataset.proId, 10);
                    const action  = qtyBtn.dataset.action;
                    const qtyEl   = qtyBtn.closest('.carrito-item__qty')?.querySelector('.carrito-qty-value');
                    const current = qtyEl ? parseInt(qtyEl.textContent, 10) : 1;

                    if (action === 'dec' && current > 1) actualizar(proID, current - 1);
                    if (action === 'inc') actualizar(proID, current + 1);
                }

                if (removeBtn) {
                    quitar(parseInt(removeBtn.dataset.proId, 10));
                }
            });
        }

        document.getElementById('carrito-close')
            ?.addEventListener('click', cerrar);

        document.getElementById('carrito-overlay')
            ?.addEventListener('click', cerrar);

        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') cerrar();
        });
    }

    /* ==============================
       INIT
    ============================== */
    document.addEventListener('DOMContentLoaded', () => {
        // Badge inicial desde caché
        const cache = lsGet();
        updateBadge(cache.cantidad);

        // Ícono del carrito en el header
        document.querySelector('[aria-label="Carrito"]')
            ?.addEventListener('click', e => {
                e.preventDefault();
                abrir();
            });

        bindDrawerEvents();
    });

    /* ---- Exponer API global ---- */
    window.Carrito = { agregar, quitar, actualizar, vaciar, abrir, cerrar };

})();
