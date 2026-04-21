<?php
namespace App\Http\Controllers;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function checkout()
    {
        $cartItems = \App\Models\CartItem::with('product')->where('user_id', Auth::id())->get();
        if($cartItems->isEmpty()) return redirect()->route('home')->with('error','Keranjang kosong');
        return view('cart.checkout', compact('cartItems'));
    }

public function store(Request $request)
{
    $data = $request->validate([
        'customer_name'=>'required|string|max:255',
        'address_id'=>'required|exists:addresses,id',
        'phone'=>'required|string',
        'payment_method'=>'required|string|in:cod,qris',
    ]);

    $cartItems = \App\Models\CartItem::with('product')
        ->where('user_id', Auth::id())
        ->get();

    if($cartItems->isEmpty()){
        return redirect()->route('cart.index')
            ->with('error','Keranjang kosong');
    }

    $address = \App\Models\Address::findOrFail($data['address_id']);

    $firstItem = $cartItems->first();

    $total = 0;

    foreach($cartItems as $item){
        $total += $item->product->price * $item->quantity;
    }

    $shipping = 5000;
    $grandTotal = $total + $shipping;

    $order = Order::create([
        'user_id' => Auth::id(),
        'product_id' => $firstItem->product_id,
        'quantity' => $firstItem->quantity,
        'total' => $grandTotal,
        'customer_name' => $data['customer_name'],
        'address' => $address->address,
        'phone' => $data['phone'],
        'payment_method' => $data['payment_method'],
        'status' => 'pending',
        'is_paid' => false,
    ]);

    \App\Models\CartItem::where('user_id', Auth::id())->delete();

    return redirect()->route('orders.invoice', $order)
        ->with('success','Order berhasil dibuat.');
}

    public function invoice(Order $order)
    {
        if($order->user_id !== auth()->id()) abort(403);
        $order->load('product');
        return view('orders.invoice', compact('order'));
    }

    public function pay(Order $order)
    {
        if($order->user_id !== auth()->id()) abort(403);
        $order->update(['is_paid'=>true, 'status'=>'processing']);
        return redirect()->route('orders.invoice', $order)->with('success','Pembayaran berhasil.');
    }

    public function myOrders()
    {
        $orders = auth()->user()->orders()->with('product')->latest()->get();
        return view('orders.my_orders', compact('orders'));
    }

    public function uploadProof(Request $request, Order $order)
{
    if ($order->user_id !== auth()->id()) {
        abort(403);
    }

    $request->validate([
        'payment_proof' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048'
    ]);

    $path = $request->file('payment_proof')->store('payment_proofs','public');

    $order->update([
        'payment_proof' => $path
    ]);

    return back()->with('success','Bukti pembayaran berhasil dikirim, menunggu verifikasi admin.');
}
}
