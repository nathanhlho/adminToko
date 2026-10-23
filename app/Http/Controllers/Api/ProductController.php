<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    // GET /api/products?search=laptop&category_id=1&min_price=1000&max_price=99999999&sort=price&order=asc
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        $sort = in_array($request->sort, ['name', 'price', 'stock', 'created_at'])
            ? $request->sort : 'created_at';
        $order = $request->order === 'asc' ? 'asc' : 'desc';

        return ProductResource::collection($query->orderBy($sort, $order)->paginate(10));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,category_id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'img' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $path = null;
        if ($request->hasFile('img')) {
            $path = $request->file('img')->store('products', 'public');
        }

        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'price' => $request->price,
            'stock' => $request->stock,
            'img' => $path,
            'slug' => Str::slug($request->name) . '-' . Str::random(5),
        ]);

        return (new ProductResource($product->load('category')))
            ->response()->setStatusCode(201);
    }

    public function show(string $id)
    {
        return new ProductResource(Product::with('category')->findOrFail($id));
    }

    public function update(Request $request, string $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:150',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,category_id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'img' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $path = $product->img;
        if ($request->hasFile('img')) {
            if ($product->img) {
                Storage::disk('public')->delete($product->img);
            }
            $path = $request->file('img')->store('products', 'public');
        }

        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'price' => $request->price,
            'stock' => $request->stock,
            'img' => $path,
            'slug' => Str::slug($request->name) . '-' . $product->product_id,
        ]);

        return new ProductResource($product->load('category'));
    }

    public function destroy(string $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        if ($product->img) {
            Storage::disk('public')->delete($product->img);
        }
        $product->delete();

        return response()->json(['message' => 'Product deleted successfully']);
    }
}