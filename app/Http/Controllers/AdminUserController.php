<?php

namespace App\Http\Controllers;

use App\Models\Sponsor;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $users = $this->filteredUsersQuery($request)
            ->latest()
            ->get();

        return view('admin.users.index', [
            'users' => $users,
            'sponsors' => Sponsor::query()->orderBy('display_order')->orderBy('name')->get(),
        ]);
    }

    public function export(Request $request)
    {
        $users = $this->filteredUsersQuery($request)
            ->orderBy('name')
            ->orderBy('email')
            ->get();

        return response()->streamDownload(function () use ($users) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'Name',
                'Email',
                'Title',
                'Organization',
                'Role',
                'Can Login',
                'Linked Sponsor',
                'Created At',
            ]);

            foreach ($users as $user) {
                fputcsv($handle, [
                    $user->name,
                    $user->email,
                    $user->title,
                    $user->organization,
                    $this->userRoleLabel($user),
                    $user->can_login ? 'Yes' : 'No',
                    $user->sponsor?->name,
                    $user->created_at?->toDateTimeString(),
                ]);
            }

            fclose($handle);
        }, 'southern-spark-users.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function exportImportTemplate()
    {
        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'name',
                'email',
                'title',
                'organization',
                'can_login',
                'is_speaker',
                'is_exhibitor',
                'is_admin',
                'sponsor',
            ]);

            fputcsv($handle, [
                'Taylor Example',
                'taylor@example.com',
                'Program Manager',
                'Mississippi AI Collaborative',
                'yes',
                'no',
                'no',
                'no',
                '',
            ]);

            fclose($handle);
        }, 'southern-spark-attendee-import-template.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function import(Request $request)
    {
        $validated = $request->validate([
            'csv_file' => ['required', 'file', 'mimes:csv,txt', 'max:5120'],
            'update_existing' => ['nullable', 'boolean'],
            'send_password_setup' => ['nullable', 'boolean'],
        ]);

        $file = $validated['csv_file'];
        $handle = fopen($file->getRealPath(), 'r');

        if ($handle === false) {
            throw ValidationException::withMessages([
                'csv_file' => 'We could not read that CSV file. Please try again with a fresh export.',
            ]);
        }

        $headerRow = fgetcsv($handle);

        if (! is_array($headerRow) || $headerRow === []) {
            fclose($handle);

            throw ValidationException::withMessages([
                'csv_file' => 'That CSV file is empty.',
            ]);
        }

        $headerMap = $this->normalizeImportHeaders($headerRow);

        foreach (['name', 'email'] as $requiredColumn) {
            if (! array_key_exists($requiredColumn, $headerMap)) {
                fclose($handle);

                throw ValidationException::withMessages([
                    'csv_file' => "The CSV is missing the required \"{$requiredColumn}\" column.",
                ]);
            }
        }

        $updateExisting = $request->boolean('update_existing');
        $sendPasswordSetup = $request->boolean('send_password_setup');
        $createdCount = 0;
        $updatedCount = 0;
        $skippedCount = 0;
        $setupSentCount = 0;
        $rowNumber = 1;
        $errors = [];

        while (($row = fgetcsv($handle)) !== false) {
            $rowNumber++;

            if ($this->csvRowIsBlank($row)) {
                continue;
            }

            $data = $this->mapImportRow($row, $headerMap);
            $name = trim((string) ($data['name'] ?? ''));
            $email = Str::lower(trim((string) ($data['email'] ?? '')));

            if ($name === '' || $email === '') {
                $errors[] = "Row {$rowNumber}: name and email are required.";
                continue;
            }

            if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Row {$rowNumber}: {$email} is not a valid email address.";
                continue;
            }

            $user = User::where('email', $email)->first();
            $isExisting = (bool) $user;

            if ($isExisting && ! $updateExisting) {
                $skippedCount++;
                continue;
            }

            $attributes = [
                'name' => $name,
                'email' => $email,
                'title' => $this->nullableTrimmed($data['title'] ?? null),
                'organization' => $this->nullableTrimmed($data['organization'] ?? null),
                'can_login' => $this->csvBoolean($data['can_login'] ?? null, true),
                'is_speaker' => $this->csvBoolean($data['is_speaker'] ?? null, false),
                'is_exhibitor' => $this->csvBoolean($data['is_exhibitor'] ?? null, false),
                'is_admin' => $this->csvBoolean($data['is_admin'] ?? null, false),
            ];

            $sponsorId = $this->resolveSponsorIdFromImport($data);
            $attributes['sponsor_id'] = $attributes['is_exhibitor'] ? $sponsorId : null;

            if (! $isExisting) {
                $attributes['password'] = Str::random(40);
                $user = User::create($attributes);
                $createdCount++;
            } else {
                $user->update($attributes);
                $updatedCount++;
            }

            if ($sendPasswordSetup && $user->can_login) {
                try {
                    $this->sendPasswordSetupLinkOrFail($user);
                    $setupSentCount++;
                } catch (\Throwable $exception) {
                    $errors[] = "Row {$rowNumber}: user saved, but we could not send the setup email to {$email}.";
                }
            }
        }

        fclose($handle);

        $summary = collect([
            $createdCount > 0 ? "{$createdCount} created" : null,
            $updatedCount > 0 ? "{$updatedCount} updated" : null,
            $skippedCount > 0 ? "{$skippedCount} skipped" : null,
            $setupSentCount > 0 ? "{$setupSentCount} setup emails sent" : null,
        ])->filter()->implode(', ');

        if ($summary === '') {
            $summary = 'No users were imported.';
        }

        if ($errors !== []) {
            $summary .= ' Issues: '.implode(' ', array_slice($errors, 0, 5));

            if (count($errors) > 5) {
                $summary .= ' Additional row issues were omitted.';
            }
        }

        return redirect('/admin/users')->with('success', $summary);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'title' => ['nullable', 'string', 'max:255'],
            'organization' => ['nullable', 'string', 'max:255'],
            'is_admin' => ['nullable', 'boolean'],
            'can_login' => ['nullable', 'boolean'],
            'is_speaker' => ['nullable', 'boolean'],
            'is_exhibitor' => ['nullable', 'boolean'],
            'sponsor_id' => ['nullable', 'integer', 'exists:sponsors,id'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Str::random(40),
            'title' => $validated['title'] ?? null,
            'organization' => $validated['organization'] ?? null,
            'is_admin' => $request->boolean('is_admin'),
            'can_login' => $request->boolean('can_login', true),
            'is_speaker' => $request->boolean('is_speaker'),
            'is_exhibitor' => $request->boolean('is_exhibitor'),
            'sponsor_id' => $request->boolean('is_exhibitor') ? ($validated['sponsor_id'] ?? null) : null,
        ]);

        $this->sendPasswordSetupLinkOrFail($user);

        return redirect('/admin/users')->with('success', 'User created and password setup email sent.');
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'title' => ['nullable', 'string', 'max:255'],
            'organization' => ['nullable', 'string', 'max:255'],
            'is_admin' => ['nullable', 'boolean'],
            'can_login' => ['nullable', 'boolean'],
            'is_speaker' => ['nullable', 'boolean'],
            'is_exhibitor' => ['nullable', 'boolean'],
            'sponsor_id' => ['nullable', 'integer', 'exists:sponsors,id'],
        ]);

        $isAdmin = $request->boolean('is_admin');
        $canLogin = $request->boolean('can_login');
        $isSpeaker = $request->boolean('is_speaker');
        $isExhibitor = $request->boolean('is_exhibitor');
        $sendPasswordSetup = $request->boolean('send_password_setup');
        $emailChanged = $validated['email'] !== $user->email;

        if ($user->id === $request->user()->id && (! $isAdmin || ! $canLogin)) {
            return back()->withErrors([
                'email' => 'You cannot remove your own admin access or disable your own login.',
            ]);
        }

        if ($user->is_admin && ! $isAdmin && User::where('is_admin', true)->count() === 1) {
            return back()->withErrors([
                'email' => 'At least one admin account must remain active.',
            ]);
        }

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'title' => $validated['title'] ?? null,
            'organization' => $validated['organization'] ?? null,
            'is_admin' => $isAdmin,
            'can_login' => $canLogin,
            'is_speaker' => $isSpeaker,
            'is_exhibitor' => $isExhibitor,
            'sponsor_id' => $isExhibitor ? ($validated['sponsor_id'] ?? null) : null,
        ]);

        if (($emailChanged || $sendPasswordSetup) && $user->can_login) {
            $this->sendPasswordSetupLinkOrFail($user);
        }

        return redirect('/admin/users')->with('success', ($emailChanged || $sendPasswordSetup)
            ? 'User updated and password setup email sent.'
            : 'User updated successfully.');
    }

    public function destroy(Request $request, User $user)
    {
        if ($user->id === $request->user()->id) {
            return back()->withErrors([
                'email' => 'You cannot delete your own account.',
            ]);
        }

        if ($user->is_admin && User::where('is_admin', true)->count() === 1) {
            return back()->withErrors([
                'email' => 'At least one admin account must remain active.',
            ]);
        }

        if ($user->profile_photo_path) {
            Storage::disk('public')->delete($user->profile_photo_path);
        }

        $user->delete();

        return redirect('/admin/users')->with('success', 'User deleted successfully.');
    }

    private function sendPasswordSetupLinkOrFail(User $user): void
    {
        try {
            app(AuthController::class)->sendPasswordSetupLink($user);
        } catch (\Throwable $exception) {
            Log::error('Admin-triggered password setup email failed.', [
                'user_id' => $user->id,
                'email' => $user->email,
                'error' => $exception->getMessage(),
            ]);

            throw ValidationException::withMessages([
                'email' => 'We could not send the account setup email right now. Please check the mail settings and try again.',
            ]);
        }
    }

    private function filteredUsersQuery(Request $request): Builder
    {
        return User::query()
            ->with('sponsor')
            ->when($request->filled('q'), function ($query) use ($request) {
                $search = trim((string) $request->string('q'));

                $query->where(function ($nested) use ($search) {
                    $nested->where('name', 'like', '%'.$search.'%')
                        ->orWhere('email', 'like', '%'.$search.'%')
                        ->orWhere('title', 'like', '%'.$search.'%')
                        ->orWhere('organization', 'like', '%'.$search.'%');
                });
            })
            ->when($request->string('role')->value(), function ($query, $role) {
                if ($role === 'admin') {
                    $query->where('is_admin', true);
                } elseif ($role === 'speaker') {
                    $query->where('is_speaker', true);
                } elseif ($role === 'exhibitor') {
                    $query->where('is_exhibitor', true);
                } elseif ($role === 'attendee') {
                    $query->where('is_admin', false)->where('is_speaker', false)->where('is_exhibitor', false);
                }
            })
            ->when($request->string('status')->value(), function ($query, $status) {
                if ($status === 'active') {
                    $query->where('can_login', true);
                } elseif ($status === 'disabled') {
                    $query->where('can_login', false);
                }
            });
    }

    private function userRoleLabel(User $user): string
    {
        $roles = [];

        if ($user->is_admin) {
            $roles[] = 'Admin';
        }

        if ($user->is_speaker) {
            $roles[] = 'Speaker';
        }

        if ($user->is_exhibitor) {
            $roles[] = 'Exhibitor';
        }

        return $roles !== [] ? implode(', ', $roles) : 'Attendee';
    }

    /**
     * @return array<string, int>
     */
    private function normalizeImportHeaders(array $headers): array
    {
        $map = [];

        foreach ($headers as $index => $header) {
            $normalized = Str::of((string) $header)
                ->trim()
                ->lower()
                ->replace(['-', ' '], '_')
                ->value();

            if ($normalized !== '' && ! array_key_exists($normalized, $map)) {
                $map[$normalized] = $index;
            }
        }

        return $map;
    }

    /**
     * @param array<int, mixed> $row
     * @param array<string, int> $headerMap
     * @return array<string, mixed>
     */
    private function mapImportRow(array $row, array $headerMap): array
    {
        $data = [];

        foreach ($headerMap as $header => $index) {
            $data[$header] = $row[$index] ?? null;
        }

        return $data;
    }

    /**
     * @param array<int, mixed> $row
     */
    private function csvRowIsBlank(array $row): bool
    {
        foreach ($row as $value) {
            if (trim((string) $value) !== '') {
                return false;
            }
        }

        return true;
    }

    private function nullableTrimmed(mixed $value): ?string
    {
        $trimmed = trim((string) $value);

        return $trimmed === '' ? null : $trimmed;
    }

    private function csvBoolean(mixed $value, bool $default): bool
    {
        $normalized = Str::lower(trim((string) $value));

        if ($normalized === '') {
            return $default;
        }

        return in_array($normalized, ['1', 'true', 'yes', 'y'], true);
    }

    /**
     * @param array<string, mixed> $data
     */
    private function resolveSponsorIdFromImport(array $data): ?int
    {
        $sponsorId = trim((string) ($data['sponsor_id'] ?? ''));

        if ($sponsorId !== '' && ctype_digit($sponsorId)) {
            $sponsor = Sponsor::find((int) $sponsorId);

            if ($sponsor) {
                return $sponsor->id;
            }
        }

        $sponsorName = trim((string) ($data['sponsor'] ?? ''));

        if ($sponsorName === '') {
            return null;
        }

        return Sponsor::whereRaw('LOWER(name) = ?', [Str::lower($sponsorName)])->value('id');
    }
}
