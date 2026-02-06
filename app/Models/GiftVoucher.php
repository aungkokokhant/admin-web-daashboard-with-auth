<?php

namespace App\Models;

use App\Enums\VoucherStatus;
use App\Enums\VoucherType;
use Illuminate\Database\Eloquent\Model;

class GiftVoucher extends Model
{
    protected $fillable = [
        'voucher_code',
        'qr_payload',
        'voucher_type',
        'voucher_value',
        'max_discount_amount',
        'promotion_id',
        'status',
        'assigned_shop_id',
        'expires_at',
        'created_by_admin_id',
    ];

    protected $casts = [
        'voucher_type' => VoucherType::class,
        'status' => VoucherStatus::class,
        'voucher_value' => 'decimal:2',
        'max_discount_amount' => 'decimal:2',
        'expires_at' => 'datetime',
    ];

    /* ================= Relationships ================= */

    public function promotion()
    {
        return $this->belongsTo(Promotion::class);
    }

    public function assignedShop()
    {
        return $this->belongsTo(Shop::class, 'assigned_shop_id');
    }

    public function creator()
    {
        return $this->belongsTo(Admin::class, 'created_by_admin_id');
    }

    public function assignment()
    {
        return $this->hasOne(VoucherAssignment::class, 'voucher_id');
    }

    public function redemption()
    {
        return $this->hasOne(VoucherRedemption::class, 'voucher_id');
    }
}
