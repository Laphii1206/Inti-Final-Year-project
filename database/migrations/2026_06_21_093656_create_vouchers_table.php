<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('code')->unique();
            $table->enum('type', ['fixed', 'percentage']);
            $table->decimal('value', 8, 2);
            $table->enum('source', ['redeemed_points', 'birthday', 'promo_code', 'spin_win'])->default('promo_code');
            $table->enum('status', ['available', 'used', 'expired'])->default('available');
            $table->unsignedInteger('points_cost')->nullable();
            $table->unsignedInteger('max_uses')->default(1);
            $table->unsignedInteger('current_uses')->default(0);
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('used_at')->nullable();
            $table->foreignId('used_in_booking_id')->nullable()->constrained('bookings')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
