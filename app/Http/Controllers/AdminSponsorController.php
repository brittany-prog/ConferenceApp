<?php

namespace App\Http\Controllers;

use App\Models\Sponsor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminSponsorController extends Controller
{
    private const MAX_SPONSORS = 20;

    public function index()
    {
        return view('admin.sponsors.index', [
            'sponsors' => Sponsor::query()->withCount('interestedUsers')->orderBy('display_order')->orderBy('name')->get(),
        ]);
    }

    public function create()
    {
        abort_if(Sponsor::count() >= self::MAX_SPONSORS, 403, 'A maximum of '.self::MAX_SPONSORS.' sponsors is supported.');

        return view('admin.sponsors.create');
    }

    public function store(Request $request)
    {
        abort_if(Sponsor::count() >= self::MAX_SPONSORS, 403, 'A maximum of '.self::MAX_SPONSORS.' sponsors is supported.');

        $validated = $this->validateSponsor($request);

        if ($request->hasFile('logo')) {
            $validated['logo_path'] = $request->file('logo')->store('sponsors', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active');

        Sponsor::create($validated);

        return redirect('/admin/sponsors')->with('success', 'Sponsor created successfully.');
    }

    public function edit(Sponsor $sponsor)
    {
        return view('admin.sponsors.edit', compact('sponsor'));
    }

    public function update(Request $request, Sponsor $sponsor)
    {
        $validated = $this->validateSponsor($request);

        if ($request->boolean('remove_logo') && $sponsor->logo_path) {
            Storage::disk('public')->delete($sponsor->logo_path);
            $validated['logo_path'] = null;
        }

        if ($request->hasFile('logo')) {
            if ($sponsor->logo_path) {
                Storage::disk('public')->delete($sponsor->logo_path);
            }

            $validated['logo_path'] = $request->file('logo')->store('sponsors', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active');

        $sponsor->update($validated);

        return redirect('/admin/sponsors')->with('success', 'Sponsor updated successfully.');
    }

    public function destroy(Sponsor $sponsor)
    {
        if ($sponsor->logo_path) {
            Storage::disk('public')->delete($sponsor->logo_path);
        }

        $sponsor->delete();

        return redirect('/admin/sponsors')->with('success', 'Sponsor deleted successfully.');
    }

    public function exportInterest()
    {
        $sponsors = Sponsor::query()
            ->with(['interestedUsers' => fn ($query) => $query->orderBy('name')])
            ->orderBy('display_order')
            ->orderBy('name')
            ->get();

        return response()->streamDownload(function () use ($sponsors) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'Sponsor',
                'Tier',
                'Booth Location',
                'Attendee Name',
                'Attendee Email',
                'Attendee Title',
                'Attendee Organization',
                'Marked Interested At',
            ]);

            foreach ($sponsors as $sponsor) {
                foreach ($sponsor->interestedUsers as $user) {
                    fputcsv($handle, [
                        $sponsor->name,
                        $sponsor->tier,
                        $sponsor->booth_location,
                        $user->name,
                        $user->email,
                        $user->title,
                        $user->organization,
                        $user->pivot->created_at?->toDateTimeString(),
                    ]);
                }
            }

            fclose($handle);
        }, 'southern-spark-sponsor-interest.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function exportSponsorInterest(Sponsor $sponsor)
    {
        $sponsor->load(['interestedUsers' => fn ($query) => $query->orderBy('name')]);
        $filename = 'sponsor-interest-'.str($sponsor->name)->slug()->value().'.csv';

        return response()->streamDownload(function () use ($sponsor) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'Sponsor',
                'Tier',
                'Booth Location',
                'Attendee Name',
                'Attendee Email',
                'Attendee Title',
                'Attendee Organization',
                'Marked Interested At',
            ]);

            foreach ($sponsor->interestedUsers as $user) {
                fputcsv($handle, [
                    $sponsor->name,
                    $sponsor->tier,
                    $sponsor->booth_location,
                    $user->name,
                    $user->email,
                    $user->title,
                    $user->organization,
                    $user->pivot->created_at?->toDateTimeString(),
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }

    private function validateSponsor(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'headline' => ['nullable', 'string', 'max:255'],
            'tier' => ['nullable', 'string', 'max:255'],
            'website_url' => ['nullable', 'url', 'max:2048'],
            'booth_location' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'cta_label' => ['nullable', 'string', 'max:255'],
            'cta_url' => ['nullable', 'url', 'max:2048'],
            'resource_title' => ['nullable', 'string', 'max:255'],
            'resource_url' => ['nullable', 'url', 'max:2048'],
            'display_order' => ['required', 'integer', 'min:1', 'max:'.self::MAX_SPONSORS],
            'is_active' => ['nullable', 'boolean'],
            'remove_logo' => ['nullable', 'boolean'],
            'logo' => ['nullable', 'image', 'max:4096'],
        ]);
    }
}
