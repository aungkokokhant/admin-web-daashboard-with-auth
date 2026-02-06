<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VoucherAssignment extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'voucher_id',
        'shop_id',
        'assigned_by_admin_id',
        'assigned_at',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
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

    public function assignedBy()
    {
        return $this->belongsTo(Admin::class, 'assigned_by_admin_id');
    }
}
