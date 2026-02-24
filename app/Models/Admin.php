<?php

namespace App\Models;

use App\Enums\AdminStatus;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'status' => AdminStatus::class,
        'last_login_at' => 'datetime',
    ];

    /* ================= Relationships ================= */

    public function shops()
    {
        return $this->hasMany(Shop::class, 'created_by_admin_id');
    }

    public function promotions()
    {
        return $this->hasMany(Promotion::class, 'created_by_admin_id');
    }

    public function createdVouchers()
    {
        return $this->hasMany(GiftVoucher::class, 'created_by_admin_id');
    }

    public function resellers()
    {
        return $this->hasMany(Reseller::class, 'created_by_admin_id');
    }
}
