<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_family')->default(false)->after('email_verified_at');
            $table->string('family_name')->nullable()->after('is_family');
            $table->string('contact_phone')->nullable()->after('email');
            $table->string('city')->nullable()->after('contact_phone');
            $table->enum('privacy_level', ['anonymous', 'first_name', 'full_name'])->default('first_name')->after('city');
            $table->text('bio')->nullable()->after('privacy_level');
            $table->boolean('is_demo')->default(false)->after('bio');
            
            $table->index('is_family');
            $table->index('city');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'is_family',
                'family_name',
                'contact_phone',
                'city',
                'privacy_level',
                'bio',
                'is_demo',
            ]);
        });
    }
};