<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();

            $table->foreignId('branch_id')->nullable()->constrained('branches')->cascadeOnDelete();

            $table->string('name');
            $table->text('description')->nullable();
            $table->string('category')->index();
            $table->decimal('price', 8, 2);
            $table->integer('estimated_duration');
            $table->json('meta_data')->nullable();
            $table->string('image_path')->nullable();

            $table->boolean('is_active')->default(true)->index();

            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
