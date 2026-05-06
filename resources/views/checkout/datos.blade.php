@extends('layout.app')

@section('title', 'Datos de entrega | Unagi Piercing')

@section('content')

<div class="checkout-page">

    {{-- Steps --}}
    <div class="checkout-steps">
        <div class="checkout-step checkout-step--done">
            <span class="checkout-step__num">✓</span>
            <span>Carrito</span>
        </div>
        <span class="checkout-step__sep"></span>
        <div class="checkout-step checkout-step--active">
            <span class="checkout-step__num">2</span>
            <span>Datos</span>
        </div>
        <span class="checkout-step__sep"></span>
        <div class="checkout-step">
            <span class="checkout-step__num">3</span>
            <span>Pago</span>
        </div>
    </div>

    <form method="POST" action="{{ route('checkout.datos.guardar') }}" novalidate>
        @csrf

        <div class="checkout-layout">

            {{-- Columna izquierda: datos personales --}}
            <div class="checkout-form">
                <div class="checkout-form__title">Datos personales</div>

                @auth
                    {{-- Usuario autenticado: solo pedir celular --}}
                    <p style="font-size:.875rem; color:var(--color-text-secondary); margin-bottom:1rem;">
                        Hola, <strong>{{ Auth::user()->name }}</strong> ({{ Auth::user()->email }})
                    </p>
                    <div class="form-group @error('celular') form-group--error @enderror">
                        <label for="celular">Celular *</label>
                        <input type="tel" id="celular" name="celular"
                               value="{{ old('celular', $previos['celular'] ?? '') }}"
                               placeholder="Ej: 1155667788" required>
                        @error('celular')<span class="form-error">{{ $message }}</span>@enderror
                    </div>
                @else
                    {{-- Invitado: todos los datos --}}
                    <div class="form-row">
                        <div class="form-group @error('nombre') form-group--error @enderror">
                            <label for="nombre">Nombre *</label>
                            <input type="text" id="nombre" name="nombre"
                                   value="{{ old('nombre', $previos['nombre'] ?? '') }}" required>
                            @error('nombre')<span class="form-error">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group @error('apellido') form-group--error @enderror">
                            <label for="apellido">Apellido *</label>
                            <input type="text" id="apellido" name="apellido"
                                   value="{{ old('apellido', $previos['apellido'] ?? '') }}" required>
                            @error('apellido')<span class="form-error">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="form-group @error('email') form-group--error @enderror">
                        <label for="email">Email *</label>
                        <input type="email" id="email" name="email"
                               value="{{ old('email', $previos['email'] ?? '') }}" required>
                        @error('email')<span class="form-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-row">
                        <div class="form-group @error('celular') form-group--error @enderror">
                            <label for="celular">Celular *</label>
                            <input type="tel" id="celular" name="celular"
                                   value="{{ old('celular', $previos['celular'] ?? '') }}" required>
                            @error('celular')<span class="form-error">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group @error('fecha_nacimiento') form-group--error @enderror">
                            <label for="fecha_nacimiento">Fecha de nacimiento</label>
                            <input type="date" id="fecha_nacimiento" name="fecha_nacimiento"
                                   value="{{ old('fecha_nacimiento', $previos['fecha_nacimiento'] ?? '') }}">
                            @error('fecha_nacimiento')<span class="form-error">{{ $message }}</span>@enderror
                        </div>
                    </div>
                @endauth
            </div>

            {{-- Columna derecha: tipo de entrega --}}
            <div class="delivery-options">
                <div class="delivery-options__title">Tipo de entrega</div>

                @php $prevEntrega = old('pedEntrega', $previos['pedEntrega'] ?? '') @endphp

                <label class="delivery-card {{ $prevEntrega === 'EN' ? 'selected' : '' }}">
                    <input type="radio" name="pedEntrega" value="EN"
                           {{ $prevEntrega === 'EN' ? 'checked' : '' }} required>
                    <div class="delivery-card__body">
                        <div class="delivery-card__title">🏠 Envío Interno</div>
                        <div class="delivery-card__desc">Entrega a domicilio dentro de la zona</div>
                    </div>
                    <div class="delivery-card__price">${{ number_format($costoEnvioInterno, 0, ',', '.') }}</div>
                </label>

                <label class="delivery-card {{ $prevEntrega === 'RE' ? 'selected' : '' }}">
                    <input type="radio" name="pedEntrega" value="RE"
                           {{ $prevEntrega === 'RE' ? 'checked' : '' }}>
                    <div class="delivery-card__body">
                        <div class="delivery-card__title">🏪 Retiro en local</div>
                        <div class="delivery-card__desc">Dirección: Av. Ejemplo 1234, Buenos Aires</div>
                    </div>
                    <div class="delivery-card__price" style="color:var(--color-blue)">Gratis</div>
                </label>

                <label class="delivery-card {{ $prevEntrega === 'EP' ? 'selected' : '' }}">
                    <input type="radio" name="pedEntrega" value="EP"
                           {{ $prevEntrega === 'EP' ? 'checked' : '' }}>
                    <div class="delivery-card__body">
                        <div class="delivery-card__title">📦 Envío Provincial</div>
                        <div class="delivery-card__desc">A coordinar por WhatsApp después de la compra</div>
                    </div>
                    <div class="delivery-card__price" style="color:var(--color-text-muted); font-size:.8rem">A coordinar</div>
                </label>

                @error('pedEntrega')<p class="form-error" style="margin-top:.5rem">{{ $message }}</p>@enderror

                {{-- Campo dirección (solo para envío interno) --}}
                <div id="campo-direccion" style="margin-top:1rem; {{ $prevEntrega !== 'EN' ? 'display:none' : '' }}">
                    <div class="form-group @error('envDireccion') form-group--error @enderror">
                        <label for="envDireccion">Dirección de entrega *</label>
                        <input type="text" id="envDireccion" name="envDireccion"
                               value="{{ old('envDireccion', $previos['envDireccion'] ?? '') }}"
                               placeholder="Calle, número, piso/dpto">
                        @error('envDireccion')<span class="form-error">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>

        </div>

        <div style="display:flex; justify-content:flex-end; margin-top:1.5rem; gap:1rem;">
            <a href="{{ route('checkout.confirmar') }}" class="btn btn--outline">← Volver</a>
            <button type="submit" class="btn btn--primary">Continuar al pago →</button>
        </div>

    </form>

</div>

@endsection

@push('scripts')
    @vite('resources/js/pages/checkout-datos.js')
@endpush
