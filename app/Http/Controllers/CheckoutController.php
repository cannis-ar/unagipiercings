<?php

namespace App\Http\Controllers;

use App\Models\eCommerce\CarritoModel;
use App\Models\Envios\EnvioModel;
use App\Models\Parametros\ParametroModel;
use App\Models\Pedidos\PedidoDetalleModel;
use App\Models\Pedidos\PedidoModel;
use App\Models\Productos\ProductoModel;
use App\Models\Transacciones\ComprobanteModel;
use App\Models\Transacciones\OperacionModel;
use App\Models\Usuario\UsuarioModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    private const COOKIE_NAME = 'carrito_token';
    private const COOKIE_TTL  = 60 * 24 * 365;

    /* ==============================
       RESOLUCIÓN DE CARRITO
    ============================== */

    private function resolverCarrito(Request $request): array
    {
        $token    = $request->cookie(self::COOKIE_NAME);
        $newToken = false;

        if (!$token) {
            $token    = (string) Str::uuid();
            $newToken = true;
        }

        $carrito = CarritoModel::activo()->where('carToken', $token)->first();

        if (!$carrito) {
            $carrito = CarritoModel::create([
                'carToken'     => $token,
                'carEstado'    => CarritoModel::ESTADO_ACTIVO,
                'carProductos' => [],
                'carTotal'     => 0,
                'usuID'        => null,
            ]);
        }

        if (Auth::check()) {
            $usuID       = Auth::id();
            $tienePerfil = UsuarioModel::where('usuID', $usuID)->exists();

            if ($tienePerfil) {
                $carritoAuth = CarritoModel::activo()
                    ->where('usuID', $usuID)
                    ->where('carToken', '!=', $token)
                    ->first();

                if ($carritoAuth) {
                    foreach ($carrito->carProductos ?? [] as $item) {
                        $carritoAuth->agregarProducto($item);
                    }
                    $carrito->carEstado = CarritoModel::ESTADO_INACTIVO;
                    $carrito->save();
                    $carrito  = $carritoAuth;
                    $token    = $carritoAuth->carToken;
                    $newToken = true;
                }

                if (!$carrito->usuID) {
                    $carrito->usuID = $usuID;
                    $carrito->save();
                }
            }
        }

        return [$carrito, $token, $newToken];
    }

    private function queueCookie(string $token, bool $set): void
    {
        if ($set) {
            Cookie::queue(self::COOKIE_NAME, $token, self::COOKIE_TTL, '/', null, false, true);
        }
    }

    private function calcularCostoEnvio(string $pedEntrega): float
    {
        return match ($pedEntrega) {
            PedidoModel::ENTREGA_ENVIO_INTERNO    => (float) ParametroModel::get('COSTO_ENVIO_INTERNO'),
            PedidoModel::ENTREGA_RETIRO           => (float) ParametroModel::get('COSTO_ENVIO_RETIRO'),
            PedidoModel::ENTREGA_ENVIO_PROVINCIAL => 0.0,
            default                               => 0.0,
        };
    }

    /* ==============================
       FLUJO A — COMPRAR AHORA
    ============================== */

    public function directo(Request $request)
    {
        $request->validate([
            'proID'    => 'required|integer|exists:upbProductos,proID',
            'cantidad' => 'required|integer|min:1',
        ]);

        [$carrito, $token, $newToken] = $this->resolverCarrito($request);

        $producto = ProductoModel::findOrFail($request->proID);

        if ($request->cantidad > $producto->proStock) {
            return back()->withErrors(['cantidad' => 'Stock insuficiente. Disponible: ' . $producto->proStock]);
        }

        $item = [
            'proID'               => $producto->proID,
            'proNombre'           => $producto->proNombre,
            'proSlug'             => $producto->proSlug,
            'proImagen'           => $producto->proImagen,
            'proPrecio'           => $producto->proPrecio,
            'precioPagado'        => $producto->precio_final,
            'tieneDescuento'      => $producto->tiene_descuento,
            'porcentajeDescuento' => (float) ($producto->proPorcentajeDescuento ?? 0),
            'precioTransferencia' => $producto->precio_transferencia,
            'permiteCuotas'       => $producto->permite_cuotas,
            'cantidad'            => $request->cantidad,
            'subtotal'            => $producto->precio_final * $request->cantidad,
        ];

        $carrito->carProductos = [$item];
        $carrito->carTotal     = $item['subtotal'];
        $carrito->save();

        $this->queueCookie($token, $newToken);
        return redirect()->route('checkout.confirmar');
    }

    /* ==============================
       CONFIRMAR PEDIDO
    ============================== */

    public function confirmar(Request $request)
    {
        [$carrito, $token, $newToken] = $this->resolverCarrito($request);

        if (empty($carrito->carProductos)) {
            return redirect()->route('shop');
        }

        $primerItem  = $carrito->carProductos[0];
        $idsEnCarrito = array_column($carrito->carProductos, 'proID');
        $firstProd   = ProductoModel::find($primerItem['proID']);

        $relacionados = $firstProd
            ? ProductoModel::where('catID', $firstProd->catID)
                ->whereNotIn('proID', $idsEnCarrito)
                ->where('proStock', '>', 0)
                ->latest('proID')
                ->limit(4)
                ->get()
            : collect();

        $this->queueCookie($token, $newToken);
        return view('checkout.confirmar', [
            'carrito'      => $carrito,
            'relacionados' => $relacionados,
        ]);
    }

    /* ==============================
       DATOS PERSONALES
    ============================== */

    public function datos(Request $request)
    {
        $costoEnvioInterno = ParametroModel::get('COSTO_ENVIO_INTERNO');
        $previos           = session('checkout_datos', []);

        return view('checkout.datos', compact('costoEnvioInterno', 'previos'));
    }

    public function datosGuardar(Request $request)
    {
        $rules = [
            'celular'      => 'required|string|max:30',
            'pedEntrega'   => 'required|in:EN,RE,EP',
            'envDireccion' => 'nullable|string|max:255',
        ];

        if (!Auth::check()) {
            $rules += [
                'nombre'           => 'required|string|max:100',
                'apellido'         => 'required|string|max:100',
                'email'            => 'required|email|max:255',
                'fecha_nacimiento' => 'nullable|date',
            ];
        }

        if ($request->pedEntrega === PedidoModel::ENTREGA_ENVIO_INTERNO) {
            $rules['envDireccion'] = 'required|string|max:255';
        }

        $validated = $request->validate($rules);

        if (Auth::check()) {
            $perfil   = UsuarioModel::find(Auth::id());
            $validated = array_merge([
                'nombre'   => $perfil?->usuNombre ?? '',
                'apellido' => $perfil?->usuApellido ?? '',
                'email'    => Auth::user()->email,
            ], $validated);
        }

        session(['checkout_datos' => $validated]);

        return redirect()->route('checkout.pago');
    }

    /* ==============================
       SELECCIÓN DE PAGO
    ============================== */

    public function pago(Request $request)
    {
        $datos = session('checkout_datos');
        if (!$datos) return redirect()->route('checkout.datos');

        [$carrito, $token, $newToken] = $this->resolverCarrito($request);
        if (empty($carrito->carProductos)) return redirect()->route('shop');

        $costoEnvio          = $this->calcularCostoEnvio($datos['pedEntrega']);
        $subtotal            = $carrito->carTotal;
        $total               = $subtotal + $costoEnvio;
        $descuento           = (float) ParametroModel::get('DESCUENTO_TRANSFERENCIA');
        $totalTransferencia  = round($total * (1 - $descuento / 100), 2);
        $permiteCuotas       = collect($carrito->carProductos)->contains('permiteCuotas', true);

        $this->queueCookie($token, $newToken);
        return view('checkout.pago', [
            'items'              => $carrito->carProductos,
            'subtotal'           => $subtotal,
            'costoEnvio'         => $costoEnvio,
            'total'              => $total,
            'totalTransferencia' => $totalTransferencia,
            'descuento'          => $descuento,
            'permiteCuotas'      => $permiteCuotas,
            'datos'              => $datos,
        ]);
    }

    public function pagoPost(Request $request)
    {
        $request->validate(['tipo' => 'required|in:MP,TR']);

        if ($request->tipo === PedidoModel::PAGO_MERCADOPAGO) {
            return back()->with('info', 'MercadoPago estará disponible próximamente. Por ahora podés pagar con transferencia.');
        }

        $datos = session('checkout_datos');
        if (!$datos) return redirect()->route('checkout.datos');

        [$carrito, $token, $newToken] = $this->resolverCarrito($request);
        if (empty($carrito->carProductos)) return redirect()->route('shop');

        // 1. Usuario
        $usuarioModel = UsuarioModel::crearDesdeCheckout($datos);

        // 2. Totales
        $costoEnvio         = $this->calcularCostoEnvio($datos['pedEntrega']);
        $total              = $carrito->carTotal + $costoEnvio;
        $totalTransferencia = round($total * (1 - (float) ParametroModel::get('DESCUENTO_TRANSFERENCIA') / 100), 2);

        // 3. Pedido
        $pedido = PedidoModel::create([
            'usuID'                 => $usuarioModel->usuID,
            'pedEstado'             => PedidoModel::ESTADO_PENDIENTE,
            'pedPago'               => PedidoModel::PAGO_TRANSFERENCIA,
            'pedEntrega'            => $datos['pedEntrega'],
            'pedTotal'              => $total,
            'pedTotalTransferencia' => $totalTransferencia,
        ]);

        // 4. Detalles
        foreach ($carrito->carProductos as $item) {
            PedidoDetalleModel::create([
                'pedID'                  => $pedido->pedID,
                'proID'                  => $item['proID'],
                'pdeNombre'              => $item['proNombre'],
                'pdePrecioUnitario'      => $item['proPrecio'],
                'pdePrecioPagado'        => $item['precioPagado'],
                'pdePorcentajeDescuento' => $item['porcentajeDescuento'] ?? null,
                'pdeCantidad'            => $item['cantidad'],
                'pdeSubtotal'            => $item['subtotal'],
            ]);
        }

        // 5. Envío
        $envio = EnvioModel::create([
            'pedID'        => $pedido->pedID,
            'envDireccion' => $datos['envDireccion'] ?? null,
            'envRetiro'    => $datos['pedEntrega'] === PedidoModel::ENTREGA_RETIRO ? 'S' : 'N',
        ]);

        // 6. Operación
        $operacion = OperacionModel::create([
            'usuID'      => $usuarioModel->usuID,
            'pedID'      => $pedido->pedID,
            'opeEstado'  => OperacionModel::ESTADO_PENDIENTE,
            'opeTipo'    => OperacionModel::TIPO_TRANSFERENCIA,
            'opeMonto'   => $totalTransferencia,
            'opeFecAlta' => now(),
        ]);

        // 7. Vincular IDs circulares
        $pedido->opeID = $operacion->opeID;
        $pedido->envID = $envio->envID;
        $pedido->save();

        // 8. Convertir carrito
        $carrito->carEstado = CarritoModel::ESTADO_CONVERTIDO;
        $carrito->save();

        session()->forget('checkout_datos');

        $this->queueCookie($token, $newToken);
        return redirect()->route('checkout.transferencia', $pedido->pedID);
    }

    /* ==============================
       COMPROBANTE DE TRANSFERENCIA
    ============================== */

    public function transferencia(int $id)
    {
        $pedido = PedidoModel::with(['detalles', 'operacion'])->findOrFail($id);

        return view('checkout.transferencia', [
            'pedido'             => $pedido,
            'totalTransferencia' => $pedido->pedTotalTransferencia,
        ]);
    }

    public function transferenciaPost(Request $request, int $id)
    {
        $request->validate([
            'comprobante' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'fecha_pago'  => 'nullable|date',
        ]);

        $pedido    = PedidoModel::with('operacion')->findOrFail($id);
        $archivo   = $request->file('comprobante');
        $nombre    = 'ped_' . $pedido->pedID . '_' . time() . '.' . $archivo->getClientOriginalExtension();
        $ruta      = $archivo->storeAs('comprobantes', $nombre, 'public');

        $comprobante = ComprobanteModel::create([
            'pedID'      => $pedido->pedID,
            'opeID'      => $pedido->operacion->opeID,
            'comRuta'    => $ruta,
            'comFecPago' => $request->fecha_pago ? $request->fecha_pago : now(),
        ]);

        // Actualizar operación
        $pedido->operacion->comID = $comprobante->comID;
        $pedido->operacion->save();

        // Actualizar pedido
        $pedido->comID     = $comprobante->comID;
        $pedido->pedEstado = PedidoModel::ESTADO_PENDIENTE;
        $pedido->save();

        return redirect()->route('checkout.gracias', $pedido->pedID);
    }

    /* ==============================
       PANTALLA DE ÉXITO
    ============================== */

    public function gracias(int $id)
    {
        $pedido = PedidoModel::with(['detalles', 'envio', 'operacion', 'comprobante'])->findOrFail($id);

        return view('checkout.gracias', compact('pedido'));
    }
}
