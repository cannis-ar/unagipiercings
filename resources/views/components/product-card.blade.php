<div class="product-card">
    <a href="{{ route('product.show', $producto->proSlug) }}" class="product-card__link">

        <div class="product-card__image">
            <img
                src="{{ $producto->proImagen ? asset('storage/' . $producto->proImagen) : asset('images/placeholder.jpg') }}"
                alt="{{ $producto->proNombre ?? 'FALTA DATO' }}"
            >
        </div>

        <div class="product-card__info">

            {{-- Nombre --}}
            <span class="product-card__name">
                {{ $producto->proNombre ?? 'FALTA DATO' }}
            </span>

            {{-- Precio --}}
            <div class="product-card__price">
                <span class="product-card__price-current">
                    ${{ number_format($producto->precio_final, 0, ',', '.') }}
                </span>

                @if($producto->tiene_descuento)
                    <span class="product-card__price-old">
                        ${{ number_format($producto->proPrecio, 0, ',', '.') }}
                    </span>
                    <span class="product-card__price-discount">
                        -{{ $producto->proPorcentajeDescuento }}%
                    </span>
                @endif
            </div>

            {{-- Cuotas --}}
            @if($producto->permite_cuotas)
                <span class="product-card__installments">
                    3 cuotas de
                    <strong>
                        ${{ number_format($producto->precio_final / 3, 0, ',', '.') }}
                    </strong>
                </span>
            @endif

            {{-- Transferencia --}}
            @if($producto->precio_transferencia)
                <span class="product-card__transfer">
                    ${{ number_format($producto->precio_transferencia, 0, ',', '.') }}
                    con transferencia
                </span>
            @endif

        </div>

    </a>
</div>
