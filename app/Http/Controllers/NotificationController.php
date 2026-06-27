<?php

namespace App\Http\Controllers;

use App\Support\NotificationCenter;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $notifications = NotificationCenter::for($request->user());

        return view('notifications.index', [
            'notifications' => $notifications['items'],
        ]);
    }
}
