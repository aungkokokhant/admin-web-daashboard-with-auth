<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VoucherRedemption extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'voucher_id',
        'shop_id',
        'original_amount',
        'discount_amount',
        'final_amount',
        'redeemed_at',
        'transaction_ref',
        'payout_status',
        'payout_confirmed_at',
    ];

    protected $casts = [
        'original_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'final_amount' => 'decimal:2',
        'redeemed_at' => 'datetime',
        'payout_status' => 'boolean',
        'payout_confirmed_at' => 'datetime',
    ];

    /* ================= Relationships ================= */

    public function voucher()
    {
        return $this->belongsTo(GiftVoucher::class, 'voucher_id');
    }


    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }
}
