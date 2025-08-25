<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function markAllAsRead(Request $request)
    {
        Auth::user()->unreadNotifications->markAsRead();
        return redirect()->back()->with('success', 'Semua notifikasi telah ditandai sebagai dibaca.');
    }
}
