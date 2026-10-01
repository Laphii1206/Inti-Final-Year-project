<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('webauthn_credentials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('credential_id')->unique(); // base64url encoded credential ID
            $table->text('public_key');                 // base64url encoded public key (COSE)
            $table->string('name')->default('My Device'); // user-friendly device name
            $table->bigInteger('sign_count')->default(0); // signature counter for replay protection
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('webauthn_credentials');
    }
};
