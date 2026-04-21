<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Address;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $addresses = Auth::user()->addresses;
        return view('addresses.index', compact('addresses'));
    }

    public function create()
    {
        return view('addresses.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'label' => 'required|string|max:255',
            'address' => 'required|string',
            'city' => 'nullable|string|max:100',
            'zip' => 'nullable|string|max:20',
            'phone' => 'required|string|max:20',
        ]);

        Auth::user()->addresses()->create($request->all());

        return redirect()->route('addresses.index')->with('success', 'Alamat berhasil disimpan.');
    }

    public function edit(Address $address)
    {
        // Pastikan alamat milik user yang login
        if ($address->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }
        return view('addresses.edit', compact('address'));
    }

    public function update(Request $request, Address $address)
    {
        if ($address->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'label' => 'required|string|max:255',
            'address' => 'required|string',
            'city' => 'nullable|string|max:100',
            'zip' => 'nullable|string|max:20',
            'phone' => 'required|string|max:20',
        ]);

        $address->update($request->all());

        return redirect()->route('addresses.index')->with('success', 'Alamat berhasil diupdate.');
    }

    public function destroy(Address $address)
    {
        if ($address->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $address->delete();

        return back()->with('success', 'Alamat berhasil dihapus.');
    }
}