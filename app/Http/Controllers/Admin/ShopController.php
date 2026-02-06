<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Enums\ShopStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ShopController extends Controller
{
    public function index()
    {
        $shops = Shop::latest()->paginate(10);

        return view('admin.shops.index', compact('shops'));
    }

    public function create()
    {
        return view('admin.shops.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'shop_code' => ['required', 'string', 'max:50', 'unique:shops,shop_code'],
            'shop_name' => ['required', 'string', 'max:255'],
            'phone'     => ['required', 'string', 'max:20'],
            'password'  => ['required', 'string', 'min:6'],
            'status'    => ['required'],
        ]);

        Shop::create([
            'shop_code' => $data['shop_code'],
            'shop_name' => $data['shop_name'],
            'phone'     => $data['phone'],
            'password'  => Hash::make($data['password']),
            'status'    => ShopStatus::from($data['status']),
            'created_by_admin_id' => auth('admin')->id(),
        ]);

        return redirect()
            ->route('admin.shops.index')
            ->with('success', 'Shop created successfully.');
    }

    public function edit(Shop $shop)
    {
        return view('admin.shops.edit', compact('shop'));
    }

    public function update(Request $request, Shop $shop)
    {
        $data = $request->validate([
            'shop_code' => ['required', 'string', 'max:50', 'unique:shops,shop_code,' . $shop->id],
            'shop_name' => ['required', 'string', 'max:255'],
            'phone'     => ['required', 'string', 'max:20'],
            'status'    => ['required'],
        ]);

        $shop->update([
            'shop_code' => $data['shop_code'],
            'shop_name' => $data['shop_name'],
            'phone'     => $data['phone'],
            'status'    => ShopStatus::from($data['status']),
        ]);

        return redirect()
            ->route('admin.shops.index')
            ->with('success', 'Shop updated successfully.');
    }

    public function updatePassword(Request $request, Shop $shop)
    {
        $request->validate([
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $shop->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()
            ->route('admin.shops.edit', $shop)
            ->with('success', 'Password updated successfully.');
    }
}
