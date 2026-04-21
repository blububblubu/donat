<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Address;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = CartItem::with('product')->where('user_id', Auth::id())->get();
        return view('cart.index', compact('cartItems'));
    }

    public function add(Product $product, Request $request)
    {
        $quantity = $request->input('quantity', 1);

        if ($product->stock < $quantity) {
            return back()->with('error','Stok tidak mencukupi');
        }

        $cartItem = CartItem::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {

            if (($cartItem->quantity + $quantity) > $product->stock) {
                return back()->with('error','Stok tidak mencukupi');
            }

            $cartItem->increment('quantity', $quantity);

        } else {

            CartItem::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'quantity' => $quantity
            ]);

        }

        return back()->with('success','Produk ditambahkan ke keranjang');
    }

    public function update(CartItem $cartItem, Request $request)
    {
        if ($cartItem->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        if ($cartItem->product->stock < $request->quantity) {
            return back()->with('error', 'Stok produk tidak mencukupi!');
        }

        $cartItem->update([
            'quantity' => $request->quantity
        ]);

        return back()->with('success', 'Jumlah produk diperbarui');
    }

    public function remove(CartItem $cartItem)
    {
        if ($cartItem->user_id !== Auth::id()) {
            abort(403);
        }

        $cartItem->delete();
        return back()->with('success','Produk dihapus dari keranjang');
    }

    public function clear()
    {
        CartItem::where('user_id', Auth::id())->delete();
        return back()->with('success','Keranjang dikosongkan');
    }

    public function checkout()
    {
        $cartItems = CartItem::with('product')->where('user_id', Auth::id())->get();

        if($cartItems->isEmpty()){
            return redirect()->route('home')->with('error','Keranjang kosong');
        }

        $addresses = Auth::user()->addresses()->latest()->get();

        return view('cart.checkout', compact('cartItems','addresses'));
    }

    public function processCheckout(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'address_id'    => 'nullable|exists:addresses,id',
            'new_address'   => 'nullable|string',
            'phone'         => 'required|string',
            'payment_method' => 'required|in:cod,qris'
        ]);

        $cartItems = CartItem::with('product')->where('user_id', Auth::id())->get();

        if($cartItems->isEmpty()) {
            return back()->with('error','Keranjang kosong');
        }

        // ongkir flat
        $shippingCost = 5000;
        $isFirstItem = true;

        if ($request->address_id) {

            $address = Address::findOrFail($request->address_id);

            $finalAddress = $address->address;
            $finalLabel = $address->label;
            $finalPhone = $address->phone;

        } else {

            $finalAddress = $request->new_address;
            $finalLabel = $request->customer_name;
            $finalPhone = $request->phone;

            Address::create([
                'user_id' => Auth::id(),
                'label'   => $finalLabel,
                'address' => $finalAddress,
                'phone'   => $finalPhone
            ]);
        }

        foreach($cartItems as $item){

            if ($item->product->stock < $item->quantity) {
                return back()->with('error', 'Stok produk '.$item->product->name.' tidak mencukupi!');
            }

            $total = $item->product->price * $item->quantity;

            if ($isFirstItem) {
                $total += $shippingCost;
                $isFirstItem = false;
            }

            Order::create([
                'user_id'       => Auth::id(),
                'product_id'    => $item->product_id,
                'quantity'      => $item->quantity,
                'total'         => $total,
                'customer_name' => $finalLabel,
                'address'       => $finalAddress,
                'phone'         => $finalPhone,
                'payment_method'=> $request->payment_method,
                'status'        => 'pending',
                'is_paid' => false,
            ]);

            // kurangi stok produk
            $item->product->decrement('stock', $item->quantity);
        }

        CartItem::where('user_id', Auth::id())->delete();

        return redirect()->route('orders.my')->with('success','Checkout berhasil!');
    }
}