<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Statistik Dasar
        $totalProducts = Product::count();
        $totalOrders   = Order::count();

        // Pesanan Terbaru (5 terakhir)
        $latestOrders = Order::with(['user', 'product'])
            ->latest()
            ->limit(5)
            ->get();

        // Total Pendapatan Bulan Ini
        $totalRevenue = Order::where('status', 'completed')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('total');

        // Data Grafik Penjualan 30 Hari Terakhir
        $salesData = [];
        $salesLabels = [];

        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);

            $revenue = Order::where('status', 'completed')
                ->whereDate('created_at', $date->format('Y-m-d'))
                ->sum('total');

            $salesData[] = (int) $revenue;                    // Pastikan tipe integer
            $salesLabels[] = $date->format('d M');            // Contoh: 01 Apr, 02 Apr
        }

        return view('admin.dashboard', compact(
            'totalProducts',
            'totalOrders',
            'latestOrders',
            'totalRevenue',
            'salesData',
            'salesLabels'
        ));
    }
}