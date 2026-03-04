<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>App</title>
    <style>body{font-family:system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;background:#f9fafb;color:#111827;margin:0;padding:0;} .container{max-width:1100px;margin:0 auto;padding:18px;} a{color:#2563eb;}</style>
</head>
<body>
    <div class="container">
        <nav style="display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;">
            <div><a href="/">Home</a></div>
            <div>
                @auth
                    <a href="/sessions" style="margin-right:12px;">Sessions</a>
                    <form method="POST" action="{{ route('logout') }}" style="display:inline">
                        @csrf
                        <button type="submit">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}">Login</a>
                @endauth
            </div>
        </nav>

        @yield('content')
    </div>
</body>
</html>