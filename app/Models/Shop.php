<?php

namespace App\Models;

use App\Enums\ShopStatus;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Laravel\Sanctum\HasApiTokens;

class Shop extends Authenticatable
{
    use Notifiable;
    use HasApiTokens;

    protected $fillable = [
        'shop_code',
        'shop_name',
        'phone',
        'password',
        'status',
        'created_by_admin_id',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'status' => ShopStatus::class,
    ];

    /* ================= Relationships ================= */

    public function creator()
    {
        return $this->belongsTo(Admin::class, 'created_by_admin_id');
    }

    public function assignedVouchers()
    {
        return $this->hasMany(GiftVoucher::class, 'assigned_shop_id');
    }

    public function voucherRedemptions()
    {
        return $this->hasMany(VoucherRedemption::class, 'shop_id');
    }
}
