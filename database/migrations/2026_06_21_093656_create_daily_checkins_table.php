<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_checkins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('checked_in_date');
            $table->unsignedInteger('streak_count')->default(1);
            $table->unsignedInteger('points_awarded');
            $table->boolean('streak_bonus_awarded')->default(false);
            $table->timestamps();

            $table->unique(['user_id', 'checked_in_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_checkins');
    }
};
