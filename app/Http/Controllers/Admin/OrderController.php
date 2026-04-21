<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('product','user')->latest()->get();
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load('product','user');
        return view('admin.orders.show', compact('order')); // ✅ FIX: detail → show
    }

    public function create()
    {
        $products = Product::all();
        $users = User::all();
        return view('admin.orders.create', compact('products','users'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id'        => 'required|exists:users,id',
            'product_id'     => 'required|exists:products,id',
            'quantity'       => 'required|integer|min:1',
            'customer_name'  => 'required|string|max:255',
            'address'        => 'required|string',
            'phone'          => 'required|string',
            'payment_method'=> 'required|in:cod,bank_transfer,e_wallet',
        ]);

        $product = Product::findOrFail($data['product_id']);
        $total = $product->price * $data['quantity'];

        Order::create([
            'user_id'        => $data['user_id'],
            'product_id'     => $data['product_id'],
            'quantity'       => $data['quantity'],
            'total'          => $total,
            'customer_name' => $data['customer_name'],
            'address'        => $data['address'],
            'phone'          => $data['phone'],
            'payment_method'=> $data['payment_method'],
            'status'         => 'pending',
            'is_paid'        => $data['payment_method'] === 'cod' ? false : true,
        ]);

        return redirect()->route('admin.orders.index')
            ->with('success','Order baru berhasil dibuat.');
    }

public function updateStatus(Request $request, Order $order)
{
    $request->validate([
        'status' => 'required|in:pending,processing,completed,cancelled'
    ]);

    $newStatus = $request->status;

    if ($newStatus === 'processing') {
        $order->update([
            'status' => 'processing',
            'is_paid' => true
        ]);
    } else {
        $order->update([
            'status' => $newStatus
        ]);
    }

    if ($newStatus === 'completed') {

        $product = $order->product;

        if ($product->stock < $order->quantity) {
            return back()->with('error','Stok tidak mencukupi');
        }

        $product->decrement('stock',$order->quantity);
    }

    return back()->with('success','Status pesanan diperbarui.');
}

public function approvePayment($id)
{
    $order = \App\Models\Order::findOrFail($id);

    $order->is_paid = true;
    $order->status = 'processing';
    $order->save();

    return redirect()
        ->back()
        ->with('success','Pembayaran berhasil diverifikasi');
}

}
