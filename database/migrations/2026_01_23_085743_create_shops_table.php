<?php

use App\Enums\ShopStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('shops', function (Blueprint $table) {
            $table->id();
            $table->string('shop_code')->unique();
            $table->string('shop_name');
            $table->string('phone')->unique();
            $table->string('password');
            $table->enum('status', ShopStatus::values())->default(ShopStatus::ACTIVE->value);
            $table->foreignId('created_by_admin_id')
                ->nullable()
                ->constrained('admins')
                ->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shops');
    }
};
