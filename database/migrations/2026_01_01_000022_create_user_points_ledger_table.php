<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_points_ledger', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('action');
            $table->integer('points');
            $table->string('reference_type')->default('');
            $table->unsignedBigInteger('reference_id')->default(0);
            $table->string('description')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'action', 'reference_type', 'reference_id'], 'user_points_unique_action_reference');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_points_ledger');
    }
};
