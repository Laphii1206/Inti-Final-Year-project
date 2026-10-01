<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('branches')->update(['service_capacity' => 3]);
    }

    public function down(): void
    {
        // No reliable rollback — the previous values (4, 6, 3) are not stored.
    }
};
