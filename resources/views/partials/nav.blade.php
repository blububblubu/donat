<nav class="flex justify-between items-center mb-4 bg-gray-100 p-4 rounded">
    <div>
        <a href="{{ route('home') }}" class="font-bold text-xl">E-Commerce</a>
    </div>
    <div class="space-x-4">
        @auth
            @if(auth()->user()->is_admin)
                <a href="{{ route('admin.dashboard') }}" class="text-blue-500">Dashboard Admin</a>
                <a href="{{ route('admin.products.index') }}" class="text-blue-500">Produk</a>
                <a href="{{ route('admin.orders.index') }}" class="text-blue-500">Pesanan</a>
            @else
                <a href="{{ route('cart.index') }}" class="text-blue-500">Keranjang</a>
            @endif
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="text-red-500">Logout</button>
            </form>
        @else
            <a href="{{ route('login') }}" class="text-blue-500">Login</a>
            <a href="{{ route('register') }}" class="text-blue-500">Register</a>
        @endauth
    </div>
</nav>
