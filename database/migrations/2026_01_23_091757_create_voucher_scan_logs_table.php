<?php

use App\Enums\VoucherScanStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('voucher_scan_logs', function (Blueprint $table) {
            $table->id();

            $table->string('voucher_code');
            $table->foreignId('shop_id')
                ->nullable()
                ->constrained('shops')
                ->nullOnDelete();

            $table->enum('scan_status', VoucherScanStatus::values())
                ->default(VoucherScanStatus::INVALID->value);

            $table->timestamp('scanned_at')->useCurrent();
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();

            $table->index('voucher_code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('voucher_scan_logs');
    }
};
