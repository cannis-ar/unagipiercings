@extends('layout.app')

@section('title', ($producto->proNombre ?? 'Producto') . ' | Unagi Piercing')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/unagi/product.css') }}">
@endpush

@section('content')

    {{-- =============================
         BREADCRUMB
    ============================== --}}
    <nav aria-label="Navegación">
        <ol class="breadcrumb">
            <li class="breadcrumb__item">
                <a href="{{ route('home') }}">Inicio</a>
            </li>
            <li class="breadcrumb__separator" aria-hidden="true">/</li>

            @if($producto->categoria)
                <li class="breadcrumb__item">
                    <a href="#">{{ $producto->categoria->catNombre }}</a>
                </li>
                <li class="breadcrumb__separator" aria-hidden="true">/</li>
            @endif

            @if($producto->subcategoria)
                <li class="breadcrumb__item">
                    <a href="#">{{ $producto->subcategoria->scaNombre }}</a>
                </li>
                <li class="breadcrumb__separator" aria-hidden="true">/</li>
            @endif

            <li class="breadcrumb__item breadcrumb__item--active" aria-current="page">
                {{ $producto->proNombre }}
            </li>
        </ol>
    </nav>

    {{-- =============================
         LAYOUT PRINCIPAL
    ============================== --}}
    <div class="product-page">
        <div class="product-layout">

            {{-- ========================
                 GALERÍA
            ========================= --}}
            <div class="product-gallery">

                {{-- Imagen principal --}}
                <div class="product-gallery__main" id="gallery-main">
                    <img
                        src="{{ asset('storage/' . $producto->proImagen) }}"
                        alt="{{ $producto->proNombre }}"
                        id="gallery-main-img"
                        loading="eager"
                    >

                    {{-- Badges --}}
                    <div class="product-gallery__badge">
                        @if($producto->tiene_descuento)
                            <span class="product-card__tag product-card__tag--sale">
                                -{{ $producto->proPorcentajeDescuento }}%
                            </span>
                        @endif

                        @if($producto->proStock > 0 && $producto->proStock <= 5)
                            <span class="product-card__tag product-card__tag--featured">
                                ¡Últimas unidades!
                            </span>
                        @endif

                        @if($producto->proStock <= 0)
                            <span class="product-card__tag product-card__tag--sold-out">
                                Agotado
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Thumbnails (si hay más imágenes en el futuro, se mapean aquí) --}}
                <div class="product-gallery__thumbs" id="gallery-thumbs">
                    <button
                        class="product-gallery__thumb active"
                        data-src="{{ $producto->proImagen ? asset('storage/' . $producto->proImagen) : asset('images/placeholder.jpg') }}"
                        aria-label="Ver imagen principal"
                    >
                        <img
                            src="{{ $producto->proImagen ? asset('storage/' . $producto->proImagen) : asset('images/placeholder.jpg') }}"
                            alt="{{ $producto->proNombre }}"
                            loading="lazy"
                        >
                    </button>
                </div>

            </div>

            {{-- ========================
                 PANEL DE COMPRA
            ========================= --}}
            <div class="product-panel">

                {{-- Categoría / Meta --}}
                <div class="product-panel__meta">
                    @if($producto->categoria)
                        <a href="#">{{ $producto->categoria->catNombre }}</a>
                        <span class="product-panel__meta-sep" aria-hidden="true">/</span>
                    @endif
                    @if($producto->subcategoria)
                        <a href="#">{{ $producto->subcategoria->scaNombre }}</a>
                    @endif
                </div>

                {{-- Nombre --}}
                <h1 class="product-panel__name">{{ $producto->proNombre }}</h1>

                {{-- ---- PRECIOS ---- --}}
                <div class="product-panel__pricing">

                    <div class="product-panel__price-row">
                        <span class="product-panel__price-current">
                            ${{ number_format($producto->precio_final, 0, ',', '.') }}
                        </span>

                        @if($producto->tiene_descuento)
                            <span class="product-panel__price-old">
                                ${{ number_format($producto->proPrecio, 0, ',', '.') }}
                            </span>
                            <span class="product-panel__price-tag product-panel__price-tag--sale">
                                -{{ $producto->proPorcentajeDescuento }}% OFF
                            </span>
                        @endif
                    </div>

                    @if($producto->permite_cuotas)
                        <p class="product-panel__installments">
                            3 cuotas sin interés de
                            <strong>${{ number_format($producto->precio_final / 3, 0, ',', '.') }}</strong>
                        </p>
                    @endif

                    @if($producto->precio_transferencia)
                        <p class="product-panel__transfer">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <polyline points="9 11 12 14 22 4"></polyline>
                                <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                            </svg>
                            <strong>${{ number_format($producto->precio_transferencia, 0, ',', '.') }}</strong>
                            con transferencia
                            @if($producto->proPorcentajeDescuentoTransferencia)
                                ({{ $producto->proPorcentajeDescuentoTransferencia }}% OFF)
                            @endif
                        </p>
                    @endif

                </div>

                {{-- ---- SPECS RÁPIDAS ---- --}}
                <div class="product-panel__specs">

                    @if($producto->material)
                        <div class="product-spec">
                            <span class="product-spec__label">Material</span>
                            <span class="product-spec__value">{{ $producto->material->matNombre }}</span>
                        </div>
                    @endif

                    @if($producto->proGrosor)
                        <div class="product-spec">
                            <span class="product-spec__label">Grosor (gauge)</span>
                            <span class="product-spec__value">{{ $producto->proGrosor }}</span>
                        </div>
                    @endif

                    @if($producto->proDiametro)
                        <div class="product-spec">
                            <span class="product-spec__label">Diámetro</span>
                            <span class="product-spec__value">{{ $producto->proDiametro }}</span>
                        </div>
                    @endif

                    @if($producto->proLargo)
                        <div class="product-spec">
                            <span class="product-spec__label">Largo</span>
                            <span class="product-spec__value">{{ $producto->proLargo }}</span>
                        </div>
                    @endif

                    @if($producto->proTipoCierre)
                        <div class="product-spec">
                            <span class="product-spec__label">Tipo de cierre</span>
                            <span class="product-spec__value">{{ $producto->proTipoCierre }}</span>
                        </div>
                    @endif

                </div>

                {{-- ---- STOCK ---- --}}
                @if($producto->proStock > 5)
                    <p class="product-panel__stock product-panel__stock--in">
                        <span class="product-panel__stock-dot"></span>
                        En stock
                    </p>
                @elseif($producto->proStock > 0)
                    <p class="product-panel__stock product-panel__stock--low">
                        <span class="product-panel__stock-dot"></span>
                        Últimas {{ $producto->proStock }} unidades
                    </p>
                @else
                    <p class="product-panel__stock product-panel__stock--out">
                        <span class="product-panel__stock-dot"></span>
                        Sin stock
                    </p>
                @endif

                {{-- ---- ACCIONES ---- --}}
                <div class="product-panel__actions">

                    @if($producto->proStock > 0)
                        {{-- Cantidad + carrito --}}
                        <div class="product-panel__qty-row">
                            <div class="qty-selector" role="group" aria-label="Cantidad">
                                <button
                                    class="qty-selector__btn"
                                    id="qty-minus"
                                    aria-label="Reducir cantidad"
                                    type="button"
                                >−</button>
                                <input
                                    class="qty-selector__input"
                                    type="number"
                                    id="qty-input"
                                    value="1"
                                    min="1"
                                    max="{{ $producto->proStock }}"
                                    aria-label="Cantidad"
                                >
                                <button
                                    class="qty-selector__btn"
                                    id="qty-plus"
                                    aria-label="Aumentar cantidad"
                                    type="button"
                                >+</button>
                            </div>

                            <button
                                class="btn--add-cart"
                                type="button"
                                data-product-id="{{ $producto->proID }}"
                            >
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <circle cx="9" cy="21" r="1"></circle>
                                    <circle cx="20" cy="21" r="1"></circle>
                                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                                </svg>
                                Agregar al carrito
                            </button>

                            <button class="btn--wishlist" type="button" aria-label="Guardar en lista de deseos">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                                </svg>
                            </button>
                        </div>

                        <button class="btn--buy-now" type="button">
                            Comprar ahora
                        </button>

                    @else
                        {{-- Sin stock: avisar cuando llegue --}}
                        <button class="btn--buy-now" type="button" style="background: var(--color-text-muted); cursor: default;" disabled>
                            Sin stock disponible
                        </button>
                        <a href="https://wa.me/5491100000000?text=Hola!%20Quiero%20avisar%20cuando%20haya%20stock%20de%20{{ urlencode($producto->proNombre) }}" target="_blank" rel="noopener" class="btn btn--outline" style="justify-content:center; height:52px;">
                            Avisarme cuando llegue
                        </a>
                    @endif

                </div>

                {{-- ---- TRUST MINI ---- --}}
                <div class="product-panel__trust">
                    <div class="product-trust-item">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                        </svg>
                        Material hipoalergénico apto piel sensible
                    </div>
                    <div class="product-trust-item">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <rect x="1" y="3" width="15" height="13"></rect>
                            <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
                            <circle cx="5.5" cy="18.5" r="2.5"></circle>
                            <circle cx="18.5" cy="18.5" r="2.5"></circle>
                        </svg>
                        Envío a todo el país · Gratis desde $80.000
                    </div>
                    <div class="product-trust-item">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                        </svg>
                        Asesoramiento personalizado por WhatsApp
                    </div>
                </div>

            </div>
            {{-- /product-panel --}}

        </div>
        {{-- /product-layout --}}

        {{-- =============================
             ACORDEÓN DE DETALLES
        ============================== --}}
        <div class="product-details" id="product-details">

            {{-- Descripción --}}
            @if($producto->proDescripcion)
                <div class="product-accordion__item open">
                    <button class="product-accordion__trigger" aria-expanded="true" aria-controls="acc-descripcion">
                        Descripción
                        <svg class="product-accordion__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                    </button>
                    <div class="product-accordion__body" id="acc-descripcion">
                        <p class="product-description">{{ $producto->proDescripcion }}</p>
                    </div>
                </div>
            @endif

            {{-- Especificaciones técnicas --}}
            <div class="product-accordion__item">
                <button class="product-accordion__trigger" aria-expanded="false" aria-controls="acc-specs">
                    Especificaciones
                    <svg class="product-accordion__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                </button>
                <div class="product-accordion__body" id="acc-specs">
                    <table class="specs-table">
                        <tbody>
                        @if($producto->material)
                            <tr>
                                <th scope="row">Material</th>
                                <td>{{ $producto->material->matNombre }}</td>
                            </tr>
                        @endif
                        @if($producto->proGrosor)
                            <tr>
                                <th scope="row">Grosor (gauge)</th>
                                <td>{{ $producto->proGrosor }}</td>
                            </tr>
                        @endif
                        @if($producto->proLargo)
                            <tr>
                                <th scope="row">Largo</th>
                                <td>{{ $producto->proLargo }}</td>
                            </tr>
                        @endif
                        @if($producto->proDiametro)
                            <tr>
                                <th scope="row">Diámetro</th>
                                <td>{{ $producto->proDiametro }}</td>
                            </tr>
                        @endif
                        @if($producto->proTopTamano)
                            <tr>
                                <th scope="row">Tamaño del top</th>
                                <td>{{ $producto->proTopTamano }}</td>
                            </tr>
                        @endif
                        @if($producto->proEsferaTamano)
                            <tr>
                                <th scope="row">Tamaño de esfera</th>
                                <td>{{ $producto->proEsferaTamano }}</td>
                            </tr>
                        @endif
                        @if($producto->proTipoCierre)
                            <tr>
                                <th scope="row">Tipo de cierre</th>
                                <td>{{ $producto->proTipoCierre }}</td>
                            </tr>
                        @endif
                        @if($producto->categoria)
                            <tr>
                                <th scope="row">Categoría</th>
                                <td>{{ $producto->categoria->catNombre }}</td>
                            </tr>
                        @endif
                        @if($producto->subcategoria)
                            <tr>
                                <th scope="row">Subcategoría</th>
                                <td>{{ $producto->subcategoria->scaNombre }}</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Cuidados del material --}}
            @if($producto->material && $producto->material->matCuidados)
                <div class="product-accordion__item">
                    <button class="product-accordion__trigger" aria-expanded="false" aria-controls="acc-cuidados">
                        Cuidados
                        <svg class="product-accordion__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                    </button>
                    <div class="product-accordion__body" id="acc-cuidados">
                        <p class="product-care">{{ $producto->material->matCuidados }}</p>
                    </div>
                </div>
            @endif

        </div>
        {{-- /product-details --}}

        {{-- =============================
             PRODUCTOS RELACIONADOS
        ============================== --}}
        @if(isset($relacionados) && $relacionados->count() > 0)
            <section class="product-related">
                <div class="product-related__heading">
                    <h2>También te puede gustar</h2>
                    <a href="#">Ver todos &rarr;</a>
                </div>
                <div class="product-grid product-grid--4">
                    @foreach($relacionados as $rel)
                        @include('components.product-card', ['producto' => $rel])
                    @endforeach
                </div>
            </section>
        @endif

    </div>
    {{-- /product-page --}}

    {{-- =============================
         ZOOM MODAL
    ============================== --}}
    <div class="zoom-modal" id="zoom-modal" role="dialog" aria-label="Imagen ampliada" aria-modal="true">
        <button class="zoom-modal__close" id="zoom-close" aria-label="Cerrar">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>
        <img class="zoom-modal__img" id="zoom-img" src="" alt="">
    </div>

@endsection

@push('scripts')
    <script>
        (function () {
            'use strict';

            /* ---- Selector de cantidad ---- */
            const qtyInput  = document.getElementById('qty-input');
            const qtyMinus  = document.getElementById('qty-minus');
            const qtyPlus   = document.getElementById('qty-plus');

            if (qtyInput && qtyMinus && qtyPlus) {
                const max = parseInt(qtyInput.getAttribute('max'), 10) || 99;

                qtyMinus.addEventListener('click', () => {
                    const val = parseInt(qtyInput.value, 10);
                    if (val > 1) {
                        qtyInput.value = val - 1;
                        syncQtyButtons();
                    }
                });

                qtyPlus.addEventListener('click', () => {
                    const val = parseInt(qtyInput.value, 10);
                    if (val < max) {
                        qtyInput.value = val + 1;
                        syncQtyButtons();
                    }
                });

                qtyInput.addEventListener('change', () => {
                    let val = parseInt(qtyInput.value, 10);
                    if (isNaN(val) || val < 1) val = 1;
                    if (val > max) val = max;
                    qtyInput.value = val;
                    syncQtyButtons();
                });

                function syncQtyButtons() {
                    const val = parseInt(qtyInput.value, 10);
                    qtyMinus.disabled = (val <= 1);
                    qtyPlus.disabled  = (val >= max);
                }

                syncQtyButtons();
            }

            /* ---- Thumbnails de galería ---- */
            const thumbs   = document.querySelectorAll('.product-gallery__thumb');
            const mainImg  = document.getElementById('gallery-main-img');

            thumbs.forEach(thumb => {
                thumb.addEventListener('click', () => {
                    const src = thumb.dataset.src;
                    if (mainImg && src) {
                        mainImg.src = src;
                    }
                    thumbs.forEach(t => t.classList.remove('active'));
                    thumb.classList.add('active');
                });
            });

            /* ---- Zoom modal ---- */
            const galleryMain = document.getElementById('gallery-main');
            const zoomModal   = document.getElementById('zoom-modal');
            const zoomImg     = document.getElementById('zoom-img');
            const zoomClose   = document.getElementById('zoom-close');

            if (galleryMain && zoomModal && zoomImg) {
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

                zoomClose.addEventListener('click', (e) => {
                    e.stopPropagation();
                    closeZoom();
                });

                zoomModal.addEventListener('click', closeZoom);

                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape') closeZoom();
                });
            }

            /* ---- Acordeón ---- */
            const triggers = document.querySelectorAll('.product-accordion__trigger');

            triggers.forEach(trigger => {
                trigger.addEventListener('click', () => {
                    const item     = trigger.closest('.product-accordion__item');
                    const isOpen   = item.classList.contains('open');
                    const expanded = !isOpen;

                    item.classList.toggle('open', expanded);
                    trigger.setAttribute('aria-expanded', expanded);
                });
            });

            /* ---- Wishlist toggle (visual, sin backend aún) ---- */
            const wishlistBtn = document.querySelector('.btn--wishlist');
            if (wishlistBtn) {
                wishlistBtn.addEventListener('click', () => {
                    const isActive = wishlistBtn.classList.toggle('active');
                    wishlistBtn.setAttribute(
                        'aria-label',
                        isActive ? 'Quitar de lista de deseos' : 'Guardar en lista de deseos'
                    );
                    // Completar con llamada AJAX cuando el módulo esté listo
                });
            }

            /* ---- Agregar al carrito (placeholder) ---- */
            const addCartBtn = document.querySelector('.btn--add-cart');
            if (addCartBtn) {
                addCartBtn.addEventListener('click', () => {
                    const productId = addCartBtn.dataset.productId;
                    const qty       = qtyInput ? parseInt(qtyInput.value, 10) : 1;

                    // TODO: reemplazar con llamada real al carrito
                    console.log('Agregar al carrito:', { productId, qty });

                    // Feedback visual temporal
                    const originalText = addCartBtn.innerHTML;
                    addCartBtn.innerHTML = `
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
                ¡Agregado!
            `;
                    addCartBtn.style.background = '#16a34a';
                    addCartBtn.disabled = true;

                    setTimeout(() => {
                        addCartBtn.innerHTML = originalText;
                        addCartBtn.style.background = '';
                        addCartBtn.disabled = false;
                    }, 2000);
                });
            }

        })();
    </script>
@endpush
