document.addEventListener('DOMContentLoaded', () => {
    initQtySelector();
    initGalleryThumbs();
    initZoomModal();
    initAccordion();
    initWishlist();
    initAddToCart();
});

/* ==============================
   SELECTOR DE CANTIDAD
============================== */
function initQtySelector() {
    const qtyInput = document.getElementById('qty-input');
    const qtyMinus = document.getElementById('qty-minus');
    const qtyPlus  = document.getElementById('qty-plus');

    if (!qtyInput || !qtyMinus || !qtyPlus) return;

    const max = parseInt(qtyInput.getAttribute('max'), 10) || 99;

    qtyMinus.addEventListener('click', () => {
        const val = parseInt(qtyInput.value, 10);
        if (val > 1) {
            qtyInput.value = val - 1;
            syncButtons();
        }
    });

    qtyPlus.addEventListener('click', () => {
        const val = parseInt(qtyInput.value, 10);
        if (val < max) {
            qtyInput.value = val + 1;
            syncButtons();
        }
    });

    qtyInput.addEventListener('change', () => {
        let val = parseInt(qtyInput.value, 10);
        if (isNaN(val) || val < 1) val = 1;
        if (val > max) val = max;
        qtyInput.value = val;
        syncButtons();
    });

    const comprarCantidad = document.getElementById('comprar-cantidad');

    function syncButtons() {
        const val = parseInt(qtyInput.value, 10);
        qtyMinus.disabled = val <= 1;
        qtyPlus.disabled  = val >= max;
        if (comprarCantidad) comprarCantidad.value = val;
    }

    syncButtons();
}

/* ==============================
   THUMBNAILS DE GALERÍA
============================== */
function initGalleryThumbs() {
    const thumbs  = document.querySelectorAll('.product-gallery__thumb');
    const mainImg = document.getElementById('gallery-main-img');

    if (!thumbs.length || !mainImg) return;

    thumbs.forEach(thumb => {
        thumb.addEventListener('click', () => {
            const src = thumb.dataset.src;
            if (src) mainImg.src = src;
            thumbs.forEach(t => t.classList.remove('active'));
            thumb.classList.add('active');
        });
    });
}

/* ==============================
   ZOOM MODAL
============================== */
function initZoomModal() {
    const galleryMain = document.getElementById('gallery-main');
    const mainImg     = document.getElementById('gallery-main-img');
    const zoomModal   = document.getElementById('zoom-modal');
    const zoomImg     = document.getElementById('zoom-img');
    const zoomClose   = document.getElementById('zoom-close');

    if (!galleryMain || !zoomModal || !zoomImg || !mainImg) return;

    galleryMain.addEventListener('click', () => {
        zoomImg.src = mainImg.src;
        zoomImg.alt = mainImg.alt;
        zoomModal.classList.add('active');
        document.body.style.overflow = 'hidden';
    });

    function closeZoom() {
        zoomModal.classList.remove('active');
        document.body.style.overflow = '';
    }

    zoomClose.addEventListener('click', e => {
        e.stopPropagation();
        closeZoom();
    });

    zoomModal.addEventListener('click', closeZoom);

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') closeZoom();
    });
}

/* ==============================
   ACORDEÓN DE DETALLES
============================== */
function initAccordion() {
    document.querySelectorAll('.product-accordion__trigger').forEach(trigger => {
        trigger.addEventListener('click', () => {
            const item   = trigger.closest('.product-accordion__item');
            const isOpen = item.classList.contains('open');
            item.classList.toggle('open', !isOpen);
            trigger.setAttribute('aria-expanded', !isOpen);
        });
    });
}

/* ==============================
   WISHLIST TOGGLE (visual)
============================== */
function initWishlist() {
    const btn = document.querySelector('.btn--wishlist');
    if (!btn) return;

    btn.addEventListener('click', () => {
        const isActive = btn.classList.toggle('active');
        btn.setAttribute(
            'aria-label',
            isActive ? 'Quitar de lista de deseos' : 'Guardar en lista de deseos'
        );
        // TODO: conectar con backend cuando el módulo esté listo
    });
}

/* ==============================
   AGREGAR AL CARRITO
============================== */
function initAddToCart() {
    const btn      = document.querySelector('.btn--add-cart');
    const qtyInput = document.getElementById('qty-input');

    if (!btn) return;

    btn.addEventListener('click', async () => {
        const proID    = parseInt(btn.dataset.productId, 10);
        const cantidad = qtyInput ? parseInt(qtyInput.value, 10) : 1;

        btn.disabled = true;
        const originalHTML = btn.innerHTML;
        btn.innerHTML = `
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
            Agregando…
        `;

        const result = await window.Carrito.agregar(proID, cantidad);

        if (result?.ok) {
            btn.innerHTML = `
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
                ¡Agregado!
            `;
            btn.style.background = '#16a34a';

            setTimeout(() => {
                btn.innerHTML    = originalHTML;
                btn.style.background = '';
                btn.disabled     = false;
            }, 2000);
        } else {
            btn.innerHTML = originalHTML;
            btn.disabled  = false;
        }
    });
}
