<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('needs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->enum('status', ['pending', 'approved', 'active', 'fulfilled', 'cancelled'])->default('pending');
            $table->date('needed_by')->nullable();
            $table->string('location')->nullable();
            $table->enum('urgency', ['low', 'medium', 'high'])->default('medium');
            $table->integer('helper_slots')->default(1); // How many helpers needed
            $table->integer('helpers_signed_up')->default(0);
            $table->boolean('is_recurring')->default(false);
            $table->string('recurrence_pattern')->nullable(); // 'weekly', 'monthly', etc.
            $table->boolean('is_public')->default(true);
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('user_id');
            $table->index('status');
            $table->index('urgency');
            $table->index('needed_by');
            $table->index('is_public');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('needs');
    }
};