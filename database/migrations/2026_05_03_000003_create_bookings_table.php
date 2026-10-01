<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
        $table->id();
        $table->uuid('uuid')->unique();
        $table->string('number')->unique()->index();
        
        $table->foreignId('user_id')->constrained();
        $table->foreignId('car_id')->constrained();
        $table->foreignId('service_id')->constrained();
        $table->foreignId('branch_id')->constrained();
        
        $table->foreignId('assigned_staff_id')->nullable()->constrained('users')->nullOnDelete();
        
        $table->decimal('service_price_at_booking', 8, 2);
        
        $table->date('booking_date');
        $table->time('start_time');
        $table->time('end_time');
        
        $table->string('status')->index();
        
        $table->text('customer_remark')->nullable();
        $table->boolean('is_rescheduled')->default(false)->index();
        $table->string('cancellation_reason')->nullable(); 
        $table->timestamp('qr_scanned_at')->nullable(); 
        $table->integer('recorded_mileage')->nullable(); 
        
        $table->timestamps();
        $table->timestamp('deleted_at')->nullable();
        
        $table->index(['booking_date', 'start_time', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
