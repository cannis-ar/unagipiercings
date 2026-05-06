<?php

namespace App\Http\Controllers;

use App\Models\eCommerce\CarritoModel;
use App\Models\Productos\ProductoModel;
use App\Models\Usuario\UsuarioModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CarritoController extends Controller
{
    private const COOKIE_NAME = 'carrito_token';
    private const COOKIE_TTL  = 60 * 24 * 365; // 1 año en minutos

    /* ---- Resolución de carrito por token + auth ---- */

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
            $usuID = Auth::id();

            // Verificar que el usuario tenga perfil antes de asignar el FK
            $tienePerfil = UsuarioModel::where('usuID', $usuID)->exists();

            if ($tienePerfil) {
                // Si hay otro carrito activo autenticado, mergear
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

    /* ---- Formato de respuesta estándar ---- */

    private function respuesta(CarritoModel $carrito): array
    {
        $items    = $carrito->carProductos ?? [];
        $cantidad = (int) array_sum(array_column($items, 'cantidad'));

        return [
            'ok'      => true,
            'carrito' => [
                'items'    => $items,
                'total'    => $carrito->carTotal,
                'cantidad' => $cantidad,
            ],
        ];
    }

    private function withToken(JsonResponse $response, string $token, bool $set): JsonResponse
    {
        if ($set) {
            $response->withCookie(
                cookie(self::COOKIE_NAME, $token, self::COOKIE_TTL, '/', null, false, true)
            );
        }
        return $response;
    }

    /* ---- Endpoints ---- */

    public function index(Request $request): JsonResponse
    {
        [$carrito, $token, $newToken] = $this->resolverCarrito($request);
        return $this->withToken(response()->json($this->respuesta($carrito)), $token, $newToken);
    }

    public function agregar(Request $request): JsonResponse
    {
        $request->validate([
            'proID'    => 'required|integer|exists:upbProductos,proID',
            'cantidad' => 'required|integer|min:1',
        ]);

        [$carrito, $token, $newToken] = $this->resolverCarrito($request);

        $producto = ProductoModel::findOrFail($request->proID);

        $itemActual     = collect($carrito->carProductos ?? [])->firstWhere('proID', $producto->proID);
        $cantidadActual = $itemActual ? $itemActual['cantidad'] : 0;

        if ($cantidadActual + $request->cantidad > $producto->proStock) {
            return response()->json([
                'ok'      => false,
                'mensaje' => 'Stock insuficiente. Disponible: ' . max(0, $producto->proStock - $cantidadActual),
            ], 422);
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

        $carrito->agregarProducto($item);

        return $this->withToken(response()->json($this->respuesta($carrito)), $token, $newToken);
    }

    public function quitar(Request $request): JsonResponse
    {
        $request->validate(['proID' => 'required|integer']);

        [$carrito, $token, $newToken] = $this->resolverCarrito($request);
        $carrito->quitarProducto((int) $request->proID);

        return $this->withToken(response()->json($this->respuesta($carrito)), $token, $newToken);
    }

    public function actualizar(Request $request): JsonResponse
    {
        $request->validate([
            'proID'    => 'required|integer',
            'cantidad' => 'required|integer|min:1',
        ]);

        [$carrito, $token, $newToken] = $this->resolverCarrito($request);

        $producto = ProductoModel::find($request->proID);
        if ($producto && $request->cantidad > $producto->proStock) {
            return response()->json([
                'ok'      => false,
                'mensaje' => 'Stock insuficiente. Disponible: ' . $producto->proStock,
            ], 422);
        }

        $carrito->actualizarCantidad((int) $request->proID, (int) $request->cantidad);

        return $this->withToken(response()->json($this->respuesta($carrito)), $token, $newToken);
    }

    public function vaciar(Request $request): JsonResponse
    {
        [$carrito, $token, $newToken] = $this->resolverCarrito($request);

        $carrito->carProductos = [];
        $carrito->carTotal     = 0;
        $carrito->save();

        return $this->withToken(response()->json($this->respuesta($carrito)), $token, $newToken);
    }
}
