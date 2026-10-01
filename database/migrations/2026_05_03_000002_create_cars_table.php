<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cars', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->string('brand');
        $table->string('model');
        $table->year('year');
        $table->string('car_plate')->unique();
        $table->integer('mileage')->default(0);
        $table->boolean('is_default')->default(false);
        $table->timestamps();
        $table->timestamp('deleted_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
