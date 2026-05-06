{{-- Overlay --}}
<div class="carrito-overlay" id="carrito-overlay" aria-hidden="true"></div>

{{-- Drawer --}}
<aside class="carrito-drawer" id="carrito-drawer" role="dialog" aria-label="Carrito de compras" aria-modal="true" hidden>

    {{-- Header --}}
    <div class="carrito-drawer__header">
        <h2 class="carrito-drawer__title">Tu carrito</h2>
        <button class="carrito-drawer__close" id="carrito-close" type="button" aria-label="Cerrar carrito">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>
    </div>

    {{-- Items (renderizados por JS) --}}
    <div class="carrito-drawer__body" id="carrito-items">
        <div class="carrito-empty">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <circle cx="9" cy="21" r="1"></circle>
                <circle cx="20" cy="21" r="1"></circle>
                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
            </svg>
            <p>Tu carrito está vacío.</p>
            <button type="button" class="btn btn--outline" onclick="window.Carrito.cerrar()">Ver productos</button>
        </div>
    </div>

    {{-- Footer --}}
    <div class="carrito-drawer__footer" id="carrito-footer" style="display: none;">
        <div class="carrito-drawer__total">
            <span>Total</span>
            <strong id="carrito-total">$0</strong>
        </div>
        <a href="{{ route('checkout.confirmar') }}" class="btn btn--primary carrito-drawer__checkout">Finalizar compra</a>
        <button type="button" class="btn btn--outline carrito-drawer__continue" onclick="window.Carrito.cerrar()">
            Seguir comprando
        </button>
    </div>

</aside>
