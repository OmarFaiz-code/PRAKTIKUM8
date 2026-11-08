<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- CSRF token dibaca oleh JavaScript untuk request AJAX --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SiPustaka')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">SiPustaka</a>
        <div class="navbar-nav me-auto">
            @auth
                <a class="nav-link" href="{{ route('buku.index') }}">Buku</a>
                <a class="nav-link" href="{{ route('peminjaman.index') }}">Peminjaman</a>
                @if(auth()->user()->isAdmin())
                    <a class="nav-link" href="{{ route('users.index') }}">Kelola User</a>
                @endif
            @endauth
        </div>
        <div class="d-flex align-items-center text-white gap-2">
            @auth
                <span>{{ auth()->user()->name }}
                    <span class="badge bg-light text-primary">{{ auth()->user()->role }}</span>
                </span>
                <form method="POST" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <button class="btn btn-sm btn-outline-light">Logout</button>
                </form>
            @else
                <a class="btn btn-sm btn-outline-light" href="{{ route('login') }}">Login</a>
                <a class="btn btn-sm btn-light" href="{{ route('register') }}">Register</a>
            @endauth
        </div>
    </div>
</nav>

<div class="container pb-5">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @yield('content')
</div>

@stack('scripts')
</body>
</html>
