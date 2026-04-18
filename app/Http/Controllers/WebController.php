<?php

namespace App\Http\Controllers;

use App\Models\Productos\CategoriaModel;
use App\Models\Productos\MaterialModel;
use App\Models\Productos\ProductoModel;
use Illuminate\Http\Request;

class WebController extends Controller
{
    public function index()
    {
        $categorias = CategoriaModel::orderBy('catNombre')->get();

        $productosDestacados = ProductoModel::with(['categoria', 'material'])
            ->orderByDesc('proID')
            ->limit(4)
            ->get();

        $productosNuevos = ProductoModel::with(['categoria', 'material'])
            ->orderByDesc('created_at')
            ->limit(8)
            ->get();

        return view('home.index', [
            'categorias'          => $categorias,
            'productosDestacados' => $productosDestacados,
            'productosNuevos'     => $productosNuevos,
        ]);
    }

    public function shop(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Filtros recibidos por query
        |--------------------------------------------------------------------------
        */
        $categoriaID = $request->query('categoria');
        $materialID  = $request->query('material');

        /*
        |--------------------------------------------------------------------------
        | Datos para navegación / filtros
        |--------------------------------------------------------------------------
        */
        $categorias = CategoriaModel::orderBy('catNombre')->get();

        $materiales = MaterialModel::orderBy('matNombre')->get();

        /*
        |--------------------------------------------------------------------------
        | Query base de productos
        |--------------------------------------------------------------------------
        */
        $productosQuery = ProductoModel::with(['categoria', 'material']);

        /*
        |--------------------------------------------------------------------------
        | Filtro por categoría
        |--------------------------------------------------------------------------
        */
        if ($categoriaID) {
            $productosQuery->where('catID', $categoriaID);
        }

        /*
        |--------------------------------------------------------------------------
        | Filtro por material
        |--------------------------------------------------------------------------
        */
        if ($materialID) {
            $productosQuery->where('matID', $materialID);
        }

        /*
        |--------------------------------------------------------------------------
        | Productos paginados
        |--------------------------------------------------------------------------
        */
        $productos = $productosQuery
            ->orderByDesc('created_at')
            ->paginate(12)
            ->withQueryString(); // mantiene filtros al paginar

        /*
        |--------------------------------------------------------------------------
        | Render de vista
        |--------------------------------------------------------------------------
        */
        return view('shop.index', [
            // navegación
            'categorias' => $categorias,
            'materiales' => $materiales,

            // productos
            'productos'  => $productos,

            // estado de filtros (útil para UI activa)
            'categoriaSeleccionada' => $categoriaID,
            'materialSeleccionado'  => $materialID,
        ]);
    }

    public function show(string $slug)
    {
        $producto = ProductoModel::with(['categoria', 'subcategoria', 'material'])
            ->where('proSlug', $slug)
            ->firstOrFail();

        $relacionados = ProductoModel::with(['categoria', 'material'])
            ->where('catID', $producto->catID)
            ->where('proID', '!=', $producto->proID)
            ->limit(4)
            ->get();

        return view('products.show', compact('producto', 'relacionados'));
    }
}
