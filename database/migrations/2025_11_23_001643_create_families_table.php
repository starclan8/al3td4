<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('families', function (Blueprint $table) {
            $table->id();
            $table->string('family_name');
            $table->string('contact_email')->unique();
            $table->string('contact_phone')->nullable();
            $table->string('password'); // For auth - should not be nullable
            $table->rememberToken();
            $table->string('city')->nullable();
            $table->boolean('is_demo')->default(false);
            $table->boolean('is_active')->default(true);
            $table->enum('privacy_level', ['anonymous', 'first_name', 'full_name'])->default('first_name');
            $table->text('bio')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamps();

            $table->index('is_demo');
            $table->index('is_active');
            $table->index('city');
            $table->index('contact_email');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('families');
    }
};