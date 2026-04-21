<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:5|max:1000',
        ]);

        // Satu user hanya boleh 1 ulasan per produk
        $review = \App\Models\Review::updateOrCreate(
            [
                'user_id'    => auth()->id(),
                'product_id' => $product->id,
            ],
            [
                'rating'  => $request->rating,
                'comment' => $request->comment,
            ]
        );

        return redirect()->back()
                        ->with('success', $review->wasRecentlyCreated 
                            ? 'Ulasan berhasil dikirim!' 
                            : 'Ulasan berhasil diperbarui!');
    }
}
