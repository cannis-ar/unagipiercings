document.addEventListener('DOMContentLoaded', () => {

    /* ---- Re-render de items cuando el carrito cambia ---- */
    window.addEventListener('carrito:updated', e => {
        const { items, total } = e.detail;
        renderItems(items);
        updateTotals(total);
    });

    /* ---- Delegación en los qty-btns del confirmar ---- */
    const list = document.getElementById('checkout-items-list');
    if (list) {
        list.addEventListener('click', e => {
            const btn = e.target.closest('[data-action]');
            if (!btn) return;

            const proID   = parseInt(btn.dataset.proId, 10);
            const action  = btn.dataset.action;
            const qtyEl   = btn.closest('.carrito-item__qty')?.querySelector('.carrito-qty-value');
            const current = qtyEl ? parseInt(qtyEl.textContent, 10) : 1;

            if (action === 'dec' && current > 1) window.Carrito.actualizar(proID, current - 1);
            if (action === 'inc') window.Carrito.actualizar(proID, current + 1);
        });
    }

    function fmt(n) {
        return Number(n).toLocaleString('es-AR', { minimumFractionDigits: 0 });
    }

    function renderItems(items) {
        const container = document.getElementById('checkout-items-list');
        const btnCont   = document.getElementById('btn-continuar');
        if (!container) return;

        if (!items || !items.length) {
            container.innerHTML = '<p style="color:var(--color-text-muted); font-size:.875rem; padding:1rem 0;">Tu carrito está vacío. <a href="/shop">Ver productos</a></p>';
            if (btnCont) btnCont.style.pointerEvents = 'none';
            return;
        }

        if (btnCont) btnCont.style.pointerEvents = '';

        container.innerHTML = items.map(item => {
            const imgSrc = item.proImagen
                ? `/storage/${item.proImagen}`
                : '/images/placeholder.jpg';
            const badge = item.tieneDescuento
                ? `<span class="checkout-item__badge">-${item.porcentajeDescuento}%</span>` : '';
            const precioViejo = item.tieneDescuento
                ? `<s>$${fmt(item.proPrecio)}</s> ` : '';

            return `
                <div class="checkout-item" data-pro-id="${item.proID}">
                    <div class="checkout-item__img-wrap">
                        <img class="checkout-item__img" src="${imgSrc}" alt="${item.proNombre}" loading="lazy">
                        ${badge}
                    </div>
                    <div class="checkout-item__info">
                        <span class="checkout-item__nombre">${item.proNombre}</span>
                        <span class="checkout-item__precio">${precioViejo}$${fmt(item.precioPagado)}</span>
                        <div class="checkout-item__controls">
                            <div class="carrito-item__qty" role="group">
                                <button class="carrito-qty-btn" data-action="dec" data-pro-id="${item.proID}" ${item.cantidad <= 1 ? 'disabled' : ''}>−</button>
                                <span class="carrito-qty-value">${item.cantidad}</span>
                                <button class="carrito-qty-btn" data-action="inc" data-pro-id="${item.proID}">+</button>
                            </div>
                            <span class="checkout-item__subtotal">$${fmt(item.subtotal)}</span>
                            <button class="checkout-item__remove" onclick="window.Carrito.quitar(${item.proID})" type="button">×</button>
                        </div>
                    </div>
                </div>`;
        }).join('');
    }

    function updateTotals(total) {
        const fmt = n => Number(n).toLocaleString('es-AR', { minimumFractionDigits: 0 });
        const sub = document.getElementById('co-subtotal');
        const tot = document.getElementById('co-total');
        if (sub) sub.textContent = `$${fmt(total)}`;
        if (tot) tot.textContent = `$${fmt(total)}`;
    }

});
