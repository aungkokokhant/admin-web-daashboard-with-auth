<?php

use App\Enums\VoucherStatus;
use App\Enums\VoucherType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('gift_vouchers', function (Blueprint $table) {
            $table->id();

            $table->string('voucher_code')->unique();
            $table->string('qr_payload')->unique();

            $table->enum('voucher_type', VoucherType::values())
                ->default(VoucherType::FIXED->value);
            $table->decimal('voucher_value', 10, 2);
            $table->decimal('max_discount_amount', 10, 2)->nullable();

            $table->foreignId('promotion_id')
                ->nullable()
                ->constrained('promotions')
                ->nullOnDelete();

            $table->enum('status', VoucherStatus::values())->default(VoucherStatus::UNUSED->value);

            $table->foreignId('assigned_shop_id')
                ->nullable()
                ->constrained('shops')
                ->nullOnDelete();

            $table->timestamp('expires_at')->nullable();

            $table->foreignId('created_by_admin_id')
                ->constrained('admins')
                ->cascadeOnDelete();

            $table->timestamps();

            $table->index(['voucher_code', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gift_vouchers');
    }
};
