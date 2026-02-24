<?php

namespace App\Http\Controllers\Reseller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResellerAuthController extends Controller
{
    /**
     * Show reseller login page
     */
    public function showLogin()
    {
        return view('reseller.auth.login');
    }

    /**
     * Handle reseller login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::guard('reseller')->attempt([
            'email' => $credentials['email'],
            'password' => $credentials['password'],
            'is_active' => true,   // ✅ Only active resellers
        ])) {

            $request->session()->regenerate();

            /** @var \App\Models\Reseller $reseller */
            $reseller = Auth::guard('reseller')->user();

            $reseller->last_login_at = now();
            $reseller->save();

            return redirect()->intended('/reseller/resell-vouchers');
        }

        return back()->withErrors([
            'email' => 'Invalid credentials or account inactive.',
        ])->onlyInput('email');
    }

    /**
     * Reseller logout
     */
    public function logout(Request $request)
    {
        Auth::guard('reseller')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/reseller/login');
    }
}
