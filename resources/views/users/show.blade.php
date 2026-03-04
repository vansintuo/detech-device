@extends('layouts.app')

@section('content')
<div style="max-width:800px;margin:28px auto;padding:16px;background:#fff;border-radius:8px;">
    <h2>User #{{ $user->id }}</h2>
    <p><strong>Name:</strong> {{ $user->name }}</p>
    <p><strong>Email:</strong> {{ $user->email }}</p>
    <p><strong>Registered:</strong> {{ $user->created_at }}</p>

    <p><a href="{{ route('sessions.index') }}?user_id={{ $user->id }}">View Sessions</a></p>
</div>
@endsection