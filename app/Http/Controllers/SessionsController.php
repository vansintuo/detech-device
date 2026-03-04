<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\DetectDevice;

class SessionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $sessions = DetectDevice::where('user_id', Auth::id())->orderBy('last_active_at', 'desc')->where('revoked', 0)->get();
        return view('sessions.index', compact('sessions'));
    }

    public function revoke(Request $request, $id)
    {
        $session = DetectDevice::where('user_id', Auth::id())->where('id', $id)->where('revoked', 0)->firstOrFail();
        $session->revoked = true;
        $session->revoked_at = now();
        $session->save();
        // If the revoked session matches current device fingerprint (hash or raw), force logout immediately
        $currentFp = $request->cookie('device_fp');
        if ($currentFp) {
            $fpHash = hash('sha256', $currentFp);
            if ($session->fingerprint === $fpHash || $session->fingerprint === $currentFp) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                $cookie = cookie()->forget('device_fp');
                return redirect('/login')->with('status', 'Your current device session was revoked.')->withCookie($cookie);
            }
        }
        return back()->with('status', 'Session revoked');
    }

    public function revokeAll(Request $request)
    {
        $userId = Auth::id();
        DetectDevice::where('user_id', $userId)->update([
            'revoked' => true,
            'revoked_at' => now(),
        ]);
        // Logout current session immediately
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        $cookie = cookie()->forget('device_fp');
        return redirect('/login')->with('status', 'All sessions revoked.')->withCookie($cookie);
    }
}
