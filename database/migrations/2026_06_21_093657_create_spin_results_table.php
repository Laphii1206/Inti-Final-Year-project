<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spin_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->unsignedInteger('points_spent');
            $table->enum('reward_type', ['points', 'voucher', 'nothing']);
            $table->integer('reward_points')->default(0);
            $table->foreignId('voucher_id')->nullable()->constrained()->nullOnDelete();
            $table->string('reward_label');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spin_results');
    }
};
