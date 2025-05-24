@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <h2>Halo, {{ auth()->user()->name }}</h2>
    <p>Selamat datang di dashboard.</p>

    <h4>Riwayat Login Terakhir</h4>
    <ul class="list-group">
        @foreach ($loginLogs as $log)
            <li class="list-group-item">
                IP: {{ $log->ip_address }} |
                Browser: {{ $log->user_agent }} |
                Waktu: {{ $log->created_at->format('d M Y H:i') }}
            </li>
        @endforeach
    </ul>
@endsection
