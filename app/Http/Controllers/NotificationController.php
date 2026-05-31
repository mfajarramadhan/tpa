<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /*
    =====================================================
    LIST NOTIFIKASI
    =====================================================
    */
    public function index()
    {
        $notifications = auth()->user()->notifications()->latest()->paginate(10);

        // AUTO READ
        auth()->user()
            ->unreadNotifications
            ->markAsRead();

        return view(
            'notifications.index',
            compact('notifications')
        );
    }


    // DELETE
    public function destroy($id)
    {
        $notification = auth()->user()

            ->notifications()

            ->findOrFail($id);

        $notification->delete();

        return back()->with(
            'success',
            'Notifikasi berhasil dihapus!'
        );
    }
}