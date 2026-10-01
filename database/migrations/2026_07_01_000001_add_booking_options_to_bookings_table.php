<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Stores the snapshot of user-selected service options at booking time
            // (e.g. tyre_count=4, oil_quantity=5L). Null when no options are required.
            $table->json('booking_options')->nullable()->after('customer_remark');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('booking_options');
        });
    }
};
