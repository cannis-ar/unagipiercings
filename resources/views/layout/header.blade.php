{{-- Announcement Bar --}}
<div class="announcement-bar">
    <div class="announcement-bar__track">
        <span class="announcement-bar__item"><strong>Envio GRATIS</strong> en compras desde $80.000</span>
        <span class="announcement-bar__item">3 cuotas sin interes</span>
        <span class="announcement-bar__item"><strong>10% OFF</strong> con Transferencia</span>
        <span class="announcement-bar__item">Materiales hipoalergenicos</span>
        {{-- Duplicado para loop continuo --}}
        <span class="announcement-bar__item"><strong>Envio GRATIS</strong> en compras desde $80.000</span>
        <span class="announcement-bar__item">3 cuotas sin interes</span>
        <span class="announcement-bar__item"><strong>10% OFF</strong> con Transferencia</span>
        <span class="announcement-bar__item">Materiales hipoalergenicos</span>
    </div>
</div>

{{-- Color Bar --}}
<div class="color-bar color-bar--thin"></div>

{{-- Header --}}
<header class="header">
    <div class="header-inner">

        {{-- Logo --}}
        <div class="logo">
            <a href="{{ route('home') }}">
                {{-- Descomentar para usar imagen de logo --}}
                 <img src="{{ asset('images/logo-unagi.png') }}" alt="Unagi Piercing">
            </a>
        </div>

        {{-- Nav --}}
        <nav>
            <ul class="nav-links">
                <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Inicio</a></li>
                <li class="has-dropdown">
                    <a href="{{route('shop')}}">Shop Piercings</a>
                    <ul class="dropdown">
                        <li><a href="#">Aros</a></li>
                        <li><a href="#">Argollas</a></li>
                        <li><a href="#">Labrets</a></li>
                        <li><a href="#">Barbells</a></li>
                        <li><a href="#">Septum</a></li>
                        <li><a href="#">Ver todo</a></li>
                    </ul>
                </li>
                <li><a href="#">Cuidados</a></li>
                <li><a href="#">Contacto</a></li>
            </ul>
        </nav>

        {{-- Header Actions --}}
        <div class="header-actions">
            {{-- Search --}}
            <button type="button" aria-label="Buscar">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
            </button>

            {{-- User --}}
            <a href="#" aria-label="Mi cuenta">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
            </a>

            {{-- Cart --}}
            <a href="#" aria-label="Carrito" style="position: relative;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                    <path d="M16 10a4 4 0 0 1-8 0"></path>
                </svg>
                <span class="cart-count">0</span>
            </a>
        </div>

        {{-- Burger --}}
        <button class="burger" aria-label="Menu" type="button">
            <div></div>
            <div></div>
            <div></div>
        </button>

    </div>
</header>
