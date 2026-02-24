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
        'activated_at',
        'expires_at',
        'available_days',
        'created_by_admin_id',
        'activated_by_reseller_id',
        're_sellable',
    ];

    protected $casts = [
        'voucher_type' => VoucherType::class,
        'status' => VoucherStatus::class,
        'voucher_value' => 'decimal:2',
        'max_discount_amount' => 'decimal:2',
        'activated_at' => 'datetime',
        'expires_at' => 'datetime',
        're_sellable' => 'boolean',
        'available_days' => 'integer',
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

    public function activatedByReseller()
    {
        return $this->belongsTo(Reseller::class, 'activated_by_reseller_id');
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
