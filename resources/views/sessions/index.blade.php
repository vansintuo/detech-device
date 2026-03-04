@extends('layouts.app')

@section('content')
<div style="max-width:900px;margin:36px auto;padding:16px;">
    <h2>Active Sessions</h2>

    @if(session('status'))
        <div style="color:green;margin:8px 0">{{ session('status') }}</div>
    @endif

    <div style="margin-bottom:12px;text-align:right;">
        <form method="POST" action="{{ route('sessions.revokeAll') }}" style="display:inline">
            @csrf
            <button type="submit" style="background:#ef4444;color:white;border:none;padding:8px 12px;border-radius:6px;">Revoke All Sessions</button>
        </form>
    </div>

    <table style="width:100%;border-collapse:collapse;margin-top:12px;">
        <thead>
            <tr style="text-align:left;border-bottom:1px solid #e5e7eb;">
                <th>Device</th>
                <th>Browser</th>
                <th>Platform</th>
                <th>IP</th>
                <th>Last active</th>
                <th>Revoked</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($sessions as $s)
                <tr style="border-bottom:1px solid #f3f4f6;">
                    <td style="padding:8px 4px">{{ $s->device_name ?? 'Unknown' }}</td>
                    <td>{{ $s->browser }}</td>
                    <td>{{ $s->platform }}</td>
                    <td>{{ $s->ip }}</td>
                    <td>{{ $s->last_active_at }}</td>
                    <td>{{ $s->revoked ? 'Yes' : 'No' }}</td>
                    <td style="text-align:right;">
                        @if(! $s->revoked)
                            <form method="POST" action="{{ route('sessions.revoke', $s->id) }}" style="display:inline">
                                @csrf 
                                <button type="submit" style="background:#ef4444;color:white;border:none;padding:6px 10px;border-radius:6px;">Revoke</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
