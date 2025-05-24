<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Dashboard')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { display: flex; min-height: 100vh; margin: 0; }
        .sidebar {
            width: 250px;
            background-color: #343a40;
            color: white;
            padding: 1rem;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 0.5rem 0;
        }
        .sidebar a:hover {
            background-color: #495057;
        }
        .main-content {
            flex: 1;
            padding: 2rem;
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <h4>MyApp</h4>
        <hr>

        @php
            $job = strtolower(auth()->user()->job); // lowercase langsung dari database
        @endphp

        {{-- Dashboard --}}
        <a href="{{ url('/dashboard/' . str_replace(' ', '-', $job)) }}">Dashboard</a>

        {{-- Data Mobil: Manajer, Marketing, Service, Finance --}}
        @if(in_array($job, ['manajer', 'divisi marketing', 'staff service', 'divisi finance']))
            <a href="{{ route('mobil.index') }}">Data Mobil</a>
        @endif

        {{-- Data Pembeli + Submenu --}}
        @if(in_array($job, ['manajer', 'divisi marketing', 'divisi finance']))
            <div class="mb-2">
                <a href="{{ route('pembeli.index') }}">Data Pembeli</a>
                <div style="margin-left: 10px;">
                    <a href="{{ route('pembeli.create') }}" style="font-size: 0.9rem;">➤ Tambah Pembeli</a>
                </div>
            </div>
        @endif

        {{-- Transaksi --}}
        @if(in_array($job, ['manajer', 'divisi finance']))
            <a href="{{ route('transaksi.index') }}">Transaksi</a>
        @endif

        <form action="{{ route('logout') }}" method="POST" class="mt-4">
            @csrf
            <button type="submit" class="btn btn-light btn-sm w-100">Logout</button>
        </form>
    </div>

    <div class="main-content">
        @yield('content')
    </div>

</body>
</html>
