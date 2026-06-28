<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    public function up(): void
    {
        $password = Hash::make('RedBeans1234!');
        $existing = DB::table('users')
            ->where('email', 'david-ceo@redbeans.io')
            ->first();

        if ($existing) {
            DB::table('users')
                ->where('id', $existing->id)
                ->update([
                    'name' => 'David CEO',
                    'password' => $password,
                    'is_admin' => true,
                    'can_login' => true,
                    'updated_at' => now(),
                ]);

            return;
        }

        $legacyAdmin = DB::table('users')
            ->where('email', 'admin@conferenceapp.test')
            ->first();

        if ($legacyAdmin) {
            DB::table('users')
                ->where('id', $legacyAdmin->id)
                ->update([
                    'name' => 'David CEO',
                    'email' => 'david-ceo@redbeans.io',
                    'password' => $password,
                    'is_admin' => true,
                    'can_login' => true,
                    'updated_at' => now(),
                ]);

            return;
        }

        DB::table('users')->insert([
            'name' => 'David CEO',
            'email' => 'david-ceo@redbeans.io',
            'password' => $password,
            'is_admin' => true,
            'can_login' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        //
    }
};
