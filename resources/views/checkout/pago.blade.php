@extends('layout.app')

@section('title', 'Selección de pago | Unagi Piercing')

@section('content')

<div class="checkout-page">

    {{-- Steps --}}
    <div class="checkout-steps">
        <div class="checkout-step checkout-step--done">
            <span class="checkout-step__num">✓</span>
            <span>Carrito</span>
        </div>
        <span class="checkout-step__sep"></span>
        <div class="checkout-step checkout-step--done">
            <span class="checkout-step__num">✓</span>
            <span>Datos</span>
        </div>
        <span class="checkout-step__sep"></span>
        <div class="checkout-step checkout-step--active">
            <span class="checkout-step__num">3</span>
            <span>Pago</span>
        </div>
    </div>

    @if(session('info'))
        <div style="background:color-mix(in srgb,var(--color-blue) 10%,transparent); border-left:3px solid var(--color-blue); padding:.875rem 1rem; border-radius:6px; font-size:.875rem; margin-bottom:1.5rem; color:var(--color-blue-dark);">
            {{ session('info') }}
        </div>
    @endif

    <div class="checkout-layout">

        {{-- Columna izquierda: medios de pago --}}
        <div>
            <div class="checkout-items__header">Elegí cómo pagar</div>
            <div class="payment-options">

                {{-- MercadoPago (próximamente) --}}
                <div class="payment-card payment-card--disabled">
                    <div class="payment-card__header">
                        <div class="payment-card__icon">
                            <svg width="40" height="40" viewBox="0 0 40 40" fill="none">
                                <rect width="40" height="40" rx="8" fill="#009EE3"/>
                                <text x="50%" y="55%" dominant-baseline="middle" text-anchor="middle" fill="white" font-size="10" font-weight="bold" font-family="sans-serif">MP</text>
                            </svg>
                        </div>
                        <div>
                            <div class="payment-card__title">MercadoPago</div>
                            <span class="badge badge--soon">Próximamente disponible</span>
                        </div>
                    </div>
                    <p class="payment-card__desc">Pagá con tarjeta de crédito, débito o dinero en cuenta.</p>
                    @if($permiteCuotas)
                        <span class="badge badge--cuotas" style="margin-bottom:.875rem">3 cuotas sin interés disponibles</span>
                    @endif
                    <button type="button" class="btn btn--primary" style="width:100%;justify-content:center;opacity:.5" disabled>
                        Pagar con MercadoPago
                    </button>
                </div>

                {{-- Transferencia --}}
                <form method="POST" action="{{ route('checkout.pago.post') }}">
                    @csrf
                    <input type="hidden" name="tipo" value="TR">
                    <div class="payment-card">
                        <div class="payment-card__header">
                            <div class="payment-card__icon">
                                <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="var(--color-gold-dark)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                                    <line x1="1" y1="10" x2="23" y2="10"></line>
                                </svg>
                            </div>
                            <div>
                                <div class="payment-card__title">Transferencia bancaria</div>
                                <span class="badge badge--off">{{ $descuento }}% OFF</span>
                            </div>
                        </div>
                        <p class="payment-card__desc">Pagá por transferencia y obtené un descuento exclusivo.</p>
                        <div class="payment-card__price-highlight">
                            ${{ number_format($totalTransferencia, 0, ',', '.') }}
                        </div>
                        <div class="payment-card__bank">
                            <strong>Datos bancarios</strong>
                            CBU: 0000003100012345678900 · Alias: UNAGI.PIERCINGS · Banco Galicia
                        </div>
                        <button type="submit" class="btn btn--primary" style="width:100%;justify-content:center;">
                            Pagar con Transferencia →
                        </button>
                    </div>
                </form>

            </div>
        </div>

        {{-- Columna derecha: resumen --}}
        <div class="order-summary">
            <div class="order-summary__title">Resumen del pedido</div>

            @foreach($items as $item)
                <div class="order-summary__item">
                    <img class="order-summary__item-img"
                         src="{{ $item['proImagen'] ? asset('storage/' . $item['proImagen']) : asset('images/placeholder.jpg') }}"
                         alt="{{ $item['proNombre'] }}">
                    <span class="order-summary__item-name">
                        {{ $item['proNombre'] }}<br>
                        <small style="color:var(--color-text-muted)">x{{ $item['cantidad'] }}</small>
                    </span>
                    <span class="order-summary__item-sub">${{ number_format($item['subtotal'], 0, ',', '.') }}</span>
                </div>
            @endforeach

            <div class="order-summary__divider"></div>

            <div class="order-summary__row">
                <span>Subtotal</span>
                <span>${{ number_format($subtotal, 0, ',', '.') }}</span>
            </div>
            <div class="order-summary__row">
                <span>Envío
                    @if($datos['pedEntrega'] === 'RE') (retiro)
                    @elseif($datos['pedEntrega'] === 'EP') (provincial)
                    @else (interno)
                    @endif
                </span>
                <span>{{ $costoEnvio > 0 ? '$' . number_format($costoEnvio, 0, ',', '.') : 'Gratis' }}</span>
            </div>
            <div class="order-summary__total">
                <span>Total</span>
                <span>${{ number_format($total, 0, ',', '.') }}</span>
            </div>
            <div class="order-summary__transfer">
                <span>Con transferencia ({{ $descuento }}% OFF)</span>
                <strong>${{ number_format($totalTransferencia, 0, ',', '.') }}</strong>
            </div>
        </div>

    </div>

</div>

@endsection
