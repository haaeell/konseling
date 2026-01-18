<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    /**
     * Display a listing of the notifications.
     */
    public function index()
    {
        $user = Auth::user();
        
        $notifications = $user->notifications()
            ->latest()
            ->paginate(15);
        
        $unreadCount = $user->unreadNotifications()->count();
        
        return view('notifications.index', compact('notifications', 'unreadCount'));
    }

    /**
     * Mark a single notification as read.
     */
    public function markAsRead($id)
    {
        $user = Auth::user();
        $notification = $user->notifications()->where('id', $id)->first();

        if ($notification) {
            $notification->markAsRead();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 404);
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead(Request $request)
    {
        Auth::user()->unreadNotifications()->update(['read_at' => now()]);
        
        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }
        
        return redirect()->back()->with('success', 'Semua notifikasi telah ditandai sebagai dibaca.');
    }

    /**
     * Delete a notification.
     */
    public function destroy($id)
    {
        $notification = DatabaseNotification::findOrFail($id);
        
        if ($notification->notifiable_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $notification->delete();

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Notifikasi telah dihapus.');
    }

    /**
     * Get unread notifications count (for AJAX).
     */
    public function unreadCount()
    {
        $count = Auth::user()->unreadNotifications()->count();
        
        return response()->json([
            'unread_count' => $count
        ]);
    }

    /**
     * Get recent unread notifications (for popup/toast).
     */
    public function getRecent()
    {
        $notifications = Auth::user()
            ->unreadNotifications()
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'type' => class_basename($notification->type),
                    'data' => $notification->data,
                    'created_at' => $notification->created_at->diffForHumans(),
                ];
            });

        return response()->json([
            'notifications' => $notifications
        ]);
    }
}
