@extends('layouts.app')

@section('content')
<div style="max-width:1000px;margin:28px auto;padding:16px;background:#fff;border-radius:8px;">
    <h2>Users</h2>

    <table style="width:100%;border-collapse:collapse;margin-top:12px;">
        <thead>
            <tr style="text-align:left;border-bottom:1px solid #e5e7eb;">
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Registered</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr style="border-bottom:1px solid #f3f4f6;">
                <td style="padding:8px 4px">{{ $user->id }}</td>
                <td>{{ $user->name ?? '-' }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->created_at }}</td>
                <td>
                    <a href="{{ route('users.show', $user->id) }}">View</a>
                    @auth
                        <a href="{{ route('sessions.index') }}?user_id={{ $user->id }}" style="margin-left:12px;">Sessions</a>
                    @endauth
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top:12px">
        {{ $users->links() }}
    </div>
</div>
@endsection