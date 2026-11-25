<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\WebpEncoder;
use Intervention\Image\ImageManager;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::search($request->search)
            ->orderBy('name')
            ->paginate(8)
            ->withQueryString();

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        return view('admin.products.create');
    }

    public function store(Request $request)
    {
        // Limpia los puntos de los precios antes de validar
        $request->merge([
            'price' => str_replace('.', '', $request->price),
            'discount_price' => $request->discount_price ? str_replace('.', '', $request->discount_price) : null,
        ]);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'brand' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|integer|min:0',
            'discount_price' => 'nullable|integer|min:0|lt:price',
            'stock' => 'required|integer|min:0',
            'active' => 'boolean',
            'featured' => 'boolean',
            'image_file' => 'nullable|image|max:5000', // max 5MB
        ], [
            'name.required' => 'El nombre del producto es obligatorio.',
            'price.required' => 'El precio es obligatorio.',
            'discount_price.lt' => 'El precio con descuento debe ser menor al precio normal.',
            'stock.required' => 'El stock es obligatorio.',
            'image_file.image' => 'El archivo debe ser una imagen válida.',
            'image_file.max' => 'La imagen no debe superar los 5MB.',
        ]);

        if ($this->hasDuplicateProduct($data['name'], $data['brand'] ?? null)) {
            return back()
                ->withInput()
                ->with('error', 'Ya existe un producto registrado con ese mismo nombre y marca.');
        }

        if ($request->hasFile('image_file')) {
            $data['image'] = $this->storeWebp($request->file('image_file'));
        }

        $data['active'] = $request->has('active');
        $data['featured'] = $request->has('featured');

        Product::create($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Producto creado correctamente.');
    }

    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $request->merge([
            'price' => str_replace('.', '', $request->price),
            'discount_price' => $request->discount_price ? str_replace('.', '', $request->discount_price) : null,
        ]);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'brand' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|integer|min:0',
            'discount_price' => 'nullable|integer|min:0|lt:price',
            'stock' => 'required|integer|min:0',
            'active' => 'boolean',
            'featured' => 'boolean',
            'image_file' => 'nullable|image|max:5000',
        ], [
            'name.required' => 'El nombre del producto es obligatorio.',
            'price.required' => 'El precio es obligatorio.',
            'discount_price.lt' => 'El precio con descuento debe ser menor al precio normal.',
            'stock.required' => 'El stock es obligatorio.',
            'image_file.image' => 'El archivo debe ser una imagen válida.',
            'image_file.max' => 'La imagen no debe superar los 5MB.',
        ]);

        if ($this->hasDuplicateProduct($data['name'], $data['brand'] ?? null, $product->id)) {
            return back()
                ->withInput()
                ->with('error', 'Ya existe otro producto con ese mismo nombre y marca.');
        }

        if ($request->hasFile('image_file')) {
            if ($product->image && !Str::startsWith($product->image, ['http://', 'https://', '/storage/'])) {
                try {
                    if (Storage::disk('s3')->exists($product->image)) {
                        Storage::disk('s3')->delete($product->image);
                    }
                } catch (\Throwable $e) {
                    report($e);
                }
            }
            $data['image'] = $this->storeWebp($request->file('image_file'));
        }

        $data['active'] = $request->has('active');
        $data['featured'] = $request->has('featured');

        $product->update($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Producto actualizado correctamente.');
    }
    
    public function destroy(Product $product)
    {
        if ($product->image && !Str::startsWith($product->image, ['http://', 'https://', '/storage/']) && Storage::disk('s3')->exists($product->image)) {
            Storage::disk('s3')->delete($product->image);
        }
        $product->delete();
        return redirect()->route('admin.products.index')
            ->with('success', 'Producto eliminado correctamente.');
    }

    // Verifica si ya existe un producto con el mismo nombre y marca (sin distinguir mayúsculas/espacios)
    private function hasDuplicateProduct(string $name, ?string $brand, ?int $ignoreId = null): bool
    {
        $normalizedName = mb_strtolower(trim((string) $name), 'UTF-8');
        $normalizedBrand = mb_strtolower(trim((string) ($brand ?? '')), 'UTF-8');

        return Product::query()
            ->when($ignoreId !== null, fn($q) => $q->where('id', '!=', $ignoreId))
            ->whereRaw('LOWER(TRIM(COALESCE(name, \'\'))) = ?', [$normalizedName])
            ->whereRaw('LOWER(TRIM(COALESCE(brand, \'\'))) = ?', [$normalizedBrand])
            ->exists();
    }

    // Método privado para convertir y guardar la imagen como WebP en el bucket (Supabase vía S3)
    private function storeWebp($file): string
    {
        $filename = Str::uuid() . '.webp';
        $path = 'products/' . $filename;

        $manager = new ImageManager(new Driver());
        $encoded = $manager->read($file->getRealPath())
            ->cover(600, 600) // Redimensiona a 600x600 (cuadrado)
            ->encode(new WebpEncoder(quality: 80));

        Storage::disk('s3')->put($path, (string) $encoded);

        return $path;
    }
}
