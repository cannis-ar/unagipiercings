@extends('layout.app')

@section('title', 'Enviar comprobante | Unagi Piercing')

@section('content')

<div class="transfer-page">

    <h1 style="font-size:1.5rem; margin-bottom:.5rem;">Completá tu pago</h1>
    <p style="font-size:.875rem; color:var(--color-text-secondary); margin-bottom:1.5rem;">
        Pedido <strong>#{{ $pedido->pedID }}</strong> · Total a transferir:
        <strong style="color:var(--color-gold-dark)">${{ number_format($totalTransferencia, 0, ',', '.') }}</strong>
    </p>

    {{-- Datos bancarios --}}
    <div class="bank-details">
        <div class="bank-details__title">Datos para la transferencia</div>
        <div class="bank-detail-row">
            <span>CBU</span>
            <strong>0000003100012345678900</strong>
        </div>
        <div class="bank-detail-row">
            <span>Alias</span>
            <strong>UNAGI.PIERCINGS</strong>
        </div>
        <div class="bank-detail-row">
            <span>Banco</span>
            <strong>Banco Galicia</strong>
        </div>
        <div class="bank-detail-row">
            <span>Titular</span>
            <strong>Unagi Piercing S.R.L.</strong>
        </div>
        <div class="bank-detail-row">
            <span>Monto exacto</span>
            <strong style="color:var(--color-gold-dark); font-size:1.05rem;">
                ${{ number_format($totalTransferencia, 0, ',', '.') }}
            </strong>
        </div>
    </div>

    {{-- Instrucciones --}}
    <ol class="transfer-steps">
        <li>Abrí la app de tu banco o billetera virtual.</li>
        <li>Realizá la transferencia por el monto exacto indicado arriba.</li>
        <li>Guardá el comprobante (imagen o PDF).</li>
        <li>Subilo en el formulario de abajo y envialo.</li>
    </ol>

    {{-- Formulario --}}
    <form method="POST" action="{{ route('checkout.transferencia.post', $pedido->pedID) }}" enctype="multipart/form-data" novalidate>
        @csrf

        @if($errors->any())
            <div style="background:color-mix(in srgb,var(--color-bordo) 8%,transparent); border-left:3px solid var(--color-bordo); padding:.875rem 1rem; border-radius:6px; font-size:.875rem; margin-bottom:1.25rem; color:var(--color-bordo);">
                {{ $errors->first() }}
            </div>
        @endif

        {{-- Upload --}}
        <div class="form-group" style="margin-bottom:1.25rem;">
            <label>Comprobante de transferencia *</label>
            <div class="upload-area" id="upload-area" onclick="document.getElementById('comprobante').click()">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="var(--color-text-muted)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" id="upload-icon">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                    <polyline points="17 8 12 3 7 8"></polyline>
                    <line x1="12" y1="3" x2="12" y2="15"></line>
                </svg>
                <p style="margin:.5rem 0 .25rem; font-size:.875rem; color:var(--color-text-secondary);" id="upload-label">
                    Hacé clic para seleccionar
                </p>
                <p style="font-size:.75rem; color:var(--color-text-muted);">JPG, PNG o PDF · Máx. 5 MB</p>
                <img id="upload-preview" class="upload-preview" src="" alt="Preview del comprobante">
            </div>
            <input type="file" id="comprobante" name="comprobante" accept=".jpg,.jpeg,.png,.pdf"
                   style="display:none" required>
        </div>

        <div class="form-group" style="margin-bottom:1.5rem;">
            <label for="fecha_pago">Fecha de pago</label>
            <input type="date" id="fecha_pago" name="fecha_pago" value="{{ date('Y-m-d') }}" style="max-width:200px;">
        </div>

        <button type="submit" class="btn btn--primary" style="width:100%;justify-content:center;">
            Enviar comprobante →
        </button>

    </form>

</div>

@endsection

@push('scripts')
    @vite('resources/js/pages/checkout-transferencia.js')
@endpush
