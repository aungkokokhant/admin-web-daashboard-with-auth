<?php

namespace App\Http\Controllers\Api\V1\Shop;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Support\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Throwable;

class AuthController extends Controller
{
    /**
     * Shop login (API)
     */
    public function login(Request $request)
    {
        try {

            $data = $request->validate([
                'shop_code' => ['required', 'string'],
                'password'  => ['required', 'string'],
            ]);

            $shop = Shop::where('shop_code', $data['shop_code'])->first();

            if (! $shop || ! Hash::check($data['password'], $shop->password)) {
                return ApiResponse::error(
                    'Invalid shop credentials',
                    401
                );
            }

            // Single-device policy (optional)
            // $shop->tokens()->delete();

            $token = $shop->createToken('shop-api-token')->plainTextToken;

            return ApiResponse::success(
                'Login successful',
                [
                    'token' => $token,
                    'shop'  => [
                        'id'   => $shop->id,
                        'name' => $shop->shop_name,
                        'code' => $shop->shop_code,
                    ],
                ]
            );
        } catch (ValidationException $e) {

            // Get first validation error message
            $firstError = collect($e->errors())->flatten()->first();

            return ApiResponse::error(
                $firstError ?? 'Validation error',
                422
            );
        } catch (Throwable $e) {

            // log($e) later if needed

            return ApiResponse::error(
                'Internal server error',
                500
            );
        }
    }


    public function me(Request $request)
    {

        try {
            $shop = $request->user('shop');

            if (!$shop) {
                return ApiResponse::error('Unauthenticated', 401);
            }

            return ApiResponse::success(
                'Shop details retrieved successfully',
                [
                    'shop' => [
                        'id'   => $shop->id,
                        'name' => $shop->shop_name,
                        'code' => $shop->shop_code,
                    ],
                ]
            );
        } catch (Throwable $e) {

            return ApiResponse::error(
                'Unable to get my data',
                500
            );
        }
    }

    public function changePassword(Request $request)
    {
        try {
            $data = $request->validate([
                'current_password'      => ['required', 'string'],
                'password'          => ['required', 'string', 'min:8'],
                'password_confirmation'          => ['required', 'string', 'min:8', 'same:password'],
            ]);

            $shop = $request->user('shop');

            if (!Hash::check($data['current_password'], $shop->password)) {
                return ApiResponse::error('Current password is incorrect', 403);
            }

            $shop->password = Hash::make($data['password']);
            $shop->save();

            return ApiResponse::success('Password changed successfully');
        } catch (ValidationException $e) {

            // Get first validation error message
            $firstError = collect($e->errors())->flatten()->first();

            return ApiResponse::error(
                $firstError ?? 'Validation error',
                422
            );
        } catch (Throwable $e) {

            return ApiResponse::error(
                'Unable to change password',
                500
            );
        }
    }

    /**
     * Shop logout (API)
     */
    public function logout(Request $request)
    {
        try {

            $request->user('shop')->currentAccessToken()->delete();

            return ApiResponse::success(
                'Logged out successfully'
            );
        } catch (Throwable $e) {

            return ApiResponse::error(
                'Unable to logout',
                500
            );
        }
    }
}
