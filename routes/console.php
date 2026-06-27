<?php

use App\Models\Session;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

Artisan::command('inspire', function () {
    $this->comment('Keep building. Your app is taking shape.');
})->purpose('Display an encouraging message');

Artisan::command('speakers:normalize {--dry-run : Preview the speaker splits without saving changes}', function () {
    $dryRun = (bool) $this->option('dry-run');

    $sessionSpeakerMap = [
        'Building with LLMs' => [
            ['name' => 'Brandon Newton', 'email' => 'brandon-newton@speaker.southernspark.local'],
            ['name' => 'David Placeholder', 'email' => 'david-placeholder@speaker.southernspark.local'],
            ['name' => 'Bob Placeholder', 'email' => 'bob-placeholder@speaker.southernspark.local'],
        ],
        'Emerging/AI Technology Strategy Gaming for Social Impact' => [
            ['name' => 'Luciano Oviedo', 'email' => 'luciano-oviedo@speaker.southernspark.local'],
            ['name' => 'Dell Gines', 'email' => 'dell-gines@speaker.southernspark.local'],
        ],
        'Plenary Opening Panel: AI Policy & Data Governance Panel: Statewide Findings' => [
            ['name' => 'James Robinson', 'email' => 'james-robinson@speaker.southernspark.local'],
            ['name' => 'Eva Harvell', 'email' => 'eva-harvell@speaker.southernspark.local'],
            ['name' => 'Lamarus Norman', 'email' => 'lamarus-norman@speaker.southernspark.local'],
            ['name' => 'Gigi Mims', 'email' => 'gigi-mims@speaker.southernspark.local'],
        ],
        "Mississippi's K-12 AI Policy and Teacher Training" => [
            ['name' => 'Melissa Placeholder', 'email' => 'melissa-placeholder@speaker.southernspark.local'],
            ['name' => 'John Placeholder', 'email' => 'john-placeholder@speaker.southernspark.local'],
        ],
        'Socratic by Design, Safe by Default: Khan Academy + Khanmigo in Action' => [
            ['name' => 'Heather White', 'email' => 'heather-white@speaker.southernspark.local'],
            ['name' => 'Nick Joe', 'email' => 'nick-joe@speaker.southernspark.local'],
        ],
        'Building a Statewide AI Innovation Hub for Public Impact' => [
            ['name' => 'Shelley Thompson', 'email' => 'shelley-thompson@speaker.southernspark.local'],
            ['name' => 'Benjamin Graham', 'email' => 'benjamin-graham@speaker.southernspark.local'],
        ],
        "Code the Beat: Music, Messaging, and Mississippi's Next Coding DJ Competition" => [
            ['name' => 'William Edd Blake', 'email' => 'william-edd-blake@speaker.southernspark.local'],
            ['name' => 'Tiffany Henderson', 'email' => 'tiffany-henderson@speaker.southernspark.local'],
        ],
        'Hidden Curriculum of Innovation' => [
            ['name' => 'Delaney Foster', 'email' => 'delaney-foster@speaker.southernspark.local'],
            ['name' => 'Katerina Sergi', 'email' => 'katerina-sergi@speaker.southernspark.local'],
        ],
        'Beyond the Tool: Building Institutional AI Capacity' => [
            ['name' => 'Delaney Foster', 'email' => 'delaney-foster@speaker.southernspark.local'],
            ['name' => 'Julie Jordan', 'email' => 'julie-jordan@speaker.southernspark.local'],
        ],
        'What Does It Take to Build a Regional Tech Hub?' => [
            ['name' => 'Raymonda Placeholder', 'email' => 'raymonda-placeholder@speaker.southernspark.local'],
            ['name' => 'Nashlie Placeholder', 'email' => 'nashlie-placeholder@speaker.southernspark.local'],
        ],
        'From Rural Classrooms to Regional Talent Pipelines: Implementing AI and Data Literacy with Quanthub in the South' => [
            ['name' => 'Belinda Patton', 'email' => 'belinda-patton@speaker.southernspark.local'],
            ['name' => 'Stephanie Triplett', 'email' => 'stephanie-triplett@speaker.southernspark.local'],
        ],
    ];

    $makeSpeaker = function (array $speakerDef) use ($dryRun) {
        $existingUser = User::query()->where('email', $speakerDef['email'])->first();

        if ($dryRun) {
            return $existingUser ?: new User([
                'id' => null,
                'name' => $speakerDef['name'],
                'email' => $speakerDef['email'],
                'is_speaker' => true,
                'can_login' => false,
            ]);
        }

        $user = User::firstOrCreate(
            ['email' => $speakerDef['email']],
            [
                'name' => $speakerDef['name'],
                'password' => Hash::make(Str::random(32)),
                'can_login' => false,
                'is_speaker' => true,
            ]
        );

        $user->update([
            'name' => $speakerDef['name'],
            'is_speaker' => true,
        ]);

        return $user;
    };

    $updatedCount = 0;

    foreach ($sessionSpeakerMap as $sessionTitle => $speakerDefs) {
        $session = Session::query()
            ->with('speakers')
            ->where('title', $sessionTitle)
            ->first();

        if (! $session) {
            $this->warn("Session not found: {$sessionTitle}");
            continue;
        }

        $speakerUsers = collect($speakerDefs)
            ->map(fn (array $speakerDef) => $makeSpeaker($speakerDef))
            ->values();

        $speakerIds = $speakerUsers
            ->pluck('id')
            ->filter()
            ->values()
            ->all();

        if (! $dryRun) {
            $session->update([
                'speaker_user_id' => $speakerIds[0] ?? null,
            ]);

            $session->speakers()->sync($speakerIds);
        }

        $updatedCount++;

        $this->info(($dryRun ? '[Dry run] ' : '').$sessionTitle);
        $this->line('  Speakers: '.$speakerUsers->pluck('name')->implode(', '));
    }

    $this->newLine();
    $this->comment(($dryRun ? 'Previewed' : 'Updated').' '.$updatedCount.' session(s).');
    $this->comment('Placeholder speaker accounts are created with login disabled.');
})->purpose('Split combined speaker records into individual speaker users and attach them to sessions');
