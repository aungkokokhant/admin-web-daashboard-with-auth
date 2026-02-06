<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('voucher_assignments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('voucher_id')
                ->constrained('gift_vouchers')
                ->cascadeOnDelete();

            $table->foreignId('shop_id')
                ->constrained('shops')
                ->cascadeOnDelete();

            $table->foreignId('assigned_by_admin_id')
                ->constrained('admins')
                ->cascadeOnDelete();

            $table->timestamp('assigned_at')->useCurrent();

            $table->unique(['voucher_id', 'shop_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('voucher_assignments');
    }
};
