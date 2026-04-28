<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        // Reset dummy session every time (for testing)
        session()->forget('notifications');

        if (!session()->has('notifications')) {
            session()->put('notifications', [
                [
                    'id' => 1,
                    'type' => 'new_request',
                    'title' => 'New Request Generated',
                    'message' => 'John Doe has generated a new service request.',
                    'is_read' => false,
                    'created_at' => now()->subMinutes(10)->diffForHumans(),
                ],
                [
                    'id' => 2,
                    'type' => 'driver_test_completed',
                    'title' => 'Driver Test Completed',
                    'message' => 'Driver Ali Khan successfully completed the driving test.',
                    'is_read' => false,
                    'created_at' => now()->subHours(1)->diffForHumans(),
                ],
                [
                    'id' => 3,
                    'type' => 'post_checklist_completed',
                    'title' => 'Post Checklist Completed',
                    'message' => 'Vehicle inspection checklist has been completed.',
                    'is_read' => true,
                    'created_at' => now()->subHours(3)->diffForHumans(),
                ],
                [
                    'id' => 4,
                    'type' => 'user_approved',
                    'title' => 'Request Approved by User',
                    'message' => 'User Sarah approved the maintenance request.',
                    'is_read' => true,
                    'created_at' => now()->subDay()->diffForHumans(),
                ],
            ]);
        }

        $notifications = session()->get('notifications');
        return view('Notification.index', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $notifications = session()->get('notifications', []);
        foreach ($notifications as &$n) {
            if ($n['id'] == $id) {
                $n['is_read'] = true;
                break;
            }
        }
        session()->put('notifications', $notifications);

        return response()->json(['success' => true]);
    }

    public function delete($id)
    {
        $notifications = collect(session()->get('notifications', []))
            ->reject(fn($n) => $n['id'] == $id)
            ->values()
            ->toArray();

        session()->put('notifications', $notifications);

        return response()->json(['success' => true]);
    }
}
