<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class NotificationController extends Controller
{
    /**
     * Tampilkan semua notifikasi user login.
     */
    public function index()
    {
        $notifications = Auth::user()->notifications()->paginate(10);

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Hapus notifikasi tertentu.
     */
    public function destroy($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->delete();

        return redirect()->back()->with('');
    }
}
