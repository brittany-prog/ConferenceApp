<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_exhibitor')->default(false)->after('is_speaker');
            $table->foreignId('sponsor_id')->nullable()->after('is_exhibitor')->constrained('sponsors')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('sponsor_id');
            $table->dropColumn('is_exhibitor');
        });
    }
};
