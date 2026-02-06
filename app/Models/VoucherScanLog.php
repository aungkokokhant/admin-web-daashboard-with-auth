<?php

namespace App\Models;

use App\Enums\VoucherScanStatus;
use Illuminate\Database\Eloquent\Model;

class VoucherScanLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'voucher_code',
        'shop_id',
        'scan_status',
        'scanned_at',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'scan_status' => VoucherScanStatus::class,
        'scanned_at' => 'datetime',
    ];

    /* ================= Relationships ================= */

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }
}
