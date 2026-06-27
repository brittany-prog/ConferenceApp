<?php

namespace App\Http\Controllers;

use App\Mail\AnnouncementPublishedMail;
use App\Models\Announcement;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AdminAnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::latest()->get();

        return view('admin.announcements.index', compact('announcements'));
    }

    public function create()
    {
        return view('admin.announcements.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string'],
            'delivery_mode' => ['nullable', 'in:none,test,all'],
        ]);

        $announcement = Announcement::create([
            'title' => $validated['title'],
            'message' => $validated['message'],
        ]);

        return $this->finishWithDelivery($request, $announcement, 'created');
    }

    public function edit(Announcement $announcement)
    {
        return view('admin.announcements.edit', compact('announcement'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string'],
            'delivery_mode' => ['nullable', 'in:none,test,all'],
        ]);

        $announcement->update([
            'title' => $validated['title'],
            'message' => $validated['message'],
        ]);

        return $this->finishWithDelivery($request, $announcement, 'updated');
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->delete();

        return redirect('/admin/announcements')->with('success', 'Announcement deleted successfully.');
    }

    private function finishWithDelivery(Request $request, Announcement $announcement, string $action)
    {
        $mode = $request->input('delivery_mode', 'none');

        if ($mode === 'test') {
            Mail::to($request->user()->email)->send(new AnnouncementPublishedMail($announcement));

            return redirect('/admin/announcements')->with(
                'success',
                'Announcement '.$action.' and test email sent to '.$request->user()->email.'.'
            );
        }

        if ($mode === 'all') {
            $emails = User::query()
                ->where('can_login', true)
                ->whereNotNull('email')
                ->pluck('email')
                ->filter()
                ->unique()
                ->values();

            foreach ($emails as $email) {
                Mail::to($email)->send(new AnnouncementPublishedMail($announcement));
            }

            return redirect('/admin/announcements')->with(
                'success',
                'Announcement '.$action.' and sent to '.$emails->count().' attendee email'.($emails->count() === 1 ? '' : 's').'.'
            );
        }

        return redirect('/admin/announcements')->with('success', 'Announcement '.$action.' successfully.');
    }
}
