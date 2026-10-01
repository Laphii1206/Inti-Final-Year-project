<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('branches', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('contact_number');
        $table->text('address');
        $table->string('google_map_link')->nullable();
        $table->time('opening_time');
        $table->time('closing_time');
        $table->integer('service_capacity')->default(3);
        $table->string('image_path')->nullable();

        $table->boolean('is_active')->default(true)->index();

        $table->timestamps();
        $table->timestamp('deleted_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('branches');
    }
};
