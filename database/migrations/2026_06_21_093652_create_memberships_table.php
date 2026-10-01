<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('memberships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('membership_id')->unique();
            $table->enum('tier', ['bronze', 'silver', 'gold'])->default('bronze');
            $table->unsignedBigInteger('reward_points')->default(0);
            $table->decimal('cumulative_annual_spending', 10, 2)->default(0);
            $table->date('membership_join_date');
            $table->timestamp('last_tier_evaluated_at')->nullable();
            $table->string('referral_code')->unique()->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('memberships');
    }
};
