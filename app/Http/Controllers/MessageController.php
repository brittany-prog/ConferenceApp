<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $conversations = Message::query()
            ->with(['sender', 'recipient'])
            ->where('sender_id', $user->id)
            ->orWhere('recipient_id', $user->id)
            ->latest()
            ->get()
            ->groupBy(function ($message) use ($user) {
                return $message->sender_id === $user->id ? $message->recipient_id : $message->sender_id;
            })
            ->map(function ($messages) use ($user) {
                $latest = $messages->first();
                $otherUser = $latest->sender_id === $user->id ? $latest->recipient : $latest->sender;
                $unreadCount = $messages->where('recipient_id', $user->id)->whereNull('read_at')->count();

                return [
                    'user' => $otherUser,
                    'latest' => $latest,
                    'unread_count' => $unreadCount,
                ];
            })
            ->filter(fn ($conversation) => $conversation['user'] && $conversation['user']->can_login && ! $conversation['user']->is_admin)
            ->values();

        return view('messages.index', compact('conversations'));
    }

    public function show(Request $request, User $user)
    {
        abort_if($request->user()->id === $user->id, 404);
        abort_unless($user->can_login && ! $user->is_admin, 404);

        $messages = Message::query()
            ->with(['sender', 'recipient'])
            ->where(function ($query) use ($request, $user) {
                $query->where('sender_id', $request->user()->id)
                    ->where('recipient_id', $user->id);
            })
            ->orWhere(function ($query) use ($request, $user) {
                $query->where('sender_id', $user->id)
                    ->where('recipient_id', $request->user()->id);
            })
            ->orderBy('created_at')
            ->get();

        Message::query()
            ->where('sender_id', $user->id)
            ->where('recipient_id', $request->user()->id)
            ->whereNull('read_at')
            ->update(['read_at' => Carbon::now()]);

        return view('messages.show', [
            'recipient' => $user,
            'messages' => $messages,
        ]);
    }

    public function store(Request $request, User $user)
    {
        abort_if($request->user()->id === $user->id, 422, 'You cannot message yourself.');
        abort_unless($user->can_login && ! $user->is_admin, 404);

        $validated = $request->validate([
            'body' => ['required', 'string', 'max:5000'],
        ]);

        Message::create([
            'sender_id' => $request->user()->id,
            'recipient_id' => $user->id,
            'body' => $validated['body'],
        ]);

        return redirect('/messages/'.$user->id);
    }
}
