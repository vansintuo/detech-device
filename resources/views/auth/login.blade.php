<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <style>body{font-family:'Nunito',sans-serif;}</style>
</head>
<body>
    <div style="max-width:420px;margin:40px auto;padding:24px;border:1px solid #e5e7eb;border-radius:8px;background:#fff;">
        <h2 style="margin-bottom:16px">Login</h2>

        @if ($errors->any())
            <div style="color:#b91c1c;margin-bottom:12px;">
                <ul style="margin:0;padding-left:18px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login.attempt') }}">
            @csrf

            <div style="margin-bottom:12px;">
                <label for="email">Email</label><br>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:4px;" />
            </div>

            <div style="margin-bottom:12px;">
                <label for="password">Password</label><br>
                <input id="password" name="password" type="password" required style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:4px;" />
            </div>

            <div style="margin-bottom:16px;">
                <label><input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }} /> Remember me</label>
            </div>

            <div>
                <button type="submit" style="background:#111827;color:#fff;padding:10px 14px;border-radius:6px;border:none;">Log in</button>
            </div>
        </form>
    </div>
</body>
</html>