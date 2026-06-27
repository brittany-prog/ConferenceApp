<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sponsors', function (Blueprint $table) {
            $table->string('headline')->nullable()->after('name');
            $table->string('booth_location')->nullable()->after('website_url');
            $table->string('cta_label')->nullable()->after('description');
            $table->string('cta_url')->nullable()->after('cta_label');
            $table->string('resource_title')->nullable()->after('cta_url');
            $table->string('resource_url')->nullable()->after('resource_title');
        });
    }

    public function down(): void
    {
        Schema::table('sponsors', function (Blueprint $table) {
            $table->dropColumn([
                'headline',
                'booth_location',
                'cta_label',
                'cta_url',
                'resource_title',
                'resource_url',
            ]);
        });
    }
};
