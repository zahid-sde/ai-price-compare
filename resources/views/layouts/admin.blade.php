<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel - AI Price Compare')</title>
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="icon" type="image/jpeg" href="{{ asset('favicon.jpg') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    @vite(['resources/css/app.css'])
    <style>
        .admin-layout {
            display: flex;
            min-height: 100vh;
        }
        .admin-sidebar {
            width: 250px;
            background: rgba(15, 23, 42, 0.95);
            border-right: 1px solid var(--border-color);
            padding: 1.5rem 1rem;
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }
        .admin-nav {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
        }
        .admin-nav-item a {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.65rem 0.85rem;
            color: var(--text-muted);
            border-radius: var(--radius-sm);
            font-size: 0.9rem;
            font-weight: 500;
        }
        .admin-nav-item a:hover, .admin-nav-item a.active {
            background: rgba(56, 189, 248, 0.15);
            color: var(--primary);
        }
        .admin-main {
            flex: 1;
            padding: 2rem;
            background: var(--bg-dark);
            overflow-y: auto;
        }
        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--border-color);
        }
    </style>
</head>
<body style="flex-direction: row;">

    <div class="admin-layout" style="width: 100%;">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <a href="{{ route('admin.dashboard') }}" class="nav-brand" style="font-size: 1.1rem; display: flex; align-items: center; gap: 0.6rem; text-decoration: none;">
                <img src="{{ asset('images/logo.jpg') }}" alt="AI Price Compare Logo" style="width: 28px; height: 28px; border-radius: 6px; object-fit: cover;">
                <span>AI Price Admin</span>
            </a>

            <ul class="admin-nav">
                <li class="admin-nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        📊 Dashboard
                    </a>
                </li>
                <li class="admin-nav-item">
                    <a href="{{ route('admin.products.index') }}" class="{{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                        🤖 AI Products
                    </a>
                </li>
                <li class="admin-nav-item">
                    <a href="{{ route('admin.plans.index') }}" class="{{ request()->routeIs('admin.plans.*') ? 'active' : '' }}">
                        📋 Plans
                    </a>
                </li>
                <li class="admin-nav-item">
                    <a href="{{ route('admin.prices.index') }}" class="{{ request()->routeIs('admin.prices.*') ? 'active' : '' }}">
                        💰 Pricing & Sources
                    </a>
                </li>
                <li class="admin-nav-item">
                    <a href="{{ route('admin.features.index') }}" class="{{ request()->routeIs('admin.features.index') ? 'active' : '' }}">
                        ⚡ Feature Catalog
                    </a>
                </li>
                <li class="admin-nav-item">
                    <a href="{{ route('admin.features.matrix') }}" class="{{ request()->routeIs('admin.features.matrix') ? 'active' : '' }}">
                        🧩 Feature Matrix
                    </a>
                </li>
                <li class="admin-nav-item">
                    <a href="{{ route('admin.countries.index') }}" class="{{ request()->routeIs('admin.countries.*') ? 'active' : '' }}">
                        🌍 Countries
                    </a>
                </li>
                <li class="admin-nav-item">
                    <a href="{{ route('admin.price_history.index') }}" class="{{ request()->routeIs('admin.price_history.*') ? 'active' : '' }}">
                        📜 Price Audit History
                    </a>
                </li>
                <li class="admin-nav-item">
                    <a href="{{ route('admin.links.index') }}" class="{{ request()->routeIs('admin.links.*') ? 'active' : '' }}">
                        🔗 Link Management
                    </a>
                </li>
                <li class="admin-nav-item">
                    <a href="{{ route('admin.analytics.index') }}" class="{{ request()->routeIs('admin.analytics.*') ? 'active' : '' }}">
                        📈 Click Analytics
                    </a>
                </li>
            </ul>

            <div style="margin-top: auto; border-top: 1px solid var(--border-color); pt-3; padding-top: 1rem;">
                <a href="{{ route('home') }}" target="_blank" class="btn btn-secondary btn-sm" style="width: 100%; margin-bottom: 0.5rem;">
                    🌐 View Website
                </a>
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline btn-sm" style="width: 100%; color: var(--danger); border-color: rgba(248,113,113,0.3);">
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="admin-main">
            <header class="admin-header">
                <div>
                    <h1 style="font-size: 1.5rem;">@yield('page_title', 'Admin Dashboard')</h1>
                    <p style="font-size: 0.85rem; color: var(--text-dim);">Logged in as {{ auth()->user()->email ?? 'Admin' }}</p>
                </div>
                <div style="display: flex; gap: 0.5rem;">
                    <a href="{{ route('admin.prices.create') }}" class="btn btn-primary btn-sm">+ Add/Verify Price</a>
                    <a href="{{ route('admin.products.create') }}" class="btn btn-secondary btn-sm">+ New Product</a>
                </div>
            </header>

            @if(session('success'))
                <div style="margin-bottom: 1.5rem; padding: 0.75rem 1.25rem; background: rgba(52, 211, 153, 0.15); border: 1px solid var(--success); color: var(--success); border-radius: var(--radius-sm);">
                    ✓ {{ session('success') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>

</body>
</html>
