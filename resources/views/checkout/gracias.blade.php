@extends('layout.app')

@section('title', '¡Pedido confirmado! | Unagi Piercing')

@section('content')

<div class="gracias-page">

    {{-- Ícono animado --}}
    <div class="success-icon">
        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor"
             stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="20 6 9 17 4 12"></polyline>
        </svg>
    </div>

    <h1 style="font-size:1.75rem; margin-bottom:.5rem;">¡Tu pedido está confirmado!</h1>
    <p style="font-size:.9rem; color:var(--color-text-secondary); margin-bottom:.5rem;">
        Gracias por tu compra. Te avisaremos al email registrado cuando esté listo.
    </p>

    <span class="gracias-pedido">Pedido #{{ $pedido->pedID }}</span>

    {{-- Resumen --}}
    <div class="gracias-resumen">
        <div class="gracias-resumen__title">Resumen del pedido</div>

        @foreach($pedido->detalles as $detalle)
            <div class="gracias-item">
                <span>{{ $detalle->pdeNombre }} × {{ $detalle->pdeCantidad }}</span>
                <strong>${{ number_format($detalle->pdeSubtotal, 0, ',', '.') }}</strong>
            </div>
        @endforeach

        <div class="gracias-item" style="margin-top:.5rem;">
            <span>Tipo de entrega</span>
            <strong>
                @if($pedido->pedEntrega === 'EN') Envío interno
                @elseif($pedido->pedEntrega === 'RE') Retiro en local
                @else Envío provincial
                @endif
            </strong>
        </div>

        @if($pedido->envio && $pedido->envio->envDireccion)
            <div class="gracias-item">
                <span>Dirección</span>
                <strong>{{ $pedido->envio->envDireccion }}</strong>
            </div>
        @endif

        <div class="gracias-total">
            <span>Total pagado</span>
            <span>${{ number_format($pedido->pedTotal, 0, ',', '.') }}</span>
        </div>

        @if($pedido->pedPago === 'TR' && $pedido->pedTotalTransferencia)
            <div class="gracias-total" style="color:var(--color-gold-dark); border-top:none; padding-top:.25rem; font-size:.9rem;">
                <span>Con descuento transferencia</span>
                <span>${{ number_format($pedido->pedTotalTransferencia, 0, ',', '.') }}</span>
            </div>
        @endif
    </div>

    @if($pedido->pedPago === 'TR')
        <div style="background:color-mix(in srgb,var(--color-gold) 10%,transparent); border:1px solid color-mix(in srgb,var(--color-gold) 30%,transparent); border-radius:10px; padding:1rem 1.25rem; font-size:.875rem; color:var(--color-text-secondary); margin-bottom:2rem; text-align:left;">
            <strong style="color:var(--color-gold-dark);">⏱ Revisamos tu comprobante en menos de 24 hs.</strong><br>
            Una vez confirmado el pago, tu pedido pasará a estado "Abonado".
        </div>
    @endif

    <div class="gracias-acciones">
        <a href="#" class="btn btn--outline">Ver mis pedidos</a>
        <a href="{{ route('shop') }}" class="btn btn--primary">Seguir comprando →</a>
    </div>

</div>

@endsection
