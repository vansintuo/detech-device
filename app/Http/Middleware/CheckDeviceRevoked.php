<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\DetectDevice;

class CheckDeviceRevoked
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $fp = $request->cookie('device_fp');
            if ($fp) {
                $fpHash = hash('sha256', $fp);
                // try hashed fingerprint first, fallback to raw value for compatibility
                $session = DetectDevice::where('user_id', Auth::id())
                    ->where(function($q) use ($fpHash, $fp) {
                        $q->where('fingerprint', $fpHash)
                          ->orWhere('fingerprint', $fp);
                    })->first();
                if ($session && $session->revoked) {
                    // revoke current user session: logout and clear session
                    Auth::logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();
                    // clear cookie
                    $cookie = cookie()->forget('device_fp');
                    return redirect('/login')->with('status', 'Your device session has been revoked.')->withCookie($cookie);
                }
            }
        }

        return $next($request);
    }
}
