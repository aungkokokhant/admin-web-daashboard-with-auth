<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reseller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ResellerController extends Controller
{
    public function index()
    {
        $resellers = Reseller::latest()->paginate(15);

        return view('admin.resellers.index', compact('resellers'));
    }

    public function create()
    {
        return view('admin.resellers.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:resellers,email'],
            'password' => ['required', 'string', 'min:6'],
            'is_active' => ['required', 'boolean'],
        ]);

        Reseller::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'is_active' => $data['is_active'],
            'created_by_admin_id' => auth('admin')->id(),
        ]);

        return redirect()
            ->route('admin.resellers.index')
            ->with('success', 'Reseller created successfully.');
    }

    public function edit(Reseller $reseller)
    {
        return view('admin.resellers.edit', compact('reseller'));
    }

    public function update(Request $request, Reseller $reseller)
    {
        $data = $request->validate([
            'name' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:resellers,email,' . $reseller->id],
            'password' => ['nullable', 'string', 'min:6'],
            'is_active' => ['required', 'boolean'],
        ]);

        $reseller->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'is_active' => $data['is_active'],
        ]);

        if (!empty($data['password'])) {
            $reseller->update([
                'password' => Hash::make($data['password']),
            ]);
        }

        return redirect()
            ->route('admin.resellers.index')
            ->with('success', 'Reseller updated successfully.');
    }
}
