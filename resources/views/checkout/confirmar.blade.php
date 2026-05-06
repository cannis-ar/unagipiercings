@extends('layout.app')

@section('title', 'Confirmar pedido | Unagi Piercing')

@section('content')

<div class="checkout-page">

    {{-- Steps --}}
    <div class="checkout-steps">
        <div class="checkout-step checkout-step--active">
            <span class="checkout-step__num">1</span>
            <span>Carrito</span>
        </div>
        <span class="checkout-step__sep"></span>
        <div class="checkout-step">
            <span class="checkout-step__num">2</span>
            <span>Datos</span>
        </div>
        <span class="checkout-step__sep"></span>
        <div class="checkout-step">
            <span class="checkout-step__num">3</span>
            <span>Pago</span>
        </div>
    </div>

    <div class="checkout-layout">

        {{-- Columna izquierda: items --}}
        <div>
            <div class="checkout-items__header">Tus productos</div>
            <div id="checkout-items-list">
                @foreach($carrito->carProductos as $item)
                    <div class="checkout-item" data-pro-id="{{ $item['proID'] }}">
                        <div class="checkout-item__img-wrap">
                            <img class="checkout-item__img"
                                 src="{{ $item['proImagen'] ? asset('storage/' . $item['proImagen']) : asset('images/placeholder.jpg') }}"
                                 alt="{{ $item['proNombre'] }}"
                                 loading="lazy">
                            @if($item['tieneDescuento'])
                                <span class="checkout-item__badge">-{{ $item['porcentajeDescuento'] }}%</span>
                            @endif
                        </div>
                        <div class="checkout-item__info">
                            <span class="checkout-item__nombre">{{ $item['proNombre'] }}</span>
                            <span class="checkout-item__precio">
                                @if($item['tieneDescuento'])
                                    <s>${{ number_format($item['proPrecio'], 0, ',', '.') }}</s>
                                @endif
                                ${{ number_format($item['precioPagado'], 0, ',', '.') }}
                            </span>
                            <div class="checkout-item__controls">
                                <div class="carrito-item__qty" role="group" aria-label="Cantidad">
                                    <button class="carrito-qty-btn" data-action="dec"
                                            data-pro-id="{{ $item['proID'] }}"
                                            {{ $item['cantidad'] <= 1 ? 'disabled' : '' }}>−</button>
                                    <span class="carrito-qty-value">{{ $item['cantidad'] }}</span>
                                    <button class="carrito-qty-btn" data-action="inc"
                                            data-pro-id="{{ $item['proID'] }}">+</button>
                                </div>
                                <span class="checkout-item__subtotal" id="sub-{{ $item['proID'] }}">
                                    ${{ number_format($item['subtotal'], 0, ',', '.') }}
                                </span>
                                <button class="checkout-item__remove"
                                        onclick="window.Carrito.quitar({{ $item['proID'] }})"
                                        aria-label="Eliminar {{ $item['proNombre'] }}" type="button">×</button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Columna derecha: resumen sticky --}}
        <div class="checkout-summary">
            <div class="checkout-summary__title">Resumen</div>
            <div class="checkout-summary__row">
                <span>Subtotal</span>
                <span id="co-subtotal">${{ number_format($carrito->carTotal, 0, ',', '.') }}</span>
            </div>
            <div class="checkout-summary__row">
                <span>Envío</span>
                <span>A confirmar</span>
            </div>
            <div class="checkout-summary__row checkout-summary__row--total">
                <span>Estimado</span>
                <span id="co-total">${{ number_format($carrito->carTotal, 0, ',', '.') }}</span>
            </div>
            <a href="{{ route('checkout.datos') }}" class="btn btn--primary" style="width:100%;justify-content:center;" id="btn-continuar">
                Continuar →
            </a>
        </div>

    </div>

    {{-- Relacionados --}}
    @if($relacionados->count() > 0)
        <section class="checkout-related">
            <div class="checkout-related__heading">
                <h2>¿Querés agregar algo más?</h2>
            </div>
            <div class="product-grid product-grid--4">
                @foreach($relacionados as $rel)
                    @include('components.product-card', ['producto' => $rel])
                @endforeach
            </div>
        </section>
    @endif

</div>

@endsection

@push('scripts')
    @vite('resources/js/pages/checkout-confirmar.js')
@endpush
