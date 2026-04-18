@extends('layout.app')

@section('title', 'Shop | Unagi Piercing & Joyeria')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/shop.css') }}">
@endpush

@section('content')

    {{-- =============================
         HERO SHOP (compacto)
    ============================== --}}
    <section class="hero hero--center" style="min-height: 30vh; background: var(--color-bg-muted);">
        <div class="hero__content" style="text-align: center;">
            <h1 style="font-size: clamp(1.75rem, 4vw, 3rem);">Shop</h1>
            <p>Joyeria hipoalergenica y piercing profesional. Encontra tu estilo.</p>
        </div>
    </section>

    {{-- =============================
         BREADCRUMB
    ============================== --}}
    <nav aria-label="Breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb__item"><a href="{{ route('home') }}">Inicio</a></li>
            <li class="breadcrumb__separator" aria-hidden="true">/</li>

            @if($categoriaSeleccionada)
                <li class="breadcrumb__item"><a href="{{ route('shop') }}">Shop</a></li>
                <li class="breadcrumb__separator" aria-hidden="true">/</li>
                <li class="breadcrumb__item breadcrumb__item--active" aria-current="page">
                    {{ $categorias->firstWhere('catID', $categoriaSeleccionada)->catNombre ?? 'Categoria' }}
                </li>
            @elseif($materialSeleccionado)
                <li class="breadcrumb__item"><a href="{{ route('shop') }}">Shop</a></li>
                <li class="breadcrumb__separator" aria-hidden="true">/</li>
                <li class="breadcrumb__item breadcrumb__item--active" aria-current="page">
                    {{ $materiales->firstWhere('matID', $materialSeleccionado)->matNombre ?? 'Material' }}
                </li>
            @else
                <li class="breadcrumb__item breadcrumb__item--active" aria-current="page">Shop</li>
            @endif
        </ol>
    </nav>

    {{-- =============================
         ACTIVE FILTERS (chips)
    ============================== --}}
    @if($categoriaSeleccionada || $materialSeleccionado)
        <div class="active-filters" style="max-width: var(--container-width); margin: 0 auto; padding: 0 2rem 0;">

            <span class="active-filters__label">Filtros:</span>

            @if($categoriaSeleccionada)
                <a href="{{ route('shop', array_filter(['material' => $materialSeleccionado])) }}" class="active-filters__chip">
                    {{ $categorias->firstWhere('catID', $categoriaSeleccionada)->catNombre ?? 'Categoria' }}
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </a>
            @endif

            @if($materialSeleccionado)
                <a href="{{ route('shop', array_filter(['categoria' => $categoriaSeleccionada])) }}" class="active-filters__chip">
                    {{ $materiales->firstWhere('matID', $materialSeleccionado)->matNombre ?? 'Material' }}
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </a>
            @endif

            <a href="{{ route('shop') }}" class="active-filters__clear">Limpiar todo</a>
        </div>
    @endif

    {{-- =============================
         MOBILE FILTER TOGGLE
    ============================== --}}
    <div style="max-width: var(--container-width); margin: 0 auto; padding: 0 2rem;">
        <button class="shop-filter-toggle" id="filterToggle" aria-expanded="false" aria-controls="shopSidebar">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="4" y1="21" x2="4" y2="14"></line>
                <line x1="4" y1="10" x2="4" y2="3"></line>
                <line x1="12" y1="21" x2="12" y2="12"></line>
                <line x1="12" y1="8" x2="12" y2="3"></line>
                <line x1="20" y1="21" x2="20" y2="16"></line>
                <line x1="20" y1="12" x2="20" y2="3"></line>
                <line x1="1" y1="14" x2="7" y2="14"></line>
                <line x1="9" y1="8" x2="15" y2="8"></line>
                <line x1="17" y1="16" x2="23" y2="16"></line>
            </svg>
            Filtrar
        </button>
    </div>

    {{-- =============================
         OVERLAY (mobile)
    ============================== --}}
    <div class="shop-sidebar__overlay" id="sidebarOverlay"></div>

    {{-- =============================
         SHOP LAYOUT
    ============================== --}}
    <div class="shop">

        {{-- ===== SIDEBAR FILTERS ===== --}}
        <aside class="shop-sidebar" id="shopSidebar" role="complementary" aria-label="Filtros de productos">

            {{-- Close button (mobile only) --}}
            <div class="shop-sidebar__close" style="display: none;">
                <span class="shop-sidebar__close-title">Filtros</span>
                <button class="shop-sidebar__close-btn" id="filterClose" aria-label="Cerrar filtros">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>

            {{-- FILTRO: Categorias --}}
            <div class="filter-group">
                <div class="filter-group__title">
                    Categorias
                    <button class="filter-group__toggle" aria-label="Expandir/colapsar categorias">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </button>
                </div>
                <ul class="filter-group__list">
                    <li class="filter-group__item {{ !$categoriaSeleccionada ? 'filter-group__item--active' : '' }}">
                        <a href="{{ route('shop', array_filter(['material' => $materialSeleccionado])) }}">
                            Todas
                        </a>
                    </li>
                    @foreach($categorias as $categoria)
                        <li class="filter-group__item {{ $categoriaSeleccionada == $categoria->catID ? 'filter-group__item--active' : '' }}">
                            <a href="{{ route('shop', array_filter(['categoria' => $categoria->catID, 'material' => $materialSeleccionado])) }}">
                                {{ $categoria->catNombre }}
                                @if(isset($categoria->productos_count))
                                    <span class="filter-group__count">{{ $categoria->productos_count }}</span>
                                @endif
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- FILTRO: Materiales --}}
            <div class="filter-group">
                <div class="filter-group__title">
                    Material
                    <button class="filter-group__toggle" aria-label="Expandir/colapsar materiales">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </button>
                </div>
                <ul class="filter-group__list">
                    <li class="filter-group__item {{ !$materialSeleccionado ? 'filter-group__item--active' : '' }}">
                        <a href="{{ route('shop', array_filter(['categoria' => $categoriaSeleccionada])) }}">
                            Todos
                        </a>
                    </li>
                    @foreach($materiales as $material)
                        <li class="filter-group__item {{ $materialSeleccionado == $material->matID ? 'filter-group__item--active' : '' }}">
                            <a href="{{ route('shop', array_filter(['categoria' => $categoriaSeleccionada, 'material' => $material->matID])) }}">
                                {{ $material->matNombre }}
                                @if(isset($material->productos_count))
                                    <span class="filter-group__count">{{ $material->productos_count }}</span>
                                @endif
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

        </aside>

        {{-- ===== MAIN CONTENT ===== --}}
        <div class="shop-main">

            {{-- Shop header (titulo + sort) --}}
            <div class="shop-header">
                <div class="shop-header__info">
                    <h2 class="shop-header__title">
                        @if($categoriaSeleccionada)
                            {{ $categorias->firstWhere('catID', $categoriaSeleccionada)->catNombre ?? 'Productos' }}
                        @elseif($materialSeleccionado)
                            {{ $materiales->firstWhere('matID', $materialSeleccionado)->matNombre ?? 'Productos' }}
                        @else
                            Todos los productos
                        @endif
                    </h2>
                    <span class="shop-header__count">
                        {{ $productos->total() }} {{ $productos->total() === 1 ? 'producto' : 'productos' }}
                    </span>
                </div>

                <div class="shop-header__sort">
                    <label for="sortSelect">Ordenar por:</label>
                    <select id="sortSelect" onchange="window.location.href = this.value;">
                        <option value="{{ route('shop', array_filter(['categoria' => $categoriaSeleccionada, 'material' => $materialSeleccionado])) }}"
                            {{ !request('orden') ? 'selected' : '' }}>
                            Mas recientes
                        </option>
                        <option value="{{ route('shop', array_filter(['categoria' => $categoriaSeleccionada, 'material' => $materialSeleccionado, 'orden' => 'precio_asc'])) }}"
                            {{ request('orden') === 'precio_asc' ? 'selected' : '' }}>
                            Menor precio
                        </option>
                        <option value="{{ route('shop', array_filter(['categoria' => $categoriaSeleccionada, 'material' => $materialSeleccionado, 'orden' => 'precio_desc'])) }}"
                            {{ request('orden') === 'precio_desc' ? 'selected' : '' }}>
                            Mayor precio
                        </option>
                        <option value="{{ route('shop', array_filter(['categoria' => $categoriaSeleccionada, 'material' => $materialSeleccionado, 'orden' => 'nombre'])) }}"
                            {{ request('orden') === 'nombre' ? 'selected' : '' }}>
                            A - Z
                        </option>
                    </select>
                </div>
            </div>

            {{-- Product grid --}}
            @if($productos->count() > 0)
                <div class="product-grid product-grid--3">
                    @foreach($productos as $producto)
                        @include('components.product-card', ['producto' => $producto])
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if($productos->hasPages())
                    <nav class="pagination" aria-label="Paginacion de productos">

                        {{-- Previous --}}
                        @if($productos->onFirstPage())
                            <span class="pagination__link pagination__prev pagination__prev--disabled" aria-disabled="true">
                                &larr; Anterior
                            </span>
                        @else
                            <a href="{{ $productos->previousPageUrl() }}" class="pagination__link pagination__prev" rel="prev">
                                &larr; Anterior
                            </a>
                        @endif

                        {{-- Page numbers --}}
                        @foreach($productos->getUrlRange(1, $productos->lastPage()) as $page => $url)
                            @if($page == $productos->currentPage())
                                <span class="pagination__current" aria-current="page">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="pagination__link">{{ $page }}</a>
                            @endif
                        @endforeach

                        {{-- Next --}}
                        @if($productos->hasMorePages())
                            <a href="{{ $productos->nextPageUrl() }}" class="pagination__link pagination__next" rel="next">
                                Siguiente &rarr;
                            </a>
                        @else
                            <span class="pagination__link pagination__next pagination__next--disabled" aria-disabled="true">
                                Siguiente &rarr;
                            </span>
                        @endif

                    </nav>
                @endif

            @else
                {{-- Empty state --}}
                <div class="shop-empty">
                    <div class="shop-empty__icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </div>
                    <h3 class="shop-empty__title">No encontramos productos</h3>
                    <p class="shop-empty__text">Intenta con otros filtros o explora todas nuestras categorias.</p>
                    <a href="{{ route('shop') }}" class="btn btn--primary">Ver todos los productos</a>
                </div>
            @endif

        </div>

    </div>

    {{-- =============================
         SHIPPING BAR
    ============================== --}}
    <div class="shipping-bar">
        Envio <strong>GRATIS</strong> en compras desde $80.000 &middot; 3 cuotas sin interes &middot; <strong>10% OFF</strong> con transferencia
    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            /* ==============================
               MOBILE SIDEBAR TOGGLE
            ============================== */
            const filterToggle = document.getElementById('filterToggle');
            const filterClose  = document.getElementById('filterClose');
            const sidebar      = document.getElementById('shopSidebar');
            const overlay      = document.getElementById('sidebarOverlay');
            const closeHeader  = sidebar ? sidebar.querySelector('.shop-sidebar__close') : null;

            function openFilters() {
                sidebar.classList.add('sidebar-active');
                overlay.classList.add('overlay-active');
                if (closeHeader) closeHeader.style.display = 'flex';
                document.body.style.overflow = 'hidden';
                filterToggle.setAttribute('aria-expanded', 'true');
            }

            function closeFilters() {
                sidebar.classList.remove('sidebar-active');
                overlay.classList.remove('overlay-active');
                if (closeHeader) closeHeader.style.display = 'none';
                document.body.style.overflow = '';
                filterToggle.setAttribute('aria-expanded', 'false');
            }

            if (filterToggle) filterToggle.addEventListener('click', openFilters);
            if (filterClose)  filterClose.addEventListener('click', closeFilters);
            if (overlay)      overlay.addEventListener('click', closeFilters);

            /* ==============================
               COLLAPSIBLE FILTER GROUPS
            ============================== */
            document.querySelectorAll('.filter-group__toggle').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    const group = btn.closest('.filter-group');
                    group.classList.toggle('collapsed');
                });
            });

        });
    </script>
@endpush
