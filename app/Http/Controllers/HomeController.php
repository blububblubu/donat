<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Ambil semua produk untuk ditampilkan di grid
        $products = Product::latest()->get();

        // Ambil produk paling populer berdasarkan jumlah order
        $popularOrder = Order::select('product_id', \DB::raw('SUM(quantity) as total_sold'))
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->first();

        // Jika ada order, ambil produknya
        if ($popularOrder) {
            $popularProduct = Product::find($popularOrder->product_id);
        } else {
            // Jika belum ada order sama sekali
            $popularProduct = Product::first();
        }

        return view('home', compact('products', 'popularProduct'));
    }


    public function show(Product $product)
    {
        // Load relasi review dan user
        $product->load('reviews.user');

        $userCanReview = false;

        if (auth()->check()) {
            $user = auth()->user();

            // Cek apakah user sudah membeli dan membayar produk
            $hasBought = $user->orders()
                ->where('product_id', $product->id)
                ->where('is_paid', true)
                ->exists();

            if ($hasBought) {
                // Cek apakah sudah pernah review
                $alreadyReviewed = $product->reviews->contains('user_id', $user->id);
                $userCanReview = !$alreadyReviewed;
            }
        }

        return view('product.show', compact('product', 'userCanReview'));
    }


    // =============================
    // REALTIME SEARCH PRODUCT
    // =============================
public function search(Request $request)
{
    if ($request->has('recommend')) {
        $products = Product::latest()->take(8)->get();   // atau where('stock', '>', 0)
    } else {
        $query = $request->get('q');
        $products = Product::where('name', 'LIKE', "%{$query}%")
                           ->orWhere('description', 'LIKE', "%{$query}%")
                           ->take(10)
                           ->get();
    }

    return response()->json($products);
}
}