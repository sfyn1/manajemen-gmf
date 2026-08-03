<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Product;
use App\Models\ProductSale;
use Illuminate\Http\Request;

class ProductSaleController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('name')->paginate(15);
        $allProducts = Product::where('is_active', true)->where('stock', '>', 0)->get();
        return view('admin.products.index', compact('products', 'allProducts'));
    }

    public function create()
    {
        return view('admin.products.form', ['product' => null]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:150'],
            'category'    => ['required', 'in:supplement,equipment,drink,snack,other'],
            'price'       => ['required', 'numeric', 'min:0'],
            'stock'       => ['required', 'integer', 'min:0'],
            'unit'        => ['required', 'string', 'max:20'],
            'description' => ['nullable', 'string'],
            'is_active'   => ['boolean'],
        ]);

        Product::create([...$data, 'is_active' => $request->boolean('is_active', true)]);
        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product)
    {
        return view('admin.products.form', compact('product'));
    }

    public function update(Product $product, Request $request)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:150'],
            'category'    => ['required', 'in:supplement,equipment,drink,snack,other'],
            'price'       => ['required', 'numeric', 'min:0'],
            'stock'       => ['required', 'integer', 'min:0'],
            'unit'        => ['required', 'string', 'max:20'],
            'description' => ['nullable', 'string'],
            'is_active'   => ['boolean'],
        ]);

        $product->update([...$data, 'is_active' => $request->boolean('is_active')]);
        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        if ($product->sales()->exists()) {
            return redirect()->back()->with('error', 'Produk memiliki riwayat penjualan, tidak dapat dihapus.');
        }
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Produk dihapus.');
    }

    public function sell(Request $request)
    {
        $data = $request->validate([
            'product_id'     => ['required', 'exists:products,id'],
            'qty'            => ['required', 'integer', 'min:1'],
            'payment_method' => ['required', 'in:cash,qris'],
        ]);

        $product = Product::findOrFail($data['product_id']);

        if ($product->stock < $data['qty']) {
            return response()->json(['success' => false, 'message' => "Stok tidak cukup. Tersedia: {$product->stock} {$product->unit}."], 422);
        }

        $total = $product->price * $data['qty'];

        ProductSale::create([
            'product_id'     => $product->id,
            'quantity'       => $data['qty'],
            'unit_price'     => $product->price,
            'total_price'    => $total,
            'payment_method' => $data['payment_method'],
            'sold_by'        => auth()->id(),
        ]);

        $product->decrement('stock', $data['qty']);

        return response()->json([
            'success' => true,
            'message' => "Penjualan {$product->name} × {$data['qty']} berhasil. Total: Rp " . number_format($total, 0, ',', '.'),
        ]);
    }

    public function history()
    {
        $sales = ProductSale::with(['product', 'seller'])->latest()->paginate(20);
        return view('admin.products.history', compact('sales'));
    }
}
