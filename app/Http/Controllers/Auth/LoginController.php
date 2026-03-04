<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\DetectDevice;

// For parsing user agent we'll use a tiny helper below (no external package)

class LoginController extends Controller
{
    /**
     * Show the login form
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle a login attempt
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            // fingerprint cookie - if not present, generate one (raw value stored in cookie)
            $fingerprintRaw = $request->cookie('device_fp');
            if (! $fingerprintRaw) {
                $fingerprintRaw = Str::uuid() . '|' . time();
            }
            // store a hash in DB (migration comment suggests hashed fingerprint)
            $fingerprintHash = hash('sha256', $fingerprintRaw);

            // basic user agent parsing (platform/browser)
            $ua = $request->header('User-Agent', '');
            $browser = $this->detectBrowser($ua);
            $platform = $this->detectPlatform($ua);
            $deviceType = $this->detectDeviceType($ua);
            $deviceName = $browser . ' on ' . $platform;

            // record or update detect_device entry
            try {
                DetectDevice::create([
                    'user_id' => Auth::id(),
                    'device_name' => $deviceName,
                    'device_type' => $deviceType,
                    'platform' => $platform,
                    'browser' => $browser,
                    'ip' => $request->ip(),
                    'user_agent' => $ua,
                    'fingerprint' => $fingerprintHash,
                    'last_active_at' => now(),
                ]);
            } catch (\Exception $e) {
                // swallow DB exceptions - non-fatal for login
            }

            // set cookie for future fingerprint recognition (remember for 1 year)
            // set cookie with raw fingerprint for the client (expires in 1 year)
            $cookie = cookie('device_fp', $fingerprintRaw, 60 * 24 * 365);

            return redirect()->intended('/users')->withCookie($cookie);
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Log the user out
     */
    public function logout(Request $request)
    {
        // Attempt to mark the current device session revoked
        try {
            $fpRaw = $request->cookie('device_fp');
            if ($fpRaw) {
                $fpHash = hash('sha256', $fpRaw);
                // delete matching device record (hash or raw) for this user
                DetectDevice::where('user_id', Auth::id())
                    ->where(function ($q) use ($fpHash, $fpRaw) {
                        $q->where('fingerprint', $fpHash)
                            ->orWhere('fingerprint', $fpRaw);
                    })->delete();
            }
        } catch (\Exception $e) {
            // ignore DB errors during logout
        }
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        $cookie = cookie()->forget('device_fp');
        return redirect('/')->withCookie($cookie);
    }
    // --- small user agent helpers ---
    protected function detectBrowser(string $ua)
    {
        $ua = strtolower($ua);
        if (strpos($ua, 'chrome') !== false && strpos($ua, 'edge') === false && strpos($ua, 'opr') === false) {
            return 'Chrome';
        }
        if (strpos($ua, 'firefox') !== false) {
            return 'Firefox';
        }
        if (strpos($ua, 'safari') !== false && strpos($ua, 'chrome') === false) {
            return 'Safari';
        }
        if (strpos($ua, 'edge') !== false) {
            return 'Edge';
        }
        if (strpos($ua, 'opr') !== false || strpos($ua, 'opera') !== false) {
            return 'Opera';
        }
        return 'Unknown';
    }


    protected function detectPlatform(string $ua)
    {
        $ua = strtolower($ua);
        if (strpos($ua, 'windows') !== false) return 'Windows';
        if (strpos($ua, 'macintosh') !== false || strpos($ua, 'mac os x') !== false) return 'macOS';
        if (strpos($ua, 'android') !== false) return 'Android';
        if (strpos($ua, 'iphone') !== false || strpos($ua, 'ipad') !== false) return 'iOS';
        if (strpos($ua, 'linux') !== false) return 'Linux';
        return 'Unknown';
    }

    protected function detectDeviceType(string $ua)
    {
        $ua = strtolower($ua);
        if (strpos($ua, 'mobile') !== false || strpos($ua, 'iphone') !== false || strpos($ua, 'android') !== false) return 'mobile';
        if (strpos($ua, 'tablet') !== false || strpos($ua, 'ipad') !== false) return 'tablet';
        return 'desktop';
    }
}
