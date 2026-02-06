<?php

namespace App\Models;

use App\Enums\PromotionStatus;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    protected $fillable = [
        'title',
        'description',
        'start_date',
        'end_date',
        'status',
        'created_by_admin_id',
    ];

    protected $casts = [
        'status' => PromotionStatus::class,
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /* ================= Relationships ================= */

    public function creator()
    {
        return $this->belongsTo(Admin::class, 'created_by_admin_id');
    }

    public function vouchers()
    {
        return $this->hasMany(GiftVoucher::class);
    }
}
