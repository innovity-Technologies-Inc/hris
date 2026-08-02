<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Services\Setting\NotificationServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    protected $notificationServices;

    public function __construct(NotificationServices $notificationServices)
    {
        $this->notificationServices = $notificationServices;
    }

    /**
     * Display a listing of all notifications.
     */
    public function index()
    {
        $title = 'Notifications';
        $section = 'Dashboard';
        $sub_section = 'All Notifications';
        $user = Auth::user();
        
        $notifications = $this->notificationServices->getVisibleNotifications($user)
            ->latest()
            ->paginate(20);

        return view('notifications.index', compact('title', 'section', 'sub_section', 'notifications'));
    }

    /**
     * Get unread notifications for the header.
     */
    public function getHeaderNotifications()
    {
        $user = Auth::user();
        $notifications = $this->notificationServices->getVisibleNotifications($user)
            ->whereNull('read_at')
            ->limit(10)
            ->get();

        $unreadCount = $this->notificationServices->getVisibleNotifications($user)
            ->whereNull('read_at')
            ->count();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount,
            'html' => view('structure.partials.notification_items', compact('notifications'))->render()
        ]);
    }

    /**
     * Mark a notification as read.
     */
    public function markAsRead($id)
    {
        $user = Auth::user();
        $notification = $this->notificationServices->getVisibleNotifications($user)
            ->where('id', $id)
            ->first();

        if ($notification) {
            $notification->update(['read_at' => now()]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Mark all as read.
     */
    public function markAllAsRead()
    {
        $user = Auth::user();
        $this->notificationServices->getVisibleNotifications($user)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json(['success' => true]);
    }
}

