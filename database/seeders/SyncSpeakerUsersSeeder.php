<?php

namespace Database\Seeders;

use App\Models\Speaker;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SyncSpeakerUsersSeeder extends Seeder
{
    public function run(): void
    {
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

            DB::table('sessions')
                ->where('speaker_id', $speaker->id)
                ->update(['speaker_user_id' => $user->id]);
        }
    }
}
