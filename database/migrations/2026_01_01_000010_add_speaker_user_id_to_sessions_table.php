<?php

use App\Models\Speaker;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sessions', function (Blueprint $table) {
            $table->foreignId('speaker_user_id')->nullable()->after('speaker_id')->constrained('users')->nullOnDelete();
        });

        if (! Schema::hasTable('speakers')) {
            return;
        }

        $speakers = Speaker::query()->get();

        foreach ($speakers as $speaker) {
            $emailBase = Str::slug($speaker->full_name ?: 'speaker');
            $email = $emailBase.'@speaker.southernspark.local';

            $suffix = 1;
            while (User::where('email', $email)->where('name', '!=', $speaker->full_name)->exists()) {
                $email = $emailBase.$suffix.'@speaker.southernspark.local';
                $suffix++;
            }

            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $speaker->full_name,
                    'title' => $speaker->title,
                    'organization' => $speaker->organization,
                    'bio' => $speaker->bio,
                    'password' => Hash::make(Str::random(32)),
                    'can_login' => true,
                    'is_speaker' => true,
                ]
            );

            $user->update([
                'name' => $speaker->full_name,
                'title' => $speaker->title,
                'organization' => $speaker->organization,
                'bio' => $speaker->bio,
                'is_speaker' => true,
            ]);

            \DB::table('sessions')
                ->where('speaker_id', $speaker->id)
                ->update(['speaker_user_id' => $user->id]);
        }
    }

    public function down(): void
    {
        Schema::table('sessions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('speaker_user_id');
        });
    }
};
