@extends('layout.app')

@section('title', 'Unagi Piercing | Joyeria & Piercing Profesional')

@section('content')

    {{-- =============================
         HERO
    ============================== --}}
    <section class="hero hero--full hero--image" style="background-image: url('{{ asset('images/hero-home.jpg') }}');">
        <div class="hero__content">
            <h1>Diseña tu propio estilo</h1>
            <p>Joyeria de calidad y piercing profesional. Materiales hipoalergenicos con garantia.</p>
            <div class="hero__actions">
                <a href="#" class="btn btn--outline-white">Ver coleccion</a>
                <a href="#" class="btn btn--gold">Reservphpar turno</a>
            </div>
        </div>
    </section>

    {{-- =============================
         TRUST BADGES
    ============================== --}}
    <section class="trust-bar">
        <div class="trust-item">
            <div class="trust-item__icon">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--color-bordo)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                </svg>
            </div>
            <span class="trust-item__label">Apto piel sensible</span>
        </div>
        <div class="trust-item">
            <div class="trust-item__icon">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--color-blue)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0z"></path>
                </svg>
            </div>
            <span class="trust-item__label">Waterproof</span>
        </div>
        <div class="trust-item">
            <div class="trust-item__icon">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--color-gold)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                </svg>
            </div>
            <span class="trust-item__label">Ajuste universal</span>
        </div>
        <div class="trust-item">
            <div class="trust-item__icon">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--color-yellow)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="1" y="3" width="15" height="13"></rect>
                    <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
                    <circle cx="5.5" cy="18.5" r="2.5"></circle>
                    <circle cx="18.5" cy="18.5" r="2.5"></circle>
                </svg>
            </div>
            <span class="trust-item__label">Envio a todo el pais</span>
        </div>
    </section>

    {{-- =============================
         PRODUCTOS DESTACADOS
    ============================== --}}
    <section class="section">
        <div class="section-heading">
            <h2>Lo mas elegido</h2>
            <a href="#">Ver todo &rarr;</a>
        </div>

        <div class="product-grid product-grid--4">
            @foreach($productosNuevos as $producto)
                @include('components.product-card', ['producto' => $producto])
            @endforeach
        </div>
    </section>

    {{-- =============================
         BANNER FEATURED (50/50)
    ============================== --}}
    <section class="featured-banner">
        <div class="featured-banner__image">
            <img src="{{ asset('images/banner-ear-curation.jpg') }}" alt="Ear Curation">
        </div>
        <div class="featured-banner__content featured-banner__content--bordo">
            <span class="featured-banner__subtitle">Servicio exclusivo</span>
            <h2>Ear Curation</h2>
            <p>Diseñamos la combinacion perfecta de piercings y joyeria para tu oreja. Asesoramiento personalizado y colocacion profesional.</p>
            <a href="#" class="btn btn--outline-white">Reservar turno</a>
        </div>
    </section>

    {{-- =============================
         CATEGORIAS
    ============================== --}}
    <section class="section">
        <div class="section-heading section-heading--bar">
            <h2>Categorias</h2>
            <a href="#">Ver todas &rarr;</a>
        </div>

        <div class="category-grid" style="margin-top: 1.5rem;">
            @foreach($categorias as $categoria)
                <a href="#" class="category-card">
                    <img
                        src="{{ asset('images/categories/' . ($categoria->catImagen ?? 'placeholder.jpg')) }}"
                        alt="{{ $categoria->catNombre ?? 'FALTA DATO' }}"
                    >
                    <div class="category-card__overlay">
                <span class="category-card__name">
                    {{ $categoria->catNombre ?? 'FALTA DATO' }}
                </span>
                    </div>
                </a>
            @endforeach
        </div>
    </section>

    {{-- =============================
         SEGUNDO GRID DE PRODUCTOS
    ============================== --}}
    <section class="section">
        <div class="section-heading">
            <h2>Nuevos ingresos</h2>
            <a href="#">Ver todo &rarr;</a>
        </div>

        <div class="product-grid product-grid--4">
            <div class="product-grid product-grid--4">
                @foreach($productosNuevos as $producto)
                    @include('components.product-card', ['producto' => $producto])
                @endforeach
            </div>
        </div>
    </section>

    {{-- =============================
         BANNER FEATURED (invertido)
    ============================== --}}
    <section class="featured-banner">
        <div class="featured-banner__content featured-banner__content--blue">
            <span class="featured-banner__subtitle">Cuidados</span>
            <h2>Tu piercing, nuestro compromiso</h2>
            <p>Guias de cuidado post-perforacion, productos recomendados y seguimiento personalizado para que tu piercing sane perfecto.</p>
            <a href="#" class="btn btn--outline-white">Leer guia</a>
        </div>
        <div class="featured-banner__image">
            <img src="{{ asset('images/banner-cuidados.jpg') }}" alt="Cuidados piercing">
        </div>
    </section>

    {{-- =============================
         PARA REGALAR
    ============================== --}}
    <section class="section">
        <div class="section-heading">
            <h2>Para regalar</h2>
            <a href="#">Ver todo &rarr;</a>
        </div>

        <div class="product-grid product-grid--3">

            <div class="product-card">
                <div class="product-card__image">
                    <span class="product-card__tag product-card__tag--featured">Gift</span>
                    <img src="{{ asset('images/products/gift-1.jpg') }}" alt="Gift Box Clasico">
                </div>
                <div class="product-card__info">
                    <span class="product-card__name">Gift Box Clasico</span>
                    <div class="product-card__price">
                        <span class="product-card__price-current">$18.500</span>
                    </div>
                    <span class="product-card__installments">3 cuotas de <strong>$6.166</strong></span>
                </div>
            </div>

            <div class="product-card">
                <div class="product-card__image">
                    <img src="{{ asset('images/products/gift-2.jpg') }}" alt="Gift Box Premium">
                </div>
                <div class="product-card__info">
                    <span class="product-card__name">Gift Box Premium</span>
                    <div class="product-card__price">
                        <span class="product-card__price-current">$32.000</span>
                    </div>
                    <span class="product-card__installments">3 cuotas de <strong>$10.666</strong></span>
                </div>
            </div>

            <div class="product-card">
                <div class="product-card__image">
                    <img src="{{ asset('images/products/gift-3.jpg') }}" alt="Gift Card Unagi">
                </div>
                <div class="product-card__info">
                    <span class="product-card__name">Gift Card Unagi</span>
                    <div class="product-card__price">
                        <span class="product-card__price-current">Desde $10.000</span>
                    </div>
                </div>
            </div>

        </div>
    </section>

    {{-- =============================
         SHIPPING BAR
    ============================== --}}
    <div class="shipping-bar">
        Envio <strong>GRATIS</strong> en compras desde $80.000 &middot; 3 cuotas sin interes &middot; <strong>10% OFF</strong> con transferencia
    </div>

@endsection
